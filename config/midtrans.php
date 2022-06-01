<?php

return [
    'mercant_id' => env('SB_MIDTRANS_MERCHAT_ID'),
    'client_key' => env('SB_MIDTRANS_CLIENT_KEY'),
    'server_key' => env('SB_MIDTRANS_SERVER_KEY'),

    'is_production' => env('SB_MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => false,
    'is_3ds' => false,
];
