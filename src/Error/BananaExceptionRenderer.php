<?php

namespace Banana\Error;

use Banana\Controller\ErrorController;
use Cake\Error\ExceptionRenderer;

class BananaExceptionRenderer extends ExceptionRenderer
{
    /*
    protected function _getController($exception)
    {
        return new ErrorController();
    }
    */

    public function missingWidget($error)
    {
        return 'Oops that widget is missing!';
    }

    public function missingPlugin($error)
    {
        return 'Oops that widget is missing!';
    }
}