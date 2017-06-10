<?php

namespace Banana\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Controller;

/**
 * Class AppController
 * @package Banana\Controller\Admin
 */
class AppController extends Controller
{
    use BackendActionsTrait;

    /**
     * Intitialize
     */
    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
    }
}