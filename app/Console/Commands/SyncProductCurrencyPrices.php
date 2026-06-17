<?php

namespace App\Console\Commands;

use App\Helper\CurrencyHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncProductCurrencyPrices extends Command
{
    protected $signature = 'prices:sync';

    protected $description = 'Recalculate dollar and euro prices from INR for all products';

    public function handle()
    {
        $rows = DB::table('product_quantity')->get();

        $updated = 0;

        foreach ($rows as $row) {
            if ((float) $row->rupee_price <= 0) {
                continue;
            }

            $gst = DB::table('products')
                ->where('product_id', $row->product_id)
                ->value('product_gst');

            $prices = CurrencyHelper::pricesFromRupee(
                $row->rupee_price,
                $row->product_discount,
                $gst ?: 0
            );

            DB::table('product_quantity')
                ->where('product_quantity_id', $row->product_quantity_id)
                ->update($prices);

            $updated++;
        }

        $febricRows = DB::table('febric')->get();
        $febricUpdated = 0;

        foreach ($febricRows as $row) {
            $update = [];

            if ((float) $row->rupee_price > 0) {
                $update['dollar_price'] = CurrencyHelper::toDollar($row->rupee_price);
                $update['euro_price'] = CurrencyHelper::toEuro($row->rupee_price);
            }

            if ((float) $row->rupee_dark_price > 0) {
                $update['dollar_dark_price'] = CurrencyHelper::toDollar($row->rupee_dark_price);
                $update['euro_dark_price'] = CurrencyHelper::toEuro($row->rupee_dark_price);
            }

            if (!empty($update)) {
                DB::table('febric')->where('febric_id', $row->febric_id)->update($update);
                $febricUpdated++;
            }
        }

        $this->info("Updated {$updated} product price rows and {$febricUpdated} fabric rows.");

        return 0;
    }
}
