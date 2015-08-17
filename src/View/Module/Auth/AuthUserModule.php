<?php

namespace Banana\View\Module\Auth;

use Banana\View\ViewModule;
use Cake\Form\Schema;
use User\Controller\Component\AuthComponent;

/**
 * Class AUthUserModule
 *
 * @package Banana\View\Module\Auth
 */
class AuthUserModule extends ViewModule
{
    protected $subDir = "Auth/";

    protected $params = [
        'actionUrl' => null,
    ];

    public function display($params = [])
    {
        $this->setParams($params);
        $this->set('user', $this->request->session()->read('Auth.User'));
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'actionUrl' => []
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'actionUrl' => [],
        ];
    }

    public static  function templates()
    {
        return [
            'display',
            'dump' => ['desc' => 'Shows vardump of user variable']
        ];
    }

}
