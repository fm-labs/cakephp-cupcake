<?php

namespace Banana\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Class FrontendController
 * @package Banana\Controller
 * @deprecated Use FrontendComponent instead
 */
abstract class FrontendController extends Controller
{
    //public $viewClass = 'Banana.Frontend';

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Banana.Frontend');
    }
} 