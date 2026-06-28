<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmazonLinkToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'amazon_link')) {
                $table->string('amazon_link', 500)->nullable()->after('product_shipping');
            }
        });
    }

    public function down()
    {
        if (Schema::hasColumn('products', 'amazon_link')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('amazon_link');
            });
        }
    }
}
