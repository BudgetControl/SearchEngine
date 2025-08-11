<?php

use Illuminate\Support\Facades\Log;

require_once __DIR__ . "/../bootstrap/app.php";

if ($_SERVER['HTTP_X_API_SECRET'] !== env('SECRET_KEY') && env('APP_ENV') !== 'local') {
    http_response_code(403);
    echo 'Access Denied - You are not allowed to access this resource.';
    exit;
}

$app = \Slim\Factory\AppFactory::create();

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
require_once __DIR__ . "/../config/middleware.php";

require_once __DIR__ . "/../routes/api.php";

// Run app
$app->run();
