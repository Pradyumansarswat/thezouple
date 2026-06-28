<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductGalleryImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_gallery_images')) {
            Schema::create('product_gallery_images', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('product_id')->index();
                $table->string('image', 500);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('products')) {
            $products = DB::table('products')
                ->select('product_id', 'product_images')
                ->whereNotNull('product_images')
                ->get();

            foreach ($products as $product) {
                if (DB::table('product_gallery_images')->where('product_id', $product->product_id)->exists()) {
                    continue;
                }

                $images = json_decode($product->product_images, true);
                if (!is_array($images)) {
                    continue;
                }

                $sortOrder = 0;
                foreach ($images as $image) {
                    $image = trim((string) $image);
                    if ($image === '') {
                        continue;
                    }

                    DB::table('product_gallery_images')->insert([
                        'product_id' => $product->product_id,
                        'image' => $image,
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_gallery_images');
    }
}
