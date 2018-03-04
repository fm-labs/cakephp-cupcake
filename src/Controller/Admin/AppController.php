<?php

namespace Banana\Controller\Admin;

use Cake\Controller\Controller;

/**
 * Class AppController
 * @package Banana\Controller\Admin
 */
class AppController extends Controller
{
    /**
     * Intitialize
     */
    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
    }
}
