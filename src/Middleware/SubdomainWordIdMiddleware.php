<?php
// src/Middleware/SubdomainWordIdMiddleware.php
namespace App\Middleware;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Cake\ORM\TableRegistry;
use Psr\Http\Server\RequestHandlerInterface;

class SubdomainWordIdMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Extract the subdomain from the host
        $host = $request->getUri()->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Extract the word ID from the URL
        $path = $request->getUri()->getPath();
        $segments = explode('/', trim($path, '/'));
        $wordId = end($segments) !== null && (int)end($segments) ? 
                                (int)end($segments) : null;
        //debug($wordId);
        // Check if the word ID falls within the desired range
        $range_array = ['gdfj' => [1729,1786,4], 'jrl' => [2,88,5], 
                        'ljl' => [1,310,7],'lojs' => [1,66,8]];

        if (in_array($subdomain, array_keys($range_array))) {
            if ($wordId !== null && $wordId >= $range_array[$subdomain][0] 
                    && $wordId <= $range_array[$subdomain][1]) {
                
                $wordsTable = TableRegistry::getTableLocator()->get('Words');
                $new_word_id = $wordsTable->get_new_word_id($range_array[$subdomain][2],$wordId);
                
                // Perform the redirect
                

                $response = new Response();
                return $response->withHeader('Location', '/words/' . $new_word_id['id'])->withStatus(302);
            }
        }

        // Continue to the next middleware/handler
        return $handler->handle($request);
    }
}
