<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Cake\Filesystem\Folder;
use Settings\Model\Table\SettingsTable;
use Settings\Model\Entity\Setting;
use Settings\Configure\Engine\SettingsConfig;


/**
 * Settings Controller
 *
 * @property \Banana\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
{
    public $modelClass = 'Settings.Settings';


    protected function _listCompiledSettingsFiles()
    {
        $folder = new Folder(SETTINGS);
        $files = $folder->findRecursive('.*\.schema\.json');
        return $files;
    }

    protected function _listCompiledSettings($assoc = true)
    {
        $files = $this->_listCompiledSettingsFiles();
        $compiled = [];

        // read schema json into array
        $sSchemaReader = function ($schemaFile) use ($assoc) {
            $content = file_get_contents($schemaFile);
            return json_decode($content, $assoc);
        };

        // walk all compiled settings files and read schema
        array_walk($files, function ($val) use (&$compiled, $sSchemaReader) {
            $key = basename($val, '.schema.json');
            $compiled[$key] = $sSchemaReader($val);
        });

        return $compiled;
    }

    protected function _getTypes()
    {
        return array_flip(Setting::typeMap());
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('dbSettingsKeys', $this->Settings->listByKeys());
        $this->set('compiledSettings', $this->_listCompiledSettings(false));
        $this->set('_serialize', ['settings']);
    }

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        $this->set('setting', $setting);
        $this->set('_serialize', ['setting']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $setting = $this->Settings->newEntity();
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The {0} has been saved.', __('setting')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('setting')));
            }
        } else {
            if ($this->request->query('key')) {
                $setting = $this->Settings->newEntity([
                    'ref' => $this->request->query('ref'),
                    'key' => $this->request->query('key'),
                    'type' => $this->request->query('type')
                ]);
            } else {
                $setting = $this->Settings->newEntity([
                    'ref' => $this->request->query('ref'),
                    'scope' => $this->request->query('scope'),
                    'name' => $this->request->query('name'),
                    'type' => $this->request->query('type')
                ]);
            }
        }
        $this->set(compact('setting'));
        $this->set('types', $this->_getTypes());
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
                $this->Flash->success(__('The {0} has been saved.', __('setting')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('types', $this->_getTypes());
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
            $this->Flash->success(__('The {0} has been deleted.', __('setting')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('setting')));
        }
        return $this->redirect(['action' => 'index']);
    }

}
