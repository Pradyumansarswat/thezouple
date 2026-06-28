<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloudinaryMediaColumns extends Migration
{
    public function up()
    {
        $this->addColumn('products', 'product_header_image_public_id');
        $this->addColumn('product_gallery_images', 'image_url');
        $this->addColumn('product_gallery_images', 'public_id');
        $this->addColumn('categorys', 'image_public_id');
        $this->addColumn('banner', 'image_public_id');
        $this->addColumn('flash_banner', 'image_public_id');
        $this->addColumn('login_banner', 'image_public_id');
        $this->addColumn('customer_shirt', 'image_public_id');
        $this->addColumn('sliders', 'image_public_id');
        $this->addColumn('offerbanners', 'image_public_id');
        $this->addColumn('about', 'image_public_id');
        $this->addColumn('blog', 'image_public_id');
        $this->addColumn('blog', 'front_image_public_id');
        $this->addColumn('testimonial', 'image_public_id');
        $this->addColumn('other_page', 'image_public_id');
        $this->addColumn('video', 'video_public_id');
        $this->addColumn('review', 'user_profile_public_id');
        $this->addColumn('review', 'review_product_image_public_ids', 'text');
    }

    public function down()
    {
        $this->dropColumn('review', 'review_product_image_public_ids');
        $this->dropColumn('review', 'user_profile_public_id');
        $this->dropColumn('video', 'video_public_id');
        $this->dropColumn('other_page', 'image_public_id');
        $this->dropColumn('testimonial', 'image_public_id');
        $this->dropColumn('blog', 'front_image_public_id');
        $this->dropColumn('blog', 'image_public_id');
        $this->dropColumn('about', 'image_public_id');
        $this->dropColumn('offerbanners', 'image_public_id');
        $this->dropColumn('sliders', 'image_public_id');
        $this->dropColumn('customer_shirt', 'image_public_id');
        $this->dropColumn('login_banner', 'image_public_id');
        $this->dropColumn('flash_banner', 'image_public_id');
        $this->dropColumn('banner', 'image_public_id');
        $this->dropColumn('categorys', 'image_public_id');
        $this->dropColumn('product_gallery_images', 'public_id');
        $this->dropColumn('product_gallery_images', 'image_url');
        $this->dropColumn('products', 'product_header_image_public_id');
    }

    private function addColumn($table, $column, $type = 'string')
    {
        if (!Schema::hasTable($table) || Schema::hasColumn($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($column, $type) {
            if ($type === 'text') {
                $tableBlueprint->text($column)->nullable();
                return;
            }

            $tableBlueprint->string($column, 500)->nullable();
        });
    }

    private function dropColumn($table, $column)
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($column) {
            $tableBlueprint->dropColumn($column);
        });
    }
}
