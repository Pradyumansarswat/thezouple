<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class CloudinaryService
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;
    protected $cloudinaryUrl;
    protected $uploadFolder;

    public function __construct()
    {
        $this->cloudinaryUrl = config('cloudinary.url');
        $this->cloudName = config('cloudinary.cloud_name');
        $this->apiKey = config('cloudinary.api_key');
        $this->apiSecret = config('cloudinary.api_secret');
        $this->uploadFolder = trim((string) config('cloudinary.upload_folder', 'zouple'), '/');
        $this->hydrateFromUrl();
    }

    public function isConfigured()
    {
        return $this->cloudName && $this->apiKey && $this->apiSecret && $this->isValidCloudName($this->cloudName) && $this->sdkAvailable();
    }

    public function uploadImage($file, $folder = null)
    {
        return $this->upload($file, $folder, 'image');
    }

    public function uploadVideo($file, $folder = null)
    {
        return $this->upload($file, $folder, 'video');
    }

    public function deleteFile($publicId, $resourceType = 'image')
    {
        $publicId = trim((string) $publicId);
        if ($publicId === '' || !$this->isConfigured()) {
            return false;
        }

        try {
            if (class_exists('\Cloudinary\Api\Upload\UploadApi')) {
                $this->configureV2();
                $api = new \Cloudinary\Api\Upload\UploadApi();
                $api->destroy($publicId, ['resource_type' => $resourceType]);
                return true;
            }

            if (class_exists('\Cloudinary\Uploader')) {
                $this->configureV1();
                \Cloudinary\Uploader::destroy($publicId, ['resource_type' => $resourceType]);
                return true;
            }
        } catch (\Exception $e) {
            Log::warning('Cloudinary delete failed.', [
                'public_id' => $publicId,
                'resource_type' => $resourceType,
                'message' => $e->getMessage(),
            ]);
        }

        return false;
    }

    public function deleteByUrl($url, $resourceType = 'image')
    {
        $publicId = $this->getPublicIdFromUrl($url);
        return $publicId ? $this->deleteFile($publicId, $resourceType) : false;
    }

    public function getPublicIdFromUrl($url)
    {
        $url = trim((string) $url);
        if ($url === '' || !preg_match('#^https?://#i', $url)) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $parts = explode('/upload/', $path, 2);
        if (count($parts) !== 2) {
            return null;
        }

        $publicPath = preg_replace('#^v\d+/#', '', ltrim($parts[1], '/'));
        $publicPath = preg_replace('#\.[A-Za-z0-9]+$#', '', $publicPath);

        return $publicPath ?: null;
    }

    public function isCloudinaryUrl($url)
    {
        return is_string($url) && preg_match('#^https?://res\.cloudinary\.com/#i', trim($url));
    }

    protected function upload($file, $folder, $resourceType)
    {
        if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
            throw new \RuntimeException('Cloudinary credentials are missing. Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET in .env.');
        }

        if (!$this->isValidCloudName($this->cloudName)) {
            throw new \RuntimeException('Invalid Cloudinary cloud name "' . $this->cloudName . '". Use the exact lowercase Cloud name from Cloudinary Dashboard, not the account/display name.');
        }

        if (!$this->sdkAvailable()) {
            throw new \RuntimeException('Cloudinary PHP SDK is not installed. Run composer install or composer require cloudinary/cloudinary_php.');
        }

        $path = $this->pathFromFile($file);
        $options = [
            'folder' => $this->folderPath($folder),
            'resource_type' => $resourceType,
        ];

        try {
            if (class_exists('\Cloudinary\Api\Upload\UploadApi')) {
                $this->configureV2();
                $api = new \Cloudinary\Api\Upload\UploadApi();
                $result = $api->upload($path, $options);
                return $this->normalizeUploadResult($result);
            }

            if (class_exists('\Cloudinary\Uploader')) {
                $this->configureV1();
                $result = \Cloudinary\Uploader::upload($path, $options);
                return $this->normalizeUploadResult($result);
            }
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed.', [
                'resource_type' => $resourceType,
                'folder' => $folder,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        throw new \RuntimeException('Cloudinary SDK is not available.');
    }

    protected function normalizeUploadResult($result)
    {
        $data = is_object($result) && method_exists($result, 'getArrayCopy')
            ? $result->getArrayCopy()
            : (array) $result;

        return [
            'url' => isset($data['secure_url']) ? $data['secure_url'] : (isset($data['url']) ? $data['url'] : null),
            'public_id' => isset($data['public_id']) ? $data['public_id'] : null,
            'resource_type' => isset($data['resource_type']) ? $data['resource_type'] : null,
        ];
    }

    protected function folderPath($folder)
    {
        $folder = trim((string) $folder, '/');
        return trim($this->uploadFolder . ($folder ? '/' . $folder : ''), '/');
    }

    protected function pathFromFile($file)
    {
        if ($file instanceof UploadedFile || $file instanceof SymfonyUploadedFile) {
            $path = $file->getRealPath() ?: $file->getPathname();
            if ($path && is_file($path)) {
                return $path;
            }
        }

        if (is_string($file) && is_file($file)) {
            return $file;
        }

        Log::error('Invalid upload file passed to Cloudinary.', [
            'type' => is_object($file) ? get_class($file) : gettype($file),
            'real_path' => is_object($file) && method_exists($file, 'getRealPath') ? $file->getRealPath() : null,
            'path_name' => is_object($file) && method_exists($file, 'getPathname') ? $file->getPathname() : null,
            'path_exists' => is_object($file) && method_exists($file, 'getPathname') ? is_file((string) $file->getPathname()) : null,
        ]);

        throw new \InvalidArgumentException('Invalid upload file.');
    }

    protected function sdkAvailable()
    {
        return class_exists('\Cloudinary\Api\Upload\UploadApi') || class_exists('\Cloudinary\Uploader');
    }

    protected function configureV2()
    {
        if (class_exists('\Cloudinary\Configuration\Configuration')) {
            \Cloudinary\Configuration\Configuration::instance([
                'cloud' => [
                    'cloud_name' => $this->cloudName,
                    'api_key' => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                ],
                'url' => ['secure' => true],
            ]);
        }
    }

    protected function configureV1()
    {
        if (class_exists('\Cloudinary')) {
            \Cloudinary::config([
                'cloud_name' => $this->cloudName,
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'secure' => true,
            ]);
        }
    }

    protected function hydrateFromUrl()
    {
        $url = trim((string) $this->cloudinaryUrl);
        if ($url === '' || !preg_match('#^cloudinary://([^:]+):([^@]+)@([^/]+)#', $url, $matches)) {
            return;
        }

        $this->apiKey = $this->apiKey ?: $matches[1];
        $this->apiSecret = $this->apiSecret ?: $matches[2];
        $this->cloudName = $this->cloudName ?: $matches[3];
    }

    protected function isValidCloudName($cloudName)
    {
        return is_string($cloudName) && preg_match('/^[a-z0-9-]+$/', trim($cloudName));
    }
}
