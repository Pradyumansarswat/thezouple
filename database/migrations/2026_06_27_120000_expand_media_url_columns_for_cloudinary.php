<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpandMediaUrlColumnsForCloudinary extends Migration
{
    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $this->modifyText('products', 'product_header_image', true);
        $this->modifyText('products', 'product_images', true);
        $this->modifyText('product_gallery_images', 'image', false);
        $this->modifyText('product_gallery_images', 'image_url', true);
        $this->modifyText('testimonial', 'image', true);
        $this->modifyText('categorys', 'image', true);
        $this->modifyText('banner', 'image', true);
        $this->modifyText('flash_banner', 'image', true);
        $this->modifyText('login_banner', 'image', true);
        $this->modifyText('customer_shirt', 'image', true);
        $this->modifyText('sliders', 'image', true);
        $this->modifyText('offerbanners', 'image', true);
        $this->modifyText('about', 'image', true);
        $this->modifyText('blog', 'image', true);
        $this->modifyText('blog', 'front_image', true);
        $this->modifyText('other_page', 'image', true);
        $this->modifyText('video', 'video', true);
        $this->modifyText('review', 'user_profile', true);
        $this->modifyText('review', 'review_product_image', true);
    }

    public function down()
    {
        // Intentionally left empty. Shrinking media URL columns can truncate Cloudinary URLs.
    }

    private function modifyText($table, $column, $nullable)
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        DB::statement(sprintf(
            'ALTER TABLE `%s` MODIFY `%s` TEXT %s',
            str_replace('`', '``', $table),
            str_replace('`', '``', $column),
            $nullable ? 'NULL' : 'NOT NULL'
        ));
    }
}
