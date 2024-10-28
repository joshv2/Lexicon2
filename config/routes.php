<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('Moderators', function (RouteBuilder $routes) {
        // All routes here will be prefixed with `/admin`, and
        // have the `'prefix' => 'Admin'` route element added that
        // will be required when generating URLs for these routes
        $routes->connect('/', ['controller' => 'Panel', 'action' => 'index']);
        $routes->connect('/logs', ['controller' => 'Panel', 'action' => 'logs']);
        $routes->connect('/me', ['controller' => 'Panel', 'action' => 'me']);
        $routes->fallbacks(DashedRoute::class);
    });
    $routes->scope('/', function (RouteBuilder $builder) {
    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'index']);
    //$builder->connect('/', ['controller' => 'Languages', 'action' => 'index']);
    $builder->connect('/add', ['controller' => 'Words', 'action' => 'add']);
    $builder->redirect('/welcome', ['controller' => 'Languages', 'action' => 'about']);
    //$builder->connect('/about', ['controller' => 'Pages', 'action' => 'display', 'about']);
    $builder->connect('/about', ['controller' => 'Languages', 'action' => 'about']);
    $builder->connect('/notes', ['controller' => 'Languages', 'action' => 'notes']);
    $builder->connect('/googleadspage', ['controller' => 'Pages', 'action' => 'googleadspage']);
    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    //$builder->connect('/login', ['controller' => 'Pages', 'action' => 'index', 'plugin' => 'false']);
    $builder->connect('/pages/*', 'Pages::display');
    $routes->connect('/.well-known/*', ['controller' => 'Pages', 'action' => 'display']);
    $builder->connect('/alphabetical', ['controller' => 'Words', 'action' => 'alphabetical', 'a']);
    $builder->connect('/alphabetical/{letter}', 
        ['controller' => 'Words', 'action' => 'alphabetical'],
        ['pass' => ['letter']]
    );
    
    Router::scope('/', function ($routes) {
        
    });

    $builder->connect('/words/{id}', 
        ['controller' => 'Words', 'action' => 'view'],
        ['id' => '[0-9]+', 'pass' => ['id']]);

    $builder->connect('/random', ['controller' => 'Words', 'action' => 'random']);


    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/:controller', ['action' => 'index']);
     * $builder->connect('/:controller/:action/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks();
    
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     
 *     // Parse specified extensions from URLs
 *     // $builder->setExtensions(['json', 'xml']);
 *     
 *     // Connect API actions here.
 * });
 * ```
 */
