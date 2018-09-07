<?php

namespace Banana\Mailer;

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Mailer\Email;
use Cake\Utility\Hash;

/**
 * LocalizedEmail class
 *
 * Supports translation definition in the email profile config
 *
 * If the config contains `_localized` key, an array of translations is expected
 * [
 *  'subject' => 'Hello!'
 *  '_localized' => ['de' => ['subject' => 'Hallo', 'layout' => 'default_de', ... ]]
 * ]
 */
class LocalizedEmail extends Email
{
    /**
     * Original locale
     *
     * @var string
     */
    protected $_originalLocale;

    /**
     * Current email locale
     *
     * @var string
     */
    protected $_locale;

    /**
     * Translation map
     *
     * @var array
     */
    protected $_translations = [];

    /**
     * Constructor
     *
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->_originalLocale = I18n::locale();
        parent::__construct($config);
    }

    /**
     * Get/Set the current email locale
     *
     * @return null|string|$this
     */
    public function locale($locale = null)
    {
        if ($locale === null) {
            return $this->_locale;
        }

        $this->_locale = $locale;
        $this->_applyLocalizedConfig($this->_locale);

        return $this;
    }

    /**
     * Overloading parent profile() method
     */
    public function profile($config = null)
    {
        return parent::profile($config);
    }

    public function send($content = null)
    {
        $subject = $this->getOriginalSubject();
        $this->set('_subject', $subject);

        return parent::send($content);
    }

    /**
     * Reset locale on email reset
     *
     * @return void;
     */
    public function reset()
    {
        parent::reset();
        $this->_locale = $this->_originalLocale;
        $this->_translations = [];
    }

    /**
     * Overloading parent _applyConfig() method
     * to parse translations config
     *
     * @return void
     */
    protected function _applyConfig($config)
    {
        parent::_applyConfig($config);

        if (is_array($config) && array_key_exists('_localized', $config)) {
            $this->_translations = Hash::merge($this->_translations, $config['_localized']);
        }

        if (is_array($config) && array_key_exists('locale', $config)) {
            $this->locale($config['locale']);
        }
    }

    /**
     * Apply localized profile
     *
     * @return void
     */
    protected function _applyLocalizedConfig($locale)
    {
        if ($locale !== null && !empty($this->_translations) && array_key_exists($locale, $this->_translations)) {
            $config = $this->_translations[$locale];

            // make sure there are no nested _localized/locale definitions,
            // which could potentially lead to an infinite loop
            if (isset($config['_localized'])) {
                unset($config['_localized']);
            }
            if (isset($config['locale'])) {
                unset($config['locale']);
            }

            //parent::_applyConfig($config);
            $this->_applyConfig($config);
        }
    }
}
