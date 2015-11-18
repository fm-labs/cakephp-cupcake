<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/18/15
 * Time: 9:22 PM
 */

namespace Banana\Model\Entity\Module;

class TextHtmlModule extends BaseModule
{
    protected $_defaultConfig = [
        'textHtml' => '<h1>Put your HTML here</h1>'
    ];

    public function getFormElement()
    {
        return 'Banana.Modules/Text/htmlForm';
    }

    public function getFormElementData()
    {
        $this->set($this->config());
        return [
            'module' => $this
        ];
    }

    public function getFormElementOptions()
    {
        return [];
    }

    public function getViewElement()
    {
        return 'Banana.Modules/Text/html';
    }

    public function getViewElementData()
    {
        $this->set($this->config());
        return [
            'module' => $this
        ];
    }

    public function getViewElementOptions()
    {

    }

    public function processFormSubmission($entity, $data)
    {

    }

    protected function _getRealParams()
    {

    }

    protected function _setRealParams($value = null)
    {

    }
}