<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZoupleEnhancements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 'amazon_link')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('amazon_link', 500)->nullable()->after('meta_description');
            });
        }

        if (!Schema::hasTable('recycle_bin')) {
            Schema::create('recycle_bin', function (Blueprint $table) {
                $table->increments('id');
                $table->string('table_name', 100);
                $table->integer('record_id');
                $table->string('record_name', 255)->nullable();
                $table->longText('data');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('amazon_link');
        });
        Schema::dropIfExists('recycle_bin');
    }
}
