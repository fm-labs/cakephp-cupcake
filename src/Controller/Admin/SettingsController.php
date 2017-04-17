<?php
namespace Banana\Controller\Admin;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Network\Exception\BadRequestException;
use Settings\Model\Table\SettingsTable;
use Settings\Configure\Engine\SettingsConfig;


/**
 * Settings Controller
 *
 * @property SettingsTable $Settings
 */
class SettingsController extends AppController
{
    public $modelClass = 'Banana.Settings';


    /**
     * Index method
     *
     * @return void
     */
    public function index($scope = null)
    {

        $query = $this->Settings->find()
            ->order(['Settings.scope' => 'ASC', 'Settings.key' => 'ASC']);

        if ($scope) {
            $query->where(['scope' => $scope]);
        }

        $this->set('settings', $query->all());
        $this->set('_serialize', ['settings']);
    }

    public function reset($id)
    {
        $setting = $this->Settings->get($id);
        $setting->value = $setting->default;


        if ($this->Settings->save($setting)) {
            $this->Flash->success(__('Saved'));
        } else {
            $this->Flash->error(__('Failed'));
        }
        $this->redirect(['action' => 'index']);
    }

    public function dump($scope = 'global')
    {
        if (!$scope) {
            throw new BadRequestException();
        }

        $compiled = $this->Settings->getCompiled();
        $config = new SettingsConfig();

        if ($written = $config->dump($scope, $compiled)) {
            $this->Flash->success(__('Dump settings {0}: {1} bytes written', $scope, $written));
        } else {
            $this->Flash->error(__('Dump settings {0}: Failed', $scope));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','setting')));
                return $this->redirect(['action' => 'edit', $setting->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('valueTypes', $this->Settings->listValueTypes());
        $this->set('_serialize', ['setting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                //$this->Settings->dump();
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','setting')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('valueTypes', $this->Settings->listValueTypes());
        $this->set('_serialize', ['setting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__d('banana','The {0} has been deleted.', __d('banana','setting')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be deleted. Please, try again.', __d('banana','setting')));
        }
        return $this->redirect(['action' => 'index']);
    }

}
