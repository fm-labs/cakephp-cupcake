<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use BadMethodCallException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\View\Helper;

/**
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @method $this setApplicationName(string $value)
 * @method $this setRobots(string $value)
 * @method $this setGooglebot(string $value)
 * @method $this setFormatDetection(string $value)
 * @method $this setThemeColor(string $value)
 * @method $this setXDnsPrefetchControl(string $value)
 * @method $this setReferrer(string $value)
 * @method $this setRating(string $value)
 * @method $this setAbstract(string $value)
 * @method $this setTopic(string $value)
 * @method $this setSummary(string $value)
 * @method $this setAuthor(string $value)
 * @method $this setDesigner(string $value)
 * @method $this setCopyright(string $value)
 * @method $this setReplyTo(string $value)
 * @method $this setOwner(string $value)
 * @method $this setUrl(string $value)
 * @method $this setIdentifierUrl(string $value)
 * @method $this setDirectory(string $value)
 * @method $this setCategory(string $value)
 * @method $this setCoverage(string $value)
 * @method $this setDistribution(string $value)
 * @method $this setRevisitAfter(string $value)
 *
 * @method $this setLinkAlternate(string $value)
 * @method $this setLinkIcon(string $value)
 * @method $this setLinkFluidIcon(string $value)
 * @method $this setLinkMe(string $value)
 * @method $this setLinkShort(string $value)
 * @method $this setLinkArchives(string $value)
 * @method $this setLinkIndex(string $value)
 * @method $this setLinkStart(string $value)
 * @method $this setLinkPrev(string $value)
 * @method $this setLinkNext(string $value)
 * @method $this setLinkSearch(string $value)
 * @method $this setLinkSelf(string $value)
 * @method $this setLinkFirst(string $value)
 * @method $this setLinkPrevious(string $value)
 * @method $this setLinkLast(string $value)
 * @method $this setLinkCanonical(string $value)
 * @method $this setLinkEditUri(string $value)
 * @method $this setLinkPingback(string $value)
 * @method $this setLinkAppleTouchIcon(string $value)
 * @method $this setLinkAppleTouchStartupImage(string $value)
 * @method $this setLink(string $value)
 * @link https://gist.github.com/lancejpollard/1978404 List of html meta tags
 */
class MetaHelper extends Helper
{
    public array $helpers = ['Html'];

    protected array $_data = [];

    protected array $_meta = [
        // these 2 meta tags should come first!
        'charset' => ['charset' => 'utf8'],
        'viewport' => 'width=device-width, initial-scale=1',

        //'content-security-policy' => "default-src 'self'",
        //'robots' => 'index,follow',
        //'googlebot' => 'index,follow',
        //'generator' => 'FooBar/0.0',
        //'rating' => 'General',
        //'referrer' => 'no-referrer',
        //'format-detection' => 'telephone=no',
        //'x-dns-prefetch-control' => ['http-equiv' => 'x-dns-prefetch-control', 'content' => 'off'],
        //'application-name' => null,
        //'theme-color' => null,

        //'description' => 'My Description',
        //'subject' => 'MySubject',
        //'keywords' => 'Keyword1, Keyword2',

        /*
        'language' => [
            'name' => 'language',
            'content' => 'en'
        ],
        'content-security-policy' => [
            'http-equiv' => 'Content-Security-Policy',
            'content' => null,
        ],
        'x-test' => [
            'name' => 'x-test',
            'content' => 'FooBaz',
            'lang' => 'en'
        ],
        */
    ];

    protected $_link = [];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setCharset(Configure::read('App.encoding'));
        $this->setViewport('width=device-width, initial-scale=1');
        $this->setLanguage(I18n::getLocale());
        //$this->setRobots("index,follow");
        //$this->setApplicationName("Test App");
        //$this->setLinkCanonical("https://www.example.org/canonical");
    }

    public function set($name, $meta = null)
    {
        // myName => setMyName($value)
        $_name = Inflector::camelize(Text::slug($name, '_'));
        $_method = 'set' . $_name;
        if (method_exists($this, $_method)) {
            return call_user_func([$this, $_method], $meta);
        }

        if (substr($name, 0, 4) == 'link') {
            $this->_setLink(substr($name, 4), $meta);
        } else {
            $this->_setMeta($name, $meta);
        }

        return $this;
    }

    public function __call($method, $params)
    {
        if (preg_match('/^setLink([\w]+)$/', $method, $matches)) {
            $name = Text::slug(Inflector::underscore($matches[1]));
            $content = $params[0] ?? null;

            return $this->_setLink($name, $content);
        } elseif (preg_match('/^set([\w]+)$/', $method, $matches)) {
            $name = Text::slug(Inflector::underscore($matches[1]));
            $content = $params[0] ?? null;

            return $this->_setMeta($name, $content);
        }

        throw new BadMethodCallException();
    }

    protected function _setMeta($name, $meta)
    {
        $this->_meta[$name] = $meta;

        return $this;
    }

    protected function _setLink($name, $url)
    {
        $link = ['rel' => $name, 'link' => $url];
        $this->_link[$name] = $link;

        return $this;
    }

    public function setCharset($content)
    {
        return $this->_setMeta('charset', ['charset' => $content]);
    }

    public function setViewport($value)
    {
        return $this->_setMeta('viewport', $value);
    }

    public function setLanguage($value)
    {
        return $this->_setMeta('language', [
            'name' => 'language',
            'content' => $value,
        ]);
    }

    public function setDescription($value, $lang = null)
    {
        return $this->_setMeta('description', [
            'name' => 'description',
            'content' => $value,
            'lang' => $lang ?: I18n::getLocale(),
        ]);
    }

    public function setKeywords($value, $lang = null)
    {
        return $this->_setMeta('keywords', [
            'name' => 'keywords',
            'content' => $value,
            'lang' => $lang ?: I18n::getLocale(),
        ]);
    }

    public function setContentSecurityPolicy($value)
    {
        return $this->_setMeta('content-security-policy', [
            'http-equiv' => 'Content-Security-Policy',
            'content' => $value,
        ]);
    }

    public function injectTags(): void
    {
        foreach ($this->_meta as $name => $meta) {
            if (is_string($meta)) {
                $meta = ['name' => $name, 'content' => $meta];
            }
            $this->Html->meta($meta, null, ['block' => true]);
        }

        foreach ($this->_link as $name => $link) {
            $this->Html->meta($link, null, ['block' => true]);
        }
    }

    public function beforeLayout(Event $event): void
    {
        $this->injectTags();
    }
}
