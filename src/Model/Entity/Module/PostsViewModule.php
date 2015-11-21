<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/20/15
 * Time: 5:46 PM
 */

namespace Banana\Model\Entity\Module;


use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class PostsViewModule extends BaseModule
{
    protected $_defaultParams = [
        'post_id' => null
    ];

    protected function _getViewPath()
    {
        return 'Banana.Modules/Posts/View';
    }

    protected function _getFormData()
    {
        $posts = TableRegistry::get('Banana.Posts')->find('list')->toArray();

        return [
            'module' => $this,
            'posts' => $posts
        ];
    }

    protected function _getViewData()
    {
        $post = (isset($this->params_arr['post_id']))
            ? TableRegistry::get('Banana.Posts')->get($this->params_arr['post_id'])
            : null;

        return [
            'module' => $this,
            'params' => $this->params_arr,
            'post' => $post
        ];
    }

    public function processForm(Entity $entity, array $formData)
    {
        // TODO: Implement processForm() method.
    }
}