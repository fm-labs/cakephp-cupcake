<?php
use Cake\Core\Configure;
use Cake\Routing\Router;


if (Configure::read('Banana.Router.enableRootScope')) {

    Router::scope('/', function($routes) {

        $routes->connect('/', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'index']);

        // Pages
        $routes->connect('/:slug/:page_id/*',
            ['plugin' => 'Banana',  'controller' => 'Pages', 'action' => 'view'],
            ['page_id' => '^[0-9]+', 'pass' => ['page_id'], '_name' => 'page']
        );

        $routes->connect('/:slug',
            ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => []]
        );

        $routes->connect('/*',
            ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => ['page_id'], 0 => '^[0-9]+']
        );

        // Posts
        $routes->connect('/post/:slug/:post_id/*',
            ['plugin' => 'Banana',  'controller' => 'Posts', 'action' => 'view'],
            ['post_id' => '^[0-9]+', 'pass' => ['post_id'], ['_name' => 'post']]
        );

        $routes->connect('/post/:slug',
            ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
            ['pass' => [], ['_name' => 'postslug']]
        );

        $routes->connect('/post/*',
            ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
            ['pass' => ['post_id'], 0 => '^[0-9]+']
        );

    });
}

// Frontend routes

Router::scope('/content', ['plugin' => 'Banana', '_namePrefix' => 'content:', ], function ($routes) {

    if (!Configure::read('Banana.Router.disableFrontendRoutes') && Configure::read('Banana.Router.enablePrettyUrls')) {

        $routes->connect('/page/:slug/:page_id/*',
            ['plugin' => 'Banana',  'controller' => 'Pages', 'action' => 'view'],
            ['pass' => ['page_id'], '_name' => 'page']
        );

        $routes->connect('/page/:slug',
            ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => []]
        );

        $routes->connect('/page/*',
            ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => ['page_id'], 0 => '^[0-9]+']
        );

        // Posts
        $routes->connect('/post/:slug/:post_id/*',
            ['plugin' => 'Banana',  'controller' => 'Posts', 'action' => 'view'],
            ['pass' => ['post_id'], ['_name' => 'post']]
        );

        $routes->connect('/post/:slug',
            ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
            ['pass' => []]
        );

        $routes->connect('/post/*',
            ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
            ['pass' => ['post_id'], 0 => '^[0-9]+']
        );

    }

    $routes->fallbacks('DashedRoute');

});



// Admin routes
if (!Configure::read('Banana.Router.disableAdminRoutes')) {
    Router::scope(
        '/content/admin',
        ['plugin' => 'Banana', '_namePrefix' => 'content:admin:', 'prefix' => 'admin'], function ($routes) {

        $routes->extensions(['json']);

        //$routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });

}

// Banana SEO: robots.txt
//Router::connect('/robots.txt', ['plugin' => 'Banana', 'controller' => 'Seo', 'action' => 'robots']);

// Banana SEO: sitemap.xml
//Router::connect('/sitemap.posts.xml', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'sitemap']);
//Router::connect('/sitemap.pages.xml', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'sitemap']);

