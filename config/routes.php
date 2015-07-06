<?php
use Cake\Routing\Router;

// Banana and Banana admin routes
Router::plugin('Banana', function ($routes) {
    $routes->prefix('admin', function ($routes) {
        $routes->connect('/:controller');
        $routes->fallbacks();
    });

    //$routes->connect('/:controller/sitemap.xml', ['action' => 'sitemap']);
    $routes->fallbacks();
});

// Banana Pages
Router::connect('/pages/:slug/:id', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view'], ['pass' => ['id']]);
Router::connect('/pages/:slug', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view']);

// Banana Posts
Router::connect('/posts', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'index']);
Router::connect('/posts/:slug/:id', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view'], ['pass' => ['id']]);
Router::connect('/posts/:slug', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view']);

// Banana SEO: robots.txt
Router::connect('/robots.txt', ['plugin' => 'Banana', 'controller' => 'Seo', 'action' => 'robots']);

// Banana SEO: sitemap.xml
//Router::connect('/sitemap.posts.xml', ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'sitemap']);
//Router::connect('/sitemap.pages.xml', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'sitemap']);

