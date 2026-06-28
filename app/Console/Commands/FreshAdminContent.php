<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class FreshAdminContent extends Command
{
    protected $signature = 'zouple:fresh-admin-content
        {--force : Required to actually clear data}
        {--include-orders : Also clear order records}
        {--include-enquiries : Also clear contact/subscriber/enquiry records}
        {--remove-local-uploads : Remove old files inside public/upload}';

    protected $description = 'Clear old admin-managed ecommerce content so the admin can upload fresh Cloudinary media.';

    public function handle()
    {
        if (!$this->option('force')) {
            $this->error('Nothing changed. Re-run with --force when you are ready.');
            return 1;
        }

        $tables = [
            'product_gallery_images',
            'product_attributes',
            'product_quantity',
            'flash_sale',
            'products',
            'categorys',
            'meta_tags',
            'sliders',
            'banner',
            'offerbanners',
            'flash_banner',
            'login_banner',
            'customer_shirt',
            'blog',
            'testimonial',
            'video',
            'cms',
            'review',
            'product_coupon',
            'customer_coupon',
            'price_coupon',
            'carts',
            'wishlists',
        ];

        if ($this->option('include-orders')) {
            $tables = array_merge($tables, [
                'order_system',
                'order_address',
                'order_old_msg',
            ]);
        }

        if ($this->option('include-enquiries')) {
            $tables = array_merge($tables, [
                'contact',
                'replay_contact',
                'subscribe',
                'getnotification',
                'send_message',
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (array_unique($tables) as $table) {
            if (!Schema::hasTable($table)) {
                $this->line('Skip missing table: ' . $table);
                continue;
            }

            DB::table($table)->truncate();
            $this->info('Cleared: ' . $table);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        if ($this->option('remove-local-uploads')) {
            $this->clearLocalUploads();
        }

        $this->info('Fresh admin content reset complete. Admin users and site settings were not removed.');
        return 0;
    }

    private function clearLocalUploads()
    {
        $uploadPath = public_path('upload');

        if (!File::isDirectory($uploadPath)) {
            $this->line('No public/upload directory found.');
            return;
        }

        foreach (File::directories($uploadPath) as $directory) {
            File::deleteDirectory($directory);
        }

        foreach (File::files($uploadPath) as $file) {
            File::delete($file->getPathname());
        }

        $this->info('Cleared old local files inside public/upload.');
    }
}
