<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/18/15
 * Time: 9:22 PM
 */

namespace Banana\Model\Entity\Module;

use Cake\ORM\Entity;

class TextHtmlModule extends BaseModule
{
    protected $_defaultParams = [
        'textHtml' => '<h1>Put your HTML here</h1>'
    ];

    protected function _getViewPath()
    {
        return 'Banana.Modules/Text/Html';
    }

    public function processForm(Entity $entity, array $formData)
    {
        // TODO: Implement processForm() method.
    }
}