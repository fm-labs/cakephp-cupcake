<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 27.11.15
 * Time: 14:28
 */

namespace Banana\View\Cell;


use Cake\ORM\TableRegistry;

class PostsViewModuleCell extends ModuleCell
{
    public static $defaultParams = [
        'post_id' => null,
        'show_teaser' => false,
    ];

    public static function inputs()
    {
        $Posts = TableRegistry::get('Banana.Posts');
        $posts = $Posts->find('list');

        return [
            'post_id' => ['type' => 'select', 'options' => $posts],
            'show_teaser' => ['type' => 'checkbox']
        ];
    }

    public static function defaults()
    {
        return static::$defaultParams;
    }

    public function display($module = null)
    {
        $params = array_merge(static::$defaultParams, $module->params_arr);
        $this->set('params', $params);
        $this->set('module', $module);

        $postId = $params['post_id'];
        if ($postId) {
            $Posts = TableRegistry::get('Banana.Posts');
            $post = $Posts->find()
                ->where(['id' => $postId])
                //->contain(['ContentModules' => ['Modules']])
                ->contain([])
                ->first();
            $this->set('post', $post);
        }
    }
}