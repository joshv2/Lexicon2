<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LanguageMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $host = $request->getUri()->getHost(); 
        $subdomain = explode('.', $host)[0];

        $Languages = TableRegistry::getTableLocator()->get('Languages');

        $language = $Languages->get_language($subdomain);
        // Store for controllers/models
        $request = $request->withAttribute('sitelang', $language);

        return $handler->handle($request);
    }
}
