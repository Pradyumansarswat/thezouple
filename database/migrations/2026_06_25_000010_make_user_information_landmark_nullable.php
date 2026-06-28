<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class MakeUserInformationLandmarkNullable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('user_information') && Schema::hasColumn('user_information', 'landmark')) {
            DB::statement("ALTER TABLE `user_information` MODIFY `landmark` varchar(255) NULL");
        }
    }

    public function down()
    {
        if (Schema::hasTable('user_information') && Schema::hasColumn('user_information', 'landmark')) {
            DB::table('user_information')->whereNull('landmark')->update(['landmark' => '']);
            DB::statement("ALTER TABLE `user_information` MODIFY `landmark` varchar(255) NOT NULL DEFAULT ''");
        }
    }
}
