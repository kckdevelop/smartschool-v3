<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use Illuminate\Support\Facades\File;

class GenerateQuizTemplateCommand extends Command
{
    protected $signature = 'quiz:generate-template';
    protected $description = 'Generates the downloadable .docx quiz template for teachers';

    public function handle()
    {
        $templateDir = public_path('templates');
        if (!File::exists($templateDir)) {
            File::makeDirectory($templateDir, 0755, true);
        }

        $filePath = $templateDir . '/template_kuis_smartschool.docx';

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';

        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <w:body>
    <w:p>
      <w:r><w:rPr><w:b/><w:sz w:val="32"/></w:rPr><w:t>TEMPLATE IMPOR SOAL KUIS LMS SMARTSCHOOL</w:t></w:r>
    </w:p>
    <w:p>
      <w:r><w:t>Petunjuk Pengisian:</w:t></w:r>
    </w:p>
    <w:p><w:r><w:t>1. Jangan mengubah nama kolom pada baris pertama tabel.</w:t></w:r></w:p>
    <w:p><w:r><w:t>2. Tipe Soal yang didukung: Pilihan Ganda, Benar Salah, Pilihan Ganda Komplek.</w:t></w:r></w:p>
    <w:p><w:r><w:t>3. Untuk Pilihan Ganda Komplek, Kunci Jawaban diisi dipisahkan koma (Contoh: A, C).</w:t></w:r></w:p>
    <w:p><w:r><w:t>4. Untuk Benar Salah, Kunci Jawaban diisi: Benar atau Salah.</w:t></w:r></w:p>
    <w:p><w:r><w:t>5. Anda dapat mengisipkan Gambar langsung di dalam sel tabel pertanyaan maupun pilihan jawaban.</w:t></w:r></w:p>
    <w:p/>
    
    <w:tbl>
      <w:tblPr>
        <w:tblW w:w="0" w:type="auto"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>
          <w:left w:val="single" w:sz="4" w:space="0" w:color="000000"/>
          <w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>
          <w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="CCCCCC"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="CCCCCC"/>
        </w:tblBorders>
      </w:tblPr>
      
      <!-- Table Header -->
      <w:tr>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>No</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Jenis Soal</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pertanyaan / Gambar</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pilihan A</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pilihan B</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pilihan C</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pilihan D</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Pilihan E</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Kunci Jawaban</w:t></w:r></w:p></w:tc>
      </w:tr>

      <!-- Row 1: Pilihan Ganda -->
      <w:tr>
        <w:tc><w:p><w:r><w:t>1</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Pilihan Ganda</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Apakah nama ibu kota negara Indonesia saat ini?</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Jakarta</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Bandung</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Surabaya</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Medan</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Semarang</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>A</w:t></w:r></w:p></w:tc>
      </w:tr>

      <!-- Row 2: Benar Salah -->
      <w:tr>
        <w:tc><w:p><w:r><w:t>2</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Benar Salah</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Matahari terbit dari sebelah barat.</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Benar</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Salah</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t/></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t/></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t/></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Salah</w:t></w:r></w:p></w:tc>
      </w:tr>

      <!-- Row 3: Pilihan Ganda Komplek -->
      <w:tr>
        <w:tc><w:p><w:r><w:t>3</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Pilihan Ganda Komplek</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Manakah di antara berikut ini yang termasuk perangkat keras (hardware) komputer?</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Processor / CPU</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>RAM Memory</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Sistem Operasi Windows</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Adobe Photoshop</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>Solid State Drive (SSD)</w:t></w:r></w:p></w:tc>
        <w:tc><w:p><w:r><w:t>A, B, E</w:t></w:r></w:p></w:tc>
      </w:tr>

    </w:tbl>
  </w:body>
</w:document>';

        $zip = new ZipArchive();
        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('[Content_Types].xml', $contentTypes);
            $zip->addFromString('_rels/.rels', $rels);
            $zip->addFromString('word/document.xml', $documentXml);
            $zip->close();
            $this->info("Template kuis Word berhasil dibuat di: {$filePath}");
        } else {
            $this->error("Gagal membuat file template .docx");
        }
    }
}
