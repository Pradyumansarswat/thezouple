<?php
return [ 
    'client_id' => env('PAYPAL_CLIENT_ID','AXmdhNhFa1Kr1pwZO4yvSBRHj748moevYBhCydCE75ZDmWfti3HCbZgU4Y_BgiAfajEIMdCese15wxXL'),
    'secret' => env('PAYPAL_SECRET','EMdfV21PSdVFXLObeWJqANBaPLVpdTWcFllEDpOm_IPCJ79R5p5_IZNLpl4MMP0vSSBxvPw95FhoD15R'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','Live'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];