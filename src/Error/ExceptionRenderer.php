<?php

namespace Banana\Error;

use Banana\Controller\ErrorController;
use Cake\Error\ExceptionRenderer as CakeExceptionRenderer;

/**
 * Class BananaExceptionRenderer
 *
 * @package Banana\Error
 */
class ExceptionRenderer extends CakeExceptionRenderer
{
    /**
     * @return ErrorController
     */
    protected function _getController()
    {
        return new ErrorController();
    }

    /**
     * @param $error
     * @return string
     */
    public function missingWidget($error)
    {
        return 'Oops that widget is missing!';
    }

    /**
     * @param $error
     * @return string
     */
    public function missingPlugin($error)
    {
        return 'Oops that widget is missing!';
    }
}
