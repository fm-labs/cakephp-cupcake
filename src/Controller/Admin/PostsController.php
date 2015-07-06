<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Cake\ORM\Table;

/**
 * Posts Controller
 *
 * @property \Banana\Model\Table\PostsTable $Posts
 */
class PostsController extends ContentController
{
    public $modelClass = 'Banana.Posts';

}
