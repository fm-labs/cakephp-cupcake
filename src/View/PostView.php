<?php

namespace Banana\View;

use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Utility\Text;

class PostView extends ContentView
{

    public function render($view = null, $layout = null)
    {
        if ($this->get('post')) {
            $this->loadHelper('Media.Media');

            $post = $this->get('post');

            $metaTitle = ($post->meta_title) ?: $post->title;
            $postUrl = $this->Html->Url->build($post->url, true);

            // post title
            $this->assign('title', $metaTitle);

            // canonical url
            $this->Html->meta(['link' => $postUrl, 'rel' => 'canonical'], null, ['block' => true]);

            // meta tags
            $metaLang = ($post->meta_lang) ?: I18n::locale();
            $this->Html->meta(['name' => 'language', 'content' => $metaLang], null, ['block' => true]);

            $metaRobots = 'index,follow';
            $this->Html->meta(['name' => 'robots', 'content' => $metaRobots], null, ['block' => true]);

            $metaDescription = ($post->meta_desc) ?: $post->title;
            $this->Html->meta(['name' => 'description', 'content' => $metaDescription, 'lang' => $metaLang], null, ['block' => true]);

            $metaKeywords = ($post->meta_keywords) ?: $post->title;
            $this->Html->meta(['name' => 'keywords', 'content' => $metaKeywords, 'lang' => $metaLang], null, ['block' => true]);

            //$this->Html->meta(['name' => 'revisit-after', 'content' => '7 days'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'expires', 'content' => 0], null, ['block' => true]);
            //$this->Html->meta(['name' => 'abstract', 'content' => $metaDescription], null, ['block' => true]);
            //$this->Html->meta(['name' => 'distribution', 'content' => 'global'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'generator', 'content' => 'Banana Cake x.x.x'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'googlebot', 'content' => ''], null, ['block' => true]);
            //$this->Html->meta(['name' => 'no-email-collection', 'content' => 'http://www.metatags.nl/nospamharvesting'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'rating', 'content' => 'general'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'reply-to', 'content' => 'webmaster@exmaple.org'], null, ['block' => true]);

            //$this->Html->meta(['http-equiv' => 'cache-control', 'content' => 'public'], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'content-type', 'content' => 'text/html'], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'content-language', 'content' => $metaLang], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'pragma', 'content' => 'no-cache'], null, ['block' => true]);


            // Open Graph Tags
            $this->Html->meta(['property' => 'og:title', 'content' => $metaTitle], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:description', 'content' => $metaDescription], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:url', 'content' => $postUrl], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:type', 'content' => 'article'], null, ['block' => true]);

            $publishedTime = ($post->publish_start) ?: $post->created;
            if ($publishedTime) {
                $this->Html->meta(['property' => 'article:published_time', 'content' => $publishedTime->format(DATE_ISO8601)], null, ['block' => true]);
            }

            $expirationTime = ($post->publish_end);
            if ($expirationTime) {
                $this->Html->meta(['property' => 'article:expiration_time', 'content' => $expirationTime->format(DATE_ISO8601)], null, ['block' => true]);
            }

            $modifiedTime = $post->modified;
            if ($modifiedTime) {
                $this->Html->meta(['property' => 'article:modified_time', 'content' => $modifiedTime->format(DATE_ISO8601)], null, ['block' => true]);
            }

            if ($post->image) {
                $thumb = $this->Media->thumbnailUrl($post->image->filepath, ['width' => 200, 'height' => 200], true);
                $this->Html->meta(['property' => 'og:image', 'content' => $thumb], null, ['block' => true]);
                $this->Html->meta(['property' => 'twitter:image', 'content' => $thumb], null, ['block' => true]);
            }

            // Twitter Tags (https://dev.twitter.com/cards/markup)
            $this->Html->meta(['property' => 'twitter:card', 'content' => 'summary'], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:title', 'content' => $metaTitle], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:description', 'content' => Text::truncate($metaDescription, 197, ['ellipsis' => '...', 'exact' => false])], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:url', 'content' => $postUrl], null, ['block' => true]);

        }

        return parent::render($view, $layout);
    }
}
