<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecycleBinTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('recycle_bin')) {
            return;
        }

        Schema::create('recycle_bin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_type', 50)->comment('video, product, banner, offerbanner');
            $table->unsignedBigInteger('item_id')->comment('original table primary key');
            $table->longText('item_data')->comment('JSON snapshot of the deleted row');
            $table->string('file_path', 500)->nullable()->comment('relative file path for restoration');
            $table->string('original_name', 255)->nullable()->comment('human readable name for display');
            $table->string('deleted_by', 100)->nullable()->comment('admin username who deleted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recycle_bin');
    }
}
