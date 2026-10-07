<?php

use Illuminate\Http\Request;

// Vercel only provides writable temporary storage at runtime.
if (getenv('VERCEL')) {
    $runtimeStorage = sys_get_temp_dir().'/event-planner';
    foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs'] as $directory) {
        $path = $runtimeStorage.'/'.$directory;
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }
    putenv('LARAVEL_STORAGE_PATH='.$runtimeStorage);
    putenv('VIEW_COMPILED_PATH='.$runtimeStorage.'/framework/views');
    putenv('LOG_CHANNEL=stderr');
    foreach (['APP_ENV' => 'production', 'APP_DEBUG' => 'false', 'SESSION_DRIVER' => 'cookie', 'SESSION_SECURE_COOKIE' => 'true', 'CACHE_STORE' => 'array', 'QUEUE_CONNECTION' => 'sync'] as $name => $value) {
        if (getenv($name) === false) {
            putenv($name.'='.$value);
        }
    }
    if (getenv('APP_URL') === false && getenv('VERCEL_PROJECT_PRODUCTION_URL')) {
        putenv('APP_URL=https://'.getenv('VERCEL_PROJECT_PRODUCTION_URL'));
    }
}

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->usePublicPath(dirname(__DIR__).'/public');
if (getenv('VERCEL')) {
    $app->useStoragePath($runtimeStorage);
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
}
$app->handleRequest(Request::capture());


