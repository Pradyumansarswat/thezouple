<?php

namespace App\Helper;

class CurrencyHelper
{
    public static function usdRate()
    {
        return (float) config('currency.usd_rate', 30);
    }

    public static function eurRate()
    {
        return (float) config('currency.eur_rate', 32);
    }

    public static function toDollar($rupeePrice)
    {
        $rupeePrice = (float) $rupeePrice;

        if ($rupeePrice <= 0) {
            return 0;
        }

        return max(1, (int) round($rupeePrice / self::usdRate()));
    }

    public static function toEuro($rupeePrice)
    {
        $rupeePrice = (float) $rupeePrice;

        if ($rupeePrice <= 0) {
            return 0;
        }

        return max(1, (int) round($rupeePrice / self::eurRate()));
    }

    public static function netAmount($price, $discount)
    {
        $price = (float) $price;
        $discount = (float) $discount;

        return $price - ($price * $discount / 100);
    }

    public static function netWithGst($netAmount, $gst)
    {
        $netAmount = (float) $netAmount;
        $gst = (float) $gst;

        return ($netAmount * $gst / 100) + $netAmount;
    }

    public static function pricesFromRupee($rupeePrice, $discount, $gst)
    {
        $rupeePrice = (float) $rupeePrice;
        $dollarPrice = self::toDollar($rupeePrice);
        $euroPrice = self::toEuro($rupeePrice);

        $rupeeNet = self::netAmount($rupeePrice, $discount);
        $dollarNet = self::netAmount($dollarPrice, $discount);
        $euroNet = self::netAmount($euroPrice, $discount);

        return [
            'rupee_price' => $rupeePrice,
            'dollar_price' => $dollarPrice,
            'euro_price' => $euroPrice,
            'rupee_net_amount' => $rupeeNet,
            'dollar_net_amount' => $dollarNet,
            'euro_net_amount' => $euroNet,
            'rupee_net_with_gst' => self::netWithGst($rupeeNet, $gst),
            'dollar_net_with_gst' => self::netWithGst($dollarNet, $gst),
            'euro_net_with_gst' => self::netWithGst($euroNet, $gst),
        ];
    }

    public static function normalizeSessionCurrency($currency)
    {
        if ($currency === 'doller_price') {
            return 'dollar_price';
        }

        $allowed = ['rupee_price', 'dollar_price', 'euro_price'];

        if (!in_array($currency, $allowed, true)) {
            return config('currency.default', 'rupee_price');
        }

        return $currency;
    }

    public static function isDollarCurrency($currency)
    {
        return in_array($currency, ['dollar_price', 'doller_price'], true);
    }
}
