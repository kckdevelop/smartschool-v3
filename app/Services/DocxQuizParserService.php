<?php

namespace App\Services;

use ZipArchive;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Storage;

class DocxQuizParserService
{
    /**
     * Parse uploaded docx file and return structured questions array.
     *
     * @param string $filePath Absolute path to .docx file
     * @return array
     */
    public function parseDocx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Gagal membuka file Word (.docx). Pastikan file tidak rusak.");
        }

        $folderName = 'kuis_images/' . date('Ymd_His') . '_' . uniqid();
        Storage::disk('public')->makeDirectory($folderName);

        // 1. Map relationships (rId -> image filename)
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        $relImageMap = []; // rId => public_url

        if ($relsXml) {
            $domRels = new DOMDocument();
            @$domRels->loadXML($relsXml);
            $relationships = $domRels->getElementsByTagName('Relationship');

            foreach ($relationships as $rel) {
                $id = $rel->getAttribute('Id');
                $target = $rel->getAttribute('Target');
                $type = $rel->getAttribute('Type');

                if (str_contains($type, 'image') || str_contains($target, 'media/')) {
                    $mediaPath = 'word/' . ltrim($target, '/');
                    if (str_starts_with($target, 'media/')) {
                        $mediaPath = 'word/' . $target;
                    }

                    $imageData = $zip->getFromName($mediaPath);
                    if ($imageData) {
                        $fileName = basename($target);
                        $savedPath = $folderName . '/' . $fileName;
                        Storage::disk('public')->put($savedPath, $imageData);
                        $relImageMap[$id] = Storage::url($savedPath);
                    }
                }
            }
        }

        // 2. Parse main document XML
        $docXml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!$docXml) {
            throw new \Exception("File Word tidak memiliki struktur document.xml yang valid.");
        }

        $dom = new DOMDocument();
        @$dom->loadXML($docXml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xpath->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xpath->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xpath->registerNamespace('v', 'urn:schemas-microsoft-com:vml');

        $tables = $xpath->query('//w:tbl');
        $questions = [];

        foreach ($tables as $table) {
            $rows = $xpath->query('.//w:tr', $table);
            $rowIndex = 0;

            foreach ($rows as $row) {
                $rowIndex++;
                $cells = $xpath->query('.//w:tc', $row);

                if ($cells->length < 4) {
                    continue; // Skip rows that aren't question format
                }

                // Check header row
                $firstCellText = trim($this->extractCellTextAndImages($cells->item(0), $xpath, $relImageMap));
                if (strtolower($firstCellText) === 'no' || strtolower($firstCellText) === 'nomor') {
                    continue;
                }

                $nomorRaw      = $firstCellText;
                $jenisRaw      = $cells->length > 1 ? trim($this->extractCellTextAndImages($cells->item(1), $xpath, $relImageMap)) : 'pilihan_ganda';
                $pertanyaanHtml= $cells->length > 2 ? trim($this->extractCellTextAndImages($cells->item(2), $xpath, $relImageMap)) : '';

                // Handle choices columns
                $pilihanA = $cells->length > 3 ? trim($this->extractCellTextAndImages($cells->item(3), $xpath, $relImageMap)) : '';
                $pilihanB = $cells->length > 4 ? trim($this->extractCellTextAndImages($cells->item(4), $xpath, $relImageMap)) : '';
                $pilihanC = $cells->length > 5 ? trim($this->extractCellTextAndImages($cells->item(5), $xpath, $relImageMap)) : '';
                $pilihanD = $cells->length > 6 ? trim($this->extractCellTextAndImages($cells->item(6), $xpath, $relImageMap)) : '';
                $pilihanE = $cells->length > 7 ? trim($this->extractCellTextAndImages($cells->item(7), $xpath, $relImageMap)) : '';

                // Answer key is usually last column
                $lastIndex = $cells->length - 1;
                $kunciRaw  = trim(strip_tags($this->extractCellTextAndImages($cells->item($lastIndex), $xpath, $relImageMap)));

                if (empty($pertanyaanHtml)) {
                    continue;
                }

                // Normalize jenis_soal
                $jenisClean = 'pilihan_ganda';
                $jLower = strtolower($jenisRaw);
                if (str_contains($jLower, 'benar') || str_contains($jLower, 'salah') || str_contains($jLower, 'tf') || str_contains($jLower, 'bs')) {
                    $jenisClean = 'benar_salah';
                } elseif (str_contains($jLower, 'komplek') || str_contains($jLower, 'complex') || str_contains($jLower, 'ganda komplek')) {
                    $jenisClean = 'pilihan_ganda_komplek';
                }

                // Extract main question image if separate
                $mainGambar = null;

                // Build choices list
                $choices = [];
                if ($jenisClean === 'benar_salah') {
                    $choices[] = ['kunci' => 'Benar', 'teks' => !empty($pilihanA) ? $pilihanA : 'Benar', 'gambar' => null];
                    $choices[] = ['kunci' => 'Salah', 'teks' => !empty($pilihanB) ? $pilihanB : 'Salah', 'gambar' => null];
                } else {
                    if (!empty($pilihanA)) $choices[] = ['kunci' => 'A', 'teks' => $pilihanA, 'gambar' => null];
                    if (!empty($pilihanB)) $choices[] = ['kunci' => 'B', 'teks' => $pilihanB, 'gambar' => null];
                    if (!empty($pilihanC)) $choices[] = ['kunci' => 'C', 'teks' => $pilihanC, 'gambar' => null];
                    if (!empty($pilihanD)) $choices[] = ['kunci' => 'D', 'teks' => $pilihanD, 'gambar' => null];
                    if (!empty($pilihanE)) $choices[] = ['kunci' => 'E', 'teks' => $pilihanE, 'gambar' => null];
                }

                $questions[] = [
                    'nomor_soal'    => is_numeric($nomorRaw) ? (int)$nomorRaw : count($questions) + 1,
                    'jenis_soal'    => $jenisClean,
                    'pertanyaan'    => $pertanyaanHtml,
                    'gambar'        => $mainGambar,
                    'kunci_jawaban' => strtoupper($kunciRaw),
                    'pilihan'       => $choices,
                ];
            }
        }

        return $questions;
    }

    /**
     * Extract cell text and embedded image HTML.
     */
    private function extractCellTextAndImages($tcNode, DOMXPath $xpath, array $relImageMap): string
    {
        $html = '';
        $paragraphs = $xpath->query('.//w:p', $tcNode);

        foreach ($paragraphs as $p) {
            $pText = '';
            $nodes = $xpath->query('.//w:t | .//a:blip | .//v:imagedata', $p);

            foreach ($nodes as $node) {
                if ($node->nodeName === 'w:t') {
                    $pText .= htmlspecialchars($node->nodeValue);
                } elseif ($node->nodeName === 'a:blip') {
                    $embedId = $node->getAttribute('r:embed');
                    if (isset($relImageMap[$embedId])) {
                        $imgUrl = $relImageMap[$embedId];
                        $pText .= '<br><img src="' . $imgUrl . '" class="img-fluid rounded my-2" style="max-height: 250px; display: block;" /><br>';
                    }
                } elseif ($node->nodeName === 'v:imagedata') {
                    $relId = $node->getAttribute('r:id');
                    if (isset($relImageMap[$relId])) {
                        $imgUrl = $relImageMap[$relId];
                        $pText .= '<br><img src="' . $imgUrl . '" class="img-fluid rounded my-2" style="max-height: 250px; display: block;" /><br>';
                    }
                }
            }

            if (!empty(trim($pText))) {
                $html .= '<p class="mb-1">' . $pText . '</p>';
            }
        }

        if (empty($html)) {
            // Fallback for cells without w:p
            $textOnly = htmlspecialchars(trim($tcNode->nodeValue));
            if (!empty($textOnly)) {
                $html = $textOnly;
            }
        }

        return $html;
    }
}
