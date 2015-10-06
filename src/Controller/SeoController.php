<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 6/20/15
 * Time: 12:52 AM
 */

namespace Banana\Controller;

use Sitemap\Controller\Component\SitemapComponent;

/**
 * Class SeoController
 * @package Banana\Controller
 *
 * @property SitemapComponent $Sitemap
 */
class SeoController extends AppController
{
    public $components = ['Sitemap.Sitemap'];

    /**
     * Generates robots.txt in webroot
     */
    public function robots()
    {
        $robots = [
            'User-agent: *',
            'Disallow: /tmp/',
            'Disallow: /wp-admin/',
            'Disallow: /admin/',
            'Disallow: /adm/',
            'Disallow: /backend/',
            'Disallow: /private/',
            'Disallow: /login/',
            'Disallow: /user/',
            'Disallow: /usr/',
            'Disallow: /hate/',
            'Disallow: /racism/',
        ];
        $this->response->type('text/plain');
        $this->response->body(join("\n", $robots));
        return $this->response;
    }
}
