<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/20/16
 * Time: 2:12 PM
 */

namespace Banana\Lib;


use Cake\ORM\TableRegistry;

class Banana
{
    static protected $_instances = [];


    protected $_sites = [];
    protected $_siteIds = [];

    /**
     * @return Banana
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            self::$_instances[0] = new self();
        }
        return self::$_instances[0];
    }

    static public function init()
    {
        $_this = self::getInstance();
    }

    static public function siteId($host = null)
    {
        return self::getInstance()->getSiteId($host);
    }

    public function __construct()
    {
        $this->_loadSites();
    }

    protected function _loadSites()
    {
        //@TODO Caching
        $this->_sites = TableRegistry::get('Banana.Sites')->find()->all()->toArray();
    }

    public function getSiteId($host = null)
    {
        if (is_null($host)) {
            $host = (defined('BANANA_HOST')) ? constant('BANANA_HOST') : env('HTTP_HOST');
        }

        if (!array_key_exists($host, $this->_siteIds)) {

            $siteId = null;
            foreach($this->_sites as $site) {
                if ($host && $site['hostname'] && $site['hostname'] == $host) {
                    $siteId = $site['id'];
                    break;
                }
            }
            $this->_siteIds[$host] = $siteId;
        }

        return $this->_siteIds[$host];
    }



}