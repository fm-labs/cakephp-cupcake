<?php
namespace Banana\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Form\Schema;
use Cake\Network\Exception\BadRequestException;
use Settings\Form\SettingsForm;
use Settings\Model\Table\SettingsTable;
use Settings\SettingsManager;

/**
 * Settings Controller
 *
 * @property SettingsTable $Settings
 */
class SettingsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = 'Settings.Settings';


    public $actions = [
        'index' => 'Backend.Index',
        'edit' => 'Backend.Edit',
        'view' => 'Backend.View',
    ];

    public function manage($scope = 'default')
    {
        $manager = new SettingsManager($scope);

        $settings = $manager->getSettings();
        $schema = $manager->buildFormSchema(new Schema());
        $inputs = $manager->buildFormInputs();


        $result = [];
        foreach ($settings as $namespace => $settings) {
            foreach (array_keys($settings) as $key) {
                $fieldKey = $namespace . '.' . $key;

                $result[$fieldKey] = [
                    'field' => $schema->field($fieldKey),
                    'input' => $inputs[$fieldKey]
                ];
            }
        }

        $this->set('manager', $manager);
        $this->set('result', $result);
    }

    public function index($scope = 'default')
    {
        $this->eventManager()->on('Backend.Action.Index.getActions', function(Event $event) use ($scope) {
            $event->result[] =  [__('Edit'), ['action' => 'form', $scope]];
            $event->result[] =  [__('Dump'), ['action' => 'dump', $scope]];
        });
        $this->Action->execute();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function form($scope = 'default')
    {

        $settingsForm = new SettingsForm(new SettingsManager($scope));

        if ($this->request->is(['put', 'post'])) {
            // apply
            $settingsForm->execute($this->request->data);

            // compile
            $compiled = $settingsForm->manager()->getCompiled();
            //Configure::write($compiled);

            // update
            if ($this->Settings->updateSettings($compiled, $scope)) {

                // dump
                $settingsForm->manager()->dump();

                $this->Flash->success('Settings updated');
                $this->redirect(['action' => 'index', $scope]);
            }
        }

        //$this->set('settings', $settings);
        $this->set('scope', $scope);
        $this->set('form', $settingsForm);
        $this->set('_serialize', ['settings']);
    }

    /**
     * @param string $scope
     */
    public function dump($scope = 'default')
    {
        if (!$scope) {
            throw new BadRequestException();
        }

        $manager = new SettingsManager($scope);
        if ($written = $manager->dump()) {
            $this->Flash->success(__('Settings for {0} dumped: {1} bytes written', $scope, $written));
        } else {
            $this->Flash->error(__('Failed to dump settings for {0}', $scope));
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
                $this->Flash->success(__d('banana', 'The {0} has been saved.', __d('banana', 'setting')));

                return $this->redirect(['action' => 'edit', $setting->id]);
            } else {
                $this->Flash->error(__d('banana', 'The {0} could not be saved. Please, try again.', __d('banana', 'setting')));
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
                $this->Flash->success(__d('banana', 'The {0} has been saved.', __d('banana', 'setting')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana', 'The {0} could not be saved. Please, try again.', __d('banana', 'setting')));
            }
        }
        $this->set(compact('setting'));
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
            $this->Flash->success(__d('banana', 'The {0} has been deleted.', __d('banana', 'setting')));
        } else {
            $this->Flash->error(__d('banana', 'The {0} could not be deleted. Please, try again.', __d('banana', 'setting')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
