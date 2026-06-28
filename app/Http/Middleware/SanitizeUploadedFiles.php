<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class SanitizeUploadedFiles
{
    /**
     * Remove invalid scalar values from the file bag before Laravel converts uploads.
     *
     * Some legacy admin forms/plugins can submit old filenames inside upload fields.
     * Laravel 5.8 then crashes when Request::all() or validation tries to convert
     * those strings into UploadedFile instances. Cleaning the file bag here keeps
     * every controller from showing a fatal error screen.
     */
    public function handle($request, Closure $next)
    {
        if ($request->files) {
            $cleanFiles = $this->cleanFiles($request->files->all());
            $rawFiles = $this->recoverRawFiles($_FILES ?: []);
            $request->files->replace($this->mergeFiles($cleanFiles, $rawFiles));
        }

        return $next($request);
    }

    private function cleanFiles($files)
    {
        $clean = [];

        foreach ($files as $key => $file) {
            if ($file instanceof SymfonyUploadedFile) {
                $clean[$key] = $file;
                continue;
            }

            if (is_array($file)) {
                $nested = $this->cleanFiles($file);
                if (!empty($nested)) {
                    $clean[$key] = $nested;
                }
            }
        }

        return $clean;
    }

    private function recoverRawFiles($files)
    {
        $recovered = [];

        foreach ($files as $key => $file) {
            if (!is_array($file) || !array_key_exists('name', $file)) {
                continue;
            }

            if (is_array($file['name'])) {
                $nested = $this->recoverRawFileArray($file);
                if (!empty($nested)) {
                    $recovered[$key] = $nested;
                }
                continue;
            }

            $uploadedFile = $this->uploadedFileFromRaw($file);
            if ($uploadedFile) {
                $recovered[$key] = $uploadedFile;
            }
        }

        return $recovered;
    }

    private function recoverRawFileArray(array $file)
    {
        $recovered = [];

        foreach ($file['name'] as $index => $name) {
            $raw = [
                'name' => $name,
                'type' => isset($file['type'][$index]) ? $file['type'][$index] : null,
                'tmp_name' => isset($file['tmp_name'][$index]) ? $file['tmp_name'][$index] : null,
                'error' => isset($file['error'][$index]) ? $file['error'][$index] : UPLOAD_ERR_NO_FILE,
                'size' => isset($file['size'][$index]) ? $file['size'][$index] : 0,
            ];

            $uploadedFile = $this->uploadedFileFromRaw($raw);
            if ($uploadedFile) {
                $recovered[$index] = $uploadedFile;
            }
        }

        return $recovered;
    }

    private function uploadedFileFromRaw(array $file)
    {
        $error = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
        $tmpName = isset($file['tmp_name']) ? $file['tmp_name'] : null;
        $originalName = isset($file['name']) ? $file['name'] : 'upload';
        $mimeType = isset($file['type']) ? $file['type'] : null;

        if ($error === UPLOAD_ERR_OK && $tmpName && is_file($tmpName)) {
            return new SymfonyUploadedFile($tmpName, $originalName, $mimeType, $error, true);
        }

        return null;
    }

    private function mergeFiles(array $cleanFiles, array $rawFiles)
    {
        foreach ($rawFiles as $key => $file) {
            if (!isset($cleanFiles[$key]) || $cleanFiles[$key] === [] || $cleanFiles[$key] === null) {
                $cleanFiles[$key] = $file;
                continue;
            }

            if (is_array($cleanFiles[$key]) && is_array($file)) {
                $cleanFiles[$key] = $this->mergeFiles($cleanFiles[$key], $file);
            }
        }

        return $cleanFiles;
    }
}
