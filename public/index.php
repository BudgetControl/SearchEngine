<?php


require_once __DIR__ . "/../bootstrap/app.php";

$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0] : $_SERVER['REMOTE_ADDR'] ?? '';
$ipAllowed = explode(',', env('IP_ALLOWLIST'));
\Illuminate\Support\Facades\Log::debug('IP Address: ' . $ip);

if (!in_array($ip, $ipAllowed) || $_SERVER['HTTP_X_API_SECRET'] !== env('SECRET_KEY')) {
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
