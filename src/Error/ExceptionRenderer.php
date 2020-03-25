<?php
declare(strict_types=1);

namespace Banana\Error;

use Banana\Controller\ErrorController;
use Cake\Controller\Controller;
use Cake\Error\ExceptionRenderer as CakeExceptionRenderer;

/**
 * Class BananaExceptionRenderer
 *
 * @package Banana\Error
 */
class ExceptionRenderer extends CakeExceptionRenderer
{
    /**
     * @return \Banana\Controller\ErrorController
     */
    protected function _getController(): Controller
    {
        return new ErrorController();
    }

    /**
     * @param $error
     * @return string
     */
    public function missingWidget($error)
    {
        return 'Oops that widget is missing! ' . $error;
    }

    /**
     * @param $error
     * @return string
     */
    public function missingPlugin($error)
    {
        var_dump(debug_backtrace());

        return 'Oops that plugin is missing! ' . $error;
    }
}
