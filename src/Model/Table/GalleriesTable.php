<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\Gallery;
use Cake\Core\Plugin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Media\Lib\Media\MediaManager;

/**
 * Galleries Model
 *
 */
class GalleriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('bc_galleries');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Parent', [
            'className' => 'Banana.Galleries',
            'foreignKey' => 'parent_id',
        ]);

        $this->hasMany('Posts', [
            'className' => 'Banana.Posts',
            'foreignKey' => 'refid',
            'conditions' => ['refscope' => 'Banana.Galleries']
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('desc_html');

        return $validator;
    }

    /**
     * Get list of available gallery sources
     */
    public function getSources()
    {
        return [
            'folder' => __('Folder'),
            'posts' => __('Posts')
        ];
    }

    /**
     * Get a recursive directory list of available gallery source folders
     */
    public function getSourceFolders()
    {
        if (Plugin::loaded('Media')) {
            $mm = MediaManager::get('default');
            return $mm->open('gallery/')->getSelectFolderListRecursive();
        }
        return null;
    }


    public function jsTreeGetNodes()
    {
        $nodes = $this->find('threaded')
            ->orderAsc('Galleries.title')
            ->all()
            ->toArray();

        //debug($nodes);

        $id = 1;
        $nodeFormatter = function(Gallery $node) use (&$id) {

            $class = "content published";

            return [
                'id' => $id++,
                'text' => $node->title,
                'icon' => $class,
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                ],
                'children' => [],
                'li_attr' => ['class' => $class],
                'a_attr' => [],
                'data' => [
                    'type' => 'content',
                    'viewUrl' => Router::url(['controller' => 'Galleries', 'action' => 'manage', $node->id]),
                ]
            ];
        };

        $nodesFormatter = function($nodes) use ($nodeFormatter, &$nodesFormatter) {
            $formatted = [];
            foreach ($nodes as $node) {
                $_node = $nodeFormatter($node);
                if ($node->children) {
                    $_node['children'] = $nodesFormatter($node->children);
                }
                $formatted[] = $_node;
            }
            return $formatted;
        };

        $nodesFormatted = $nodesFormatter($nodes);
        return $nodesFormatted;
    }
}
