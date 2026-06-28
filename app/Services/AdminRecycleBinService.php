<?php

namespace App\Services;

use Auth;
use DB;
use File;
use Illuminate\Support\Facades\Schema;

class AdminRecycleBinService
{
    public static function definitions()
    {
        return [
            'products' => ['table' => 'products', 'key' => 'product_id', 'label' => 'Products', 'name' => ['product_title', 'product_sku'], 'files' => ['product_header_image' => 'upload/product', 'product_images' => 'upload/product']],
            'categories' => ['table' => 'categorys', 'key' => 'category_id', 'label' => 'Categories', 'name' => ['title'], 'files' => ['image' => 'upload/category']],
            'orders' => ['table' => 'order_system', 'key' => 'order_id', 'label' => 'Orders', 'name' => ['order_number'], 'files' => []],
            'customers' => ['table' => 'users', 'key' => 'id', 'label' => 'Customers', 'name' => ['name', 'email'], 'files' => []],
            'sliders' => ['table' => 'sliders', 'key' => 'slider_id', 'label' => 'Banners', 'name' => ['name', 'heading'], 'files' => ['image' => 'upload/slider']],
            'offerbanners' => ['table' => 'offerbanners', 'key' => 'offerbanners_id', 'label' => 'Banners', 'name' => ['banner_name', 'description'], 'files' => ['image' => 'upload/offerbanner']],
            'banner' => ['table' => 'banner', 'key' => 'banner_id', 'label' => 'Banners', 'name' => ['title', 'banner_name'], 'files' => ['image' => 'upload/banner']],
            'videos' => ['table' => 'video', 'key' => 'video_id', 'label' => 'Media', 'name' => ['title', 'video'], 'files' => ['video' => 'upload/video']],
            'blogs' => ['table' => 'blog', 'key' => 'blog_id', 'label' => 'Blogs', 'name' => ['heading'], 'files' => ['image' => 'upload/blog', 'front_image' => 'upload/blog']],
            'enquiries' => ['table' => 'contact', 'key' => 'contact_id', 'label' => 'Enquiries', 'name' => ['name', 'email', 'subject'], 'files' => []],
            'reviews' => ['table' => 'review', 'key' => 'review_id', 'label' => 'Reviews', 'name' => ['name', 'description'], 'files' => ['user_profile' => 'upload/review', 'review_product_image' => 'upload/review']],
            'testimonials' => ['table' => 'testimonial', 'key' => 'testimonial_id', 'label' => 'Media', 'name' => ['name', 'heading'], 'files' => ['image' => 'upload/testimonial']],
            'about' => ['table' => 'about', 'key' => 'about_id', 'label' => 'Content', 'name' => ['heading', 'title'], 'files' => ['image' => 'upload/about']],
            'vendors' => ['table' => 'vendors', 'key' => 'vendor_id', 'label' => 'Content', 'name' => ['vendor_name'], 'files' => []],
            'cms' => ['table' => 'cms', 'key' => 'cms_id', 'label' => 'Content', 'name' => ['title'], 'files' => []],
            'subscribers' => ['table' => 'subscribe', 'key' => 'subscribe_id', 'label' => 'Enquiries', 'name' => ['email'], 'files' => []],
            'notifications' => ['table' => 'getnotification', 'key' => 'notifi_id', 'label' => 'Enquiries', 'name' => ['user_email', 'message'], 'files' => []],
        ];
    }

    public static function definition($type)
    {
        $definitions = self::definitions();
        return isset($definitions[$type]) ? $definitions[$type] : null;
    }

    public static function activeTable($table)
    {
        return self::withoutDeleted(DB::table($table), $table);
    }

    public static function withoutDeleted($query, $tableOrAlias, $actualTable = null)
    {
        $actualTable = $actualTable ?: $tableOrAlias;

        if (Schema::hasTable($actualTable) && Schema::hasColumn($actualTable, 'deleted_at')) {
            $query->whereNull($tableOrAlias . '.deleted_at');
        }

        return $query;
    }

    public static function softDelete($type, $id)
    {
        $def = self::definition($type);
        if (!$def || !Schema::hasTable($def['table'])) {
            return false;
        }

        $row = DB::table($def['table'])->where($def['key'], $id)->first();
        if (!$row) {
            return false;
        }

        if (Schema::hasColumn($def['table'], 'deleted_at')) {
            $update = ['deleted_at' => now()];
            if (Schema::hasColumn($def['table'], 'deleted_by')) {
                $update['deleted_by'] = Auth::id();
            }
            DB::table($def['table'])->where($def['key'], $id)->update($update);
        } else {
            self::snapshotLegacy($type, $def, $row);
            DB::table($def['table'])->where($def['key'], $id)->delete();
        }

        self::log('soft_delete', $type, $id, self::displayName($row, $def));
        return true;
    }

