<?php
namespace Banana\View\Module\Posts;

use Banana\View\ViewModule;
use Banana\Model\Table\PostsTable;
use Cake\Form\Schema;
use Cake\ORM\Entity;

/**
 * Class ListPostsModule
 * @package Banana\View\Module\Posts
 *
 * @property PostsTable $Posts
 */
class ViewPostModule  extends ViewModule
{
    protected $subDir = "Posts/";

    protected $params = [
        'postId' => null,
        //'post' => null,
    ];

    public function display($params = [])
    {
        $this->setParams($params);

        if (isset($this->params['post'])) {
            $post = $this->params['post'];
        } else {
            $this->loadModel('Banana.Posts');
            $post = $this->Posts->get($this->params['postId']);
        }

        $this->set('post', $post);
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'postId' => [
                'type' => 'number'
            ],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'postId' => []
        ];
    }
}
