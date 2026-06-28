<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductionReadinessUpdates extends Migration
{
    private $softDeleteTables = [
        'products',
        'categorys',
        'order_system',
        'users',
        'sliders',
        'offerbanners',
        'banner',
        'video',
        'blog',
        'contact',
        'review',
        'testimonial',
        'about',
        'vendors',
        'cms',
        'subscribe',
        'getnotification',
    ];

    public function up()
    {
        foreach ($this->softDeleteTables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                if (!Schema::hasColumn($table, 'deleted_at')) {
                    $tableBlueprint->timestamp('deleted_at')->nullable()->index();
                }
                if (!Schema::hasColumn($table, 'deleted_by')) {
                    $tableBlueprint->unsignedInteger('deleted_by')->nullable()->index();
                }
            });
        }

        if (Schema::hasTable('siteinfos')) {
            Schema::table('siteinfos', function (Blueprint $table) {
                if (!Schema::hasColumn('siteinfos', 'whatsapp_number')) {
                    $table->string('whatsapp_number', 30)->nullable()->after('phone_number');
                }
                if (!Schema::hasColumn('siteinfos', 'recycle_cleanup_days')) {
                    $table->unsignedSmallInteger('recycle_cleanup_days')->default(90)->after('minimum_charge');
                }
            });
        }

        if (Schema::hasTable('order_system')) {
            Schema::table('order_system', function (Blueprint $table) {
                if (!Schema::hasColumn('order_system', 'payment_method')) {
                    $table->string('payment_method', 40)->nullable()->after('payment_status');
                }
            });
        }

        if (!Schema::hasTable('admin_activity_logs')) {
            Schema::create('admin_activity_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('admin_id')->nullable();
                $table->string('action', 80);
                $table->string('item_type', 80)->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->string('description', 500)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('admin_activity_logs');

        if (Schema::hasTable('order_system') && Schema::hasColumn('order_system', 'payment_method')) {
            Schema::table('order_system', function (Blueprint $table) {
                $table->dropColumn('payment_method');
            });
        }

        if (Schema::hasTable('siteinfos')) {
            Schema::table('siteinfos', function (Blueprint $table) {
                if (Schema::hasColumn('siteinfos', 'whatsapp_number')) {
                    $table->dropColumn('whatsapp_number');
                }
                if (Schema::hasColumn('siteinfos', 'recycle_cleanup_days')) {
                    $table->dropColumn('recycle_cleanup_days');
                }
            });
        }

        foreach ($this->softDeleteTables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                if (Schema::hasColumn($table, 'deleted_by')) {
                    $tableBlueprint->dropColumn('deleted_by');
                }
                if (Schema::hasColumn($table, 'deleted_at')) {
                    $tableBlueprint->dropColumn('deleted_at');
                }
            });
        }
    }
}
