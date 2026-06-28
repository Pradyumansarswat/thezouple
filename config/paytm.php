<?php

return [
    'env' => env('PAYTM_ENVIRONMENT', 'local'), // values : (local | production)
    'merchant_id' => env('PAYTM_MERCHANT_ID', ''),
    'merchant_key' => env('PAYTM_MERCHANT_KEY', ''),
    'merchant_website' => env('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'),
    'channel' => env('PAYTM_CHANNEL', 'WEB'),
    'industry_type' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),
];
