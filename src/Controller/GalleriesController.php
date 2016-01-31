<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/3/15
 * Time: 6:36 PM
 */

namespace Banana\Controller;


class GalleriesController extends FrontendController
{
    /**
     * View method
     *
     * @param string|null $id Gallery id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => ['Posts'],
            'media' => true,
        ]);
        $this->set('gallery', $gallery);
        $this->set('_serialize', ['gallery']);

        $view = ($gallery->view_template) ?: null;

        $this->render($view);
    }
}