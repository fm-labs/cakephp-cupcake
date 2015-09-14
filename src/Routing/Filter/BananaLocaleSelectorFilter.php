<?php
namespace Banana\Routing\Filter;

use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Routing\Filter\LocaleSelectorFilter;

/**
 * Class BananaLocaleSelectorFilter
 *
 * Extends CakePHP's built-in LocaleSelectorFilter,
 * by also checking the session for a valid locale setting.
 *
 * @package Banana\Routing\Filter
 */
class BananaLocaleSelectorFilter extends LocaleSelectorFilter
{
    public static $sessionKey = 'Banana.locale';

    public function beforeDispatch(Event $event)
    {
        parent::beforeDispatch($event);

        $request = $event->data['request'];
        if ($request->session()->check(static::$sessionKey)) {
            $locale = $request->session()->read(static::$sessionKey);

            if (!$locale || (!empty($this->_locales) && !in_array($locale, $this->_locales))) {
                return;
            }

            I18n::locale($locale);
        }

        /*
        if (!isset($request->params['locale'])) {
            debug("add locale to request");
            $request->addParams(['locale' => I18n::locale()]);
        }
        */
    }
}