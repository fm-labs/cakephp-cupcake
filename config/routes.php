<?php
use Cake\Routing\Router;

// Banana and Banana admin routes
Router::plugin('Banana',['path' => '/cms'], function ($routes) {
    $routes->prefix('admin', function ($routes) {
        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });

    //$routes->connect('/:controller/sitemap.xml', ['action' => 'sitemap']);
    $routes->fallbacks('DashedRoute');
});

/*
// Banana Pages
Router::connect('/pages/:pageid/:slug',
    ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
    ['pass' => ['pageid']]
);
Router::connect('/pages/:pageid',
    ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'],
    ['pass' => ['pageid'], 'pageid' => '[0-9]+']
);
Router::connect('/pages/:slug',
    ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view']);
Router::connect('/pages/*',
    ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'display']);
*/

// Banana Posts
//Router::connect('/posts', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'index']);
//Router::connect('/posts/:slug/:id', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'], ['pass' => ['id']]);
//Router::connect('/posts/:slug', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view']);

// Banana SEO: robots.txt
//Router::connect('/robots.txt', ['plugin' => 'Banana', 'controller' => 'Seo', 'action' => 'robots']);

// Banana SEO: sitemap.xml
//Router::connect('/sitemap.posts.xml', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'sitemap']);
//Router::connect('/sitemap.pages.xml', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'sitemap']);

