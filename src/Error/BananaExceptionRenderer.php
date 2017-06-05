<?php

namespace Banana\Error;

use Cake\Error\ExceptionRenderer;

/**
 * Class BananaExceptionRenderer
 * @package Banana\Error
 */
class BananaExceptionRenderer extends ExceptionRenderer
{
    /*
    protected function _getController($exception)
    {
        return new ErrorController();
    }
    */

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
