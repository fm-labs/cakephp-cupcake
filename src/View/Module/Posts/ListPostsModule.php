<?php
namespace Banana\View\Module\Posts;

use Banana\View\ViewModule;
use Banana\Model\Table\PostsTable;
use Cake\Form\Schema;

/**
 * Class ListPostsModule
 * @package Banana\View\Module\Posts
 *
 * @property PostsTable $Posts
 */
class ListPostsModule  extends ViewModule
{
    protected $subDir = "Posts/";

    protected $params = [
        'limit' => 10
    ];

    public function display($params = [])
    {
        $this->loadModel('Banana.Posts');

        $posts = $this->Posts->find()
            ->contain(['ContentModules' => ['Modules']])
            ->order(['Posts.id' => 'DESC'])
            //->limit($this->params['limit']) // @TODO check limit boundaries min/max
            ->all();

        $this->set('posts', $posts);
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'limit' => [
                'type' => 'number'
            ],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'limit' => []
        ];
    }
}