    public static function restore($type, $id)
    {
        $def = self::definition($type);
        if (!$def || !Schema::hasTable($def['table']) || !Schema::hasColumn($def['table'], 'deleted_at')) {
            return false;
        }

        DB::table($def['table'])->where($def['key'], $id)->update(['deleted_at' => null, 'deleted_by' => null]);
        self::log('restore', $type, $id, null);
        return true;
    }

    public static function forceDelete($type, $id)
    {
        $def = self::definition($type);
        if (!$def || !Schema::hasTable($def['table'])) {
            return false;
        }

        $row = DB::table($def['table'])->where($def['key'], $id)->first();
        if ($row) {
            self::deleteFiles($row, $def);
            DB::table($def['table'])->where($def['key'], $id)->delete();
        }

        self::log('force_delete', $type, $id, null);
        return true;
    }

    public static function deletedItems($search = null, $type = null)
    {
        $items = collect([]);
        foreach (self::definitions() as $key => $def) {
            if ($type && $type !== $key) {
                continue;
            }
            if (!Schema::hasTable($def['table']) || !Schema::hasColumn($def['table'], 'deleted_at')) {
                continue;
            }

            $rows = DB::table($def['table'])->whereNotNull($def['table'] . '.deleted_at')->get();
            foreach ($rows as $row) {
                $name = self::displayName($row, $def);
                if ($search && stripos($name, $search) === false && stripos($key, $search) === false) {
                    continue;
                }
                $items->push((object) [
                    'type' => $key,
                    'type_label' => $def['label'],
                    'id' => $row->{$def['key']},
                    'name' => $name,
                    'deleted_at' => $row->deleted_at,
                    'deleted_by' => isset($row->deleted_by) ? self::deletedByName($row->deleted_by) : null,
                ]);
            }
        }

        return $items->sortByDesc('deleted_at')->values();
    }

    public static function count()
    {
        return self::deletedItems()->count();
    }

    public static function cleanup($days)
    {
        $cutoff = now()->subDays((int) $days);
        $count = 0;
        foreach (self::definitions() as $key => $def) {
            if (!Schema::hasTable($def['table']) || !Schema::hasColumn($def['table'], 'deleted_at')) {
                continue;
            }
            $rows = DB::table($def['table'])->whereNotNull('deleted_at')->where('deleted_at', '<', $cutoff)->get();
            foreach ($rows as $row) {
                if (self::forceDelete($key, $row->{$def['key']})) {
                    $count++;
                }
            }
        }
        return $count;
    }

    private static function displayName($row, $def)
    {
        foreach ($def['name'] as $column) {
            if (isset($row->{$column}) && trim(strip_tags($row->{$column})) !== '') {
                return trim(strip_tags($row->{$column}));
            }
        }
        return $def['table'] . ' #' . $row->{$def['key']};
    }

    private static function deleteFiles($row, $def)
    {
        foreach ($def['files'] as $column => $folder) {
            if (!isset($row->{$column}) || !$row->{$column}) {
                continue;
            }

            $files = json_decode($row->{$column}, true);
            if (!is_array($files)) {
                $files = [$row->{$column}];
            }

            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }
                File::delete(public_path(trim($folder, '/') . '/' . $file));
            }
        }
    }

    private static function deletedByName($adminId)
    {
        if (!$adminId || !Schema::hasTable('users')) {
            return null;
        }

        $admin = DB::table('users')->where('id', $adminId)->first();
        if (!$admin) {
            return '#' . $adminId;
        }

        return $admin->name ?: ($admin->email ?: '#' . $adminId);
    }

    private static function snapshotLegacy($type, $def, $row)
    {
        if (!Schema::hasTable('recycle_bin')) {
            return;
        }

        $data = [
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('recycle_bin', 'item_type')) {
            $data += [
                'item_type' => $type,
                'item_id' => $row->{$def['key']},
                'item_data' => json_encode((array) $row),
                'original_name' => self::displayName($row, $def),
                'deleted_by' => Auth::user() ? Auth::user()->name : null,
            ];
        } else {
            $data += [
                'table_name' => $def['table'],
                'record_id' => $row->{$def['key']},
                'record_name' => self::displayName($row, $def),
                'data' => json_encode((array) $row),
            ];
        }

        DB::table('recycle_bin')->insert($data);
    }

    private static function log($action, $type, $id, $description)
    {
        if (!Schema::hasTable('admin_activity_logs')) {
            return;
        }

        DB::table('admin_activity_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => $action,
            'item_type' => $type,
            'item_id' => $id,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
