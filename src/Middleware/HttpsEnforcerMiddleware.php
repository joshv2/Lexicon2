<?php
// src/Middleware/SubdomainWordIdMiddleware.php
namespace App\Middleware;

use Cake\Http\Middleware\HttpsEnforcerMiddleware;

// Send a 302 status code when redirecting
$https = new HttpsEnforcerMiddleware([
    'redirect' => true,
    'statusCode' => 302,
]);

// Disable HTTPs enforcement when ``debug`` is on.
$https = new HttpsEnforcerMiddleware([
    'disableOnDebug' => true,
]);

$https = new HttpsEnforcerMiddleware([
    'hsts' => [
        // How long the header value should be cached for.
        'maxAge' => 60 * 60 * 24 * 365,
        // should this policy apply to subdomains?
        'includeSubDomains' => true,
        // Should the header value be cacheable in google's HSTS preload
        // service? While not part of the spec it is widely implemented.
        'preload' => true,
    ],
]);