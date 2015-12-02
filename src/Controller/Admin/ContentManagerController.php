<?php

namespace Banana\Controller\Admin;

use Cake\Network\Exception\NotFoundException;

class ContentManagerController extends AppController
{
    public $tabs = [
        'pages' => [
            'title' => 'Pages',
            'url' => ['controller' => 'PagesManager', 'action' => 'index']
        ],
        'posts' => [
            'title' => 'Posts',
            'url' => ['controller' => 'PagesManager', 'action' => 'index']
        ]
    ];

    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentPages'],
            'order' => ['Pages.lft ASC']
        ];

        $this->loadModel('Banana.Pages');
        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $this->set('contents', $this->paginate($this->Pages));
        $this->set('_serialize', ['contents']);

        //$this->set('tabs', $this->tabs);

    }

    public function tab($id = null)
    {
        if (!isset($this->tabs[$id])) {
            throw new NotFoundException();
        }
        $this->redirect($this->tabs[$id]['url']);
    }
}