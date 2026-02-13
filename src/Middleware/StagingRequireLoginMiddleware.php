<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StagingRequireLoginMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (PHP_SAPI === 'cli') {
            return $handler->handle($request);
        }

        $config = (array)Configure::read('StagingAuth');
        $enabled = (bool)($config['enabled'] ?? false);
        if (!$enabled) {
            return $handler->handle($request);
        }

        $path = $request->getUri()->getPath() ?: '/';

        // Always allow access to the Users plugin routes so login/logout/reset work.
        if (str_starts_with($path, '/users')) {
            return $handler->handle($request);
        }

        $session = $request->getSession();
        $userId = $session->read('Auth.id');
        $username = $session->read('Auth.username');
        $role = (string)$session->read('Auth.role');

        $isLoggedIn = !empty($userId) || !empty($username);
        if (!$isLoggedIn) {
            return $this->redirectToLogin($request, $config);
        }

        $allowedRoles = $config['allowedRoles'] ?? [];
        if (is_string($allowedRoles)) {
            $allowedRoles = array_values(array_filter(array_map('trim', explode(',', $allowedRoles))));
        }
        if (is_array($allowedRoles) && $allowedRoles !== []) {
            if (!in_array($role, $allowedRoles, true)) {
                $response = new Response();
                $response = $response->withStatus(403);
                $response->getBody()->write('Forbidden');

                return $response;
            }
        }

        return $handler->handle($request);
    }

    private function redirectToLogin(ServerRequestInterface $request, array $config): ResponseInterface
    {
        $loginPath = (string)($config['loginPath'] ?? '/users/login');
        $redirectParam = (string)($config['redirectQueryParam'] ?? 'redirect');

        $path = $request->getUri()->getPath() ?: '/';
        $query = $request->getUri()->getQuery();
        $current = $path . ($query !== '' ? ('?' . $query) : '');

        $separator = str_contains($loginPath, '?') ? '&' : '?';
        $location = $loginPath . $separator . rawurlencode($redirectParam) . '=' . rawurlencode($current);

        $response = new Response();

        return $response
            ->withStatus(302)
            ->withHeader('Location', $location);
    }
}
