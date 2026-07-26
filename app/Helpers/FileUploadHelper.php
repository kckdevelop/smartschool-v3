<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{
    /**
     * Store a file or base64 string from a request.
     *
     * @param Request|mixed $request Request object or array/input value
     * @param string|null $key Input field key (if $request is Request)
     * @param string $directory Target storage directory (e.g. 'guru-foto')
     * @param string $disk Storage disk (default 'public')
     * @return string|null Relative storage path or null if no valid file
     */
    public static function storeFile($request, ?string $key, string $directory, string $disk = 'public'): ?string
    {
        $input = null;

        if ($request instanceof Request && $key !== null) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                if ($file instanceof UploadedFile && $file->isValid()) {
                    return $file->store($directory, $disk);
                }
            }
            $input = $request->input($key);
        } else {
            $input = $request;
        }

        if (empty($input)) {
            return null;
        }

        if ($input instanceof UploadedFile) {
            if ($input->isValid()) {
                return $input->store($directory, $disk);
            }
            return null;
        }

        if (is_string($input)) {
            return static::storeBase64String($input, $directory, $disk);
        }

        return null;
    }

    /**
     * Store multiple files or base64 strings from a request.
     *
     * @param Request $request
     * @param string $key Input field key e.g. 'fotos'
     * @param string $directory
     * @param string $disk
     * @return array List of relative stored file paths
     */
    public static function storeMultipleFiles(Request $request, string $key, string $directory, string $disk = 'public'): array
    {
        $stored = [];

        if ($request->hasFile($key)) {
            $files = $request->file($key);
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $stored[] = $file->store($directory, $disk);
                }
            }
        }

        $input = $request->input($key);
        if (!empty($input)) {
            if (!is_array($input)) {
                $input = [$input];
            }
            foreach ($input as $item) {
                if (is_string($item) && !empty($item)) {
                    // Check if it's already a relative path or storage URL
                    if (str_contains($item, '/storage/') || !str_contains($item, ';base64,')) {
                        $clean = preg_replace('#^https?://[^/]+/storage/#', '', $item);
                        $clean = preg_replace('#^/storage/#', '', $clean);
                        if (!empty($clean) && !str_starts_with($clean, 'data:image')) {
                            $stored[] = ltrim($clean, '/');
                            continue;
                        }
                    }
                    $path = static::storeBase64String($item, $directory, $disk);
                    if ($path) {
                        $stored[] = $path;
                    }
                }
            }
        }

        return array_values(array_unique(array_filter($stored)));
    }

    /**
     * Store a base64 encoded string as a file in storage.
     *
     * @param string $base64String
     * @param string $directory
     * @param string $disk
     * @return string|null Relative storage path
     */
    public static function storeBase64String(string $base64String, string $directory, string $disk = 'public'): ?string
    {
        $base64String = trim($base64String);

        if (empty($base64String)) {
            return null;
        }

        // Check if it's a URL or existing path, not a base64 string
        if (str_starts_with($base64String, 'http://') || str_starts_with($base64String, 'https://') || (str_contains($base64String, '/') && !str_contains($base64String, ';base64,'))) {
            $clean = preg_replace('#^https?://[^/]+/storage/#', '', $base64String);
            $clean = preg_replace('#^/storage/#', '', $clean);
            return ltrim($clean, '/');
        }

        $extension = 'jpg'; // default fallback
        $data = $base64String;

        // Matches data URI format: data:image/png;base64,iVBORw0KGgo...
        if (preg_match('/^data:(.*?);base64,(.*)$/s', $base64String, $matches)) {
            $mimeType = strtolower($matches[1]);
            $data = $matches[2];

            $mimeMap = [
                'image/jpeg'      => 'jpg',
                'image/jpg'       => 'jpg',
                'image/png'       => 'png',
                'image/gif'       => 'gif',
                'image/webp'      => 'webp',
                'image/heic'      => 'heic',
                'image/svg+xml'   => 'svg',
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'application/vnd.ms-excel' => 'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                'application/zip' => 'zip',
                'text/plain'      => 'txt',
            ];

            if (isset($mimeMap[$mimeType])) {
                $extension = $mimeMap[$mimeType];
            }
        }

        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            return null;
        }

        // Detect mime type from decoded binary data if extension is default
        if ($extension === 'jpg' && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $decoded);
            finfo_close($finfo);

            if ($mime) {
                if ($mime === 'image/png') $extension = 'png';
                elseif ($mime === 'image/webp') $extension = 'webp';
                elseif ($mime === 'image/gif') $extension = 'gif';
                elseif ($mime === 'application/pdf') $extension = 'pdf';
            }
        }

        $filename = Str::random(30) . '.' . $extension;
        $relativePath = rtrim($directory, '/') . '/' . $filename;

        if (Storage::disk($disk)->put($relativePath, $decoded)) {
            return $relativePath;
        }

        return null;
    }
}
