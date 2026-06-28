<?php

namespace App\Console\Commands;

use App\Services\CloudinaryService;
use Illuminate\Console\Command;

class TestCloudinaryUpload extends Command
{
    protected $signature = 'zouple:test-cloudinary {file? : Optional local image file to upload}';

    protected $description = 'Upload a small test image to Cloudinary and print the returned secure URL.';

    public function handle(CloudinaryService $cloudinary)
    {
        $file = $this->argument('file') ?: public_path('img/dark-logo.png');

        if (!is_file($file)) {
            $this->error('Test file was not found: ' . $file);
            return 1;
        }

        try {
            $result = $cloudinary->uploadImage($file, 'debug');
            $this->info('Cloudinary upload OK');
            $this->line('URL: ' . $result['url']);
            $this->line('Public ID: ' . $result['public_id']);
            return 0;
        } catch (\Exception $exception) {
            $this->error('Cloudinary upload failed: ' . $exception->getMessage());
            return 1;
        }
    }
}
