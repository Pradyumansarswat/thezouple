<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityStateToPincodesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pincodes')) {
            return;
        }

        Schema::table('pincodes', function (Blueprint $table) {
            if (!Schema::hasColumn('pincodes', 'city')) {
                $table->string('city')->nullable()->after('pincode');
            }

            if (!Schema::hasColumn('pincodes', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('pincodes')) {
            return;
        }

        Schema::table('pincodes', function (Blueprint $table) {
            if (Schema::hasColumn('pincodes', 'state')) {
                $table->dropColumn('state');
            }

            if (Schema::hasColumn('pincodes', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
}
