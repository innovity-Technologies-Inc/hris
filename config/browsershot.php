<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Node Binary Path
    |--------------------------------------------------------------------------
    |
    | The path to the node binary. If not set, it will try to find it in PATH
    |
    */
    'node_binary' => env('BROWSERSHOT_NODE_BINARY', 'node'),

    /*
    |--------------------------------------------------------------------------
    | NPM Binary Path
    |--------------------------------------------------------------------------
    |
    | The path to the npm binary. If not set, it will try to find it in PATH
    |
    */
    'npm_binary' => env('BROWSERSHOT_NPM_BINARY', 'npm'),

    /*
    |--------------------------------------------------------------------------
    | Node Modules Path
    |--------------------------------------------------------------------------
    |
    | The path to node_modules directory containing Puppeteer
    |
    */
    'node_modules_path' => env('BROWSERSHOT_NODE_MODULES_PATH', base_path('node_modules')),

    /*
    |--------------------------------------------------------------------------
    | Chrome Binary Path
    |--------------------------------------------------------------------------
    |
    | The path to Chrome/Chromium binary. Leave null to auto-detect.
    | Windows: Usually auto-detected from Program Files
    | Linux: /usr/bin/google-chrome or /usr/bin/chromium-browser
    |
    */
    'chrome_path' => env('BROWSERSHOT_CHROME_PATH', null),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum time (in seconds) to wait for the PDF generation
    |
    */
    'timeout' => env('BROWSERSHOT_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | PDF Options
    |--------------------------------------------------------------------------
    |
    | Default options for PDF generation
    |
    */
    'pdf' => [
        'format' => 'A4',
        'orientation' => 'portrait',
        'margin_top' => 0,
        'margin_right' => 0,
        'margin_bottom' => 0,
        'margin_left' => 0,
        'print_background' => true,
        'prefer_css_page_size' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Screenshot Options
    |--------------------------------------------------------------------------
    |
    | Default options for screenshots
    |
    */
    'screenshot' => [
        'type' => 'png', // png, jpeg
        'quality' => 100,
        'full_page' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Chrome Arguments
    |--------------------------------------------------------------------------
    |
    | Additional arguments to pass to Chrome/Chromium
    |
    */
    'chrome_arguments' => [
        '--disable-gpu',
        '--no-sandbox',
        '--disable-dev-shm-usage',
        '--disable-setuid-sandbox',
    ],
];
