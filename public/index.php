<?php

require_once __DIR__ . "/../bootstrap/app.php";

$ip = explode(',', $_SERVER['X-Forwarded-For'])[0] ?? $_SERVER['REMOTE_ADDR'];
$ipAllowed = explode(',', getenv('IP_ALLOWLIST') ?? '');
if (!in_array($ip, $ipAllowed) || $_SERVER['HTTP_X_API_SECRET'] !== getenv('SECRET_KEY')) {
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
