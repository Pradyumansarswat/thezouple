<?php

return [
  'default' => env('DEFAULT_CURRENCY', 'rupee_price'),

  // Convert INR base price into other currencies.
  'usd_rate' => (float) env('CURRENCY_USD_RATE', 30),
  'eur_rate' => (float) env('CURRENCY_EUR_RATE', 32),
];
