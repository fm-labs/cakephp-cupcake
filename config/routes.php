<?php
use Cake\Core\Configure;
use Cake\Routing\Router;


if (Configure::read('Banana.Router.enableRootScope')) {

    Router::scope('/', function($routes) {

        $routes->connect('/', ['plugin' => null, 'controller' => 'Pages', 'action' => 'index']);

        // Pages
        $routes->connect('/:slug/:page_id/*',
            ['plugin' => null,  'controller' => 'Pages', 'action' => 'view'],
            ['pass' => ['page_id'], '_name' => 'page']
        );

        $routes->connect('/:slug',
            ['plugin' => null, 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => []]
        );

        $routes->connect('/*',
            ['plugin' => null, 'controller' => 'Pages', 'action' => 'view'],
            ['pass' => ['page_id'], 0 => '^[0-9]+']
        );


        // Posts
        $routes->connect('/post/:slug/:post_id/*',
            ['plugin' => 'Banana',  'controller' => 'Posts', 'action' => 'view'],
            ['pass' => ['post_id'], ['_name' => 'post']]
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
if (!Configure::read('Banana.Router.disableFrontendRoutes')) {
    Router::scope('/content', ['plugin' => 'Banana', '_namePrefix' => 'content:', ], function ($routes) {

        if (Configure::read('Banana.Router.enablePrettyUrls')) {

            // do not create named page route here, if already defined in root scope
            $name = Configure::read('Banana.Router.enableRootScope') ? null : 'page';

            $routes->connect('/page/:slug/:page_id/*',
                ['plugin' => null,  'controller' => 'Pages', 'action' => 'view'],
                ['pass' => ['page_id'], '_name' => $name]
            );

            $routes->connect('/page/:slug',
                ['plugin' => null, 'controller' => 'Pages', 'action' => 'view'],
                ['pass' => []]
            );

            $routes->connect('/page/*',
                ['plugin' => null, 'controller' => 'Pages', 'action' => 'view'],
                ['pass' => ['page_id'], 0 => '^[0-9]+']
            );

            // Posts
            // do not create named page route here, if already defined in root scope
            $name = Configure::read('Banana.Router.enableRootScope') ? null : 'post';

            $routes->connect('/post/:slug/:post_id/*',
                ['plugin' => 'Banana',  'controller' => 'Posts', 'action' => 'view'],
                ['pass' => ['post_id'], ['_name' => 'post']]
            );

            $routes->connect('/post/:slug',
                ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
                ['pass' => [], ['_name' => 'postslug']]
            );

            $routes->connect('/post/*',
                ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'],
                ['pass' => ['post_id'], 0 => '^[0-9]+']
            );

        } else {
            // fallback named routes
            //$routes->connect('/pages/view/*', ['plugin' => null, 'controller' => 'Pages', 'action' => 'view'], ['_name' => 'page']);
            //$routes->connect('/posts/view/*', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'], ['_name' => 'post']);
        }


        //$routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');

    });

}


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

