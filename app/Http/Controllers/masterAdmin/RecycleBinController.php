<?php

namespace App\Http\Controllers\masterAdmin;

use App\Http\Controllers\Controller;
use App\Services\AdminRecycleBinService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Redirect;

class RecycleBinController extends Controller
{
    public function index(Request $request)
    {
        $items = AdminRecycleBinService::deletedItems($request->get('search'), $request->get('type'));
        $types = AdminRecycleBinService::definitions();
        $cleanupDays = Schema::hasTable('siteinfos') && Schema::hasColumn('siteinfos', 'recycle_cleanup_days')
            ? (DB::table('siteinfos')->where('siteinfo_id', 1)->value('recycle_cleanup_days') ?: 90)
            : 90;
        $page_title = 'Recycle Bin - Zouple';

        return view('masters.recycle_bin.index', compact('items', 'types', 'cleanupDays', 'page_title'));
    }

    public function restore(Request $request, $type, $id)
    {
        if (AdminRecycleBinService::restore($type, $id)) {
            $request->session()->flash('alert-success', 'Item restored successfully.');
        } else {
            $request->session()->flash('alert-danger', 'Unable to restore this item.');
        }

        return Redirect::route('recycleBin');
    }

    public function permanentDelete(Request $request, $type, $id)
    {
        if (AdminRecycleBinService::forceDelete($type, $id)) {
            $request->session()->flash('alert-success', 'Item permanently deleted.');
        } else {
            $request->session()->flash('alert-danger', 'Unable to permanently delete this item.');
        }

        return Redirect::route('recycleBin');
    }

    public function bulk(Request $request)
    {
        $this->validate($request, [
            'action' => 'required|in:restore,delete',
            'items' => 'required|array',
        ]);

        $count = 0;
        foreach ($request->items as $item) {
            $parts = explode(':', $item, 2);
            if (count($parts) !== 2) {
                continue;
            }

            if ($request->action === 'restore' && AdminRecycleBinService::restore($parts[0], $parts[1])) {
                $count++;
            }
            if ($request->action === 'delete' && AdminRecycleBinService::forceDelete($parts[0], $parts[1])) {
                $count++;
            }
        }

        $request->session()->flash('alert-success', $count . ' item(s) processed successfully.');
        return Redirect::route('recycleBin');
    }

    public function cleanupPage(Request $request)
    {
        $request->session()->flash('alert-info', 'Recycle Bin cleanup is a protected action. Review deleted items below, then use the cleanup button with confirmation.');

        return Redirect::route('recycleBin');
    }

    public function cleanup(Request $request)
    {
        $configuredDays = Schema::hasTable('siteinfos') && Schema::hasColumn('siteinfos', 'recycle_cleanup_days')
            ? DB::table('siteinfos')->where('siteinfo_id', 1)->value('recycle_cleanup_days')
            : 90;
        $days = (int) ($request->get('days') ?: $configuredDays ?: 90);
        $count = AdminRecycleBinService::cleanup($days);

        $request->session()->flash('alert-success', $count . ' old item(s) permanently deleted.');
        return Redirect::route('recycleBin');
    }
}
