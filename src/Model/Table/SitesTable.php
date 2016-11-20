<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\Site;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Content\Model\Entity\MenuItem;

/**
 * Sites Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentSites
 * @property \Cake\ORM\Association\HasMany $ChildSites
 */
class SitesTable extends Table
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

        $this->table('bc_sites');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('ParentSites', [
            'className' => 'Banana.Sites',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildSites', [
            'className' => 'Banana.Sites',
            'foreignKey' => 'parent_id'
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
            ->requirePresence('alias', 'create')
            ->notEmpty('alias')
            ->add('alias', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->allowEmpty('title');

        $validator
            ->allowEmpty('hostname');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['alias']));
        $rules->add($rules->existsIn(['parent_id'], 'ParentSites'));
        return $rules;
    }


    public function toJsTree($rootId = null)
    {

        $id = 1;
        $nodeFormatter = function(MenuItem $menuItem) use (&$id) {

            //$publishedClass = ($menuItem->isPublished()) ? 'published' : 'unpublished';
            $publishedClass = '';
            $class = $menuItem->type;
            $class.= " " . $publishedClass;

            return [
                'id' => $id++,
                'text' => $menuItem->getLabel(),
                'icon' => null, //$class,
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false,
                ],
                'children' => [],
                'li_attr' => ['class' => $class],
                'a_attr' => [],
                'data' => [
                    'type' => $menuItem->type,
                    'viewUrl' => Router::url($menuItem->getAdminUrl()),
                ]
            ];
        };

        $nodesFormatter = function($menuItems) use ($nodeFormatter, &$nodesFormatter) {
            $formatted = [];
            foreach ($menuItems as $menuItem) {
                $_node = $nodeFormatter($menuItem);
                if ($menuItem->getChildren()) {
                    $_node['children'] = $nodesFormatter($menuItem->getChildren());
                }
                $formatted[] = $_node;
            }
            return $formatted;
        };

        $sites = $this->find()->all();
        $nodes = [];
        foreach($sites as $site) {
            $_siteNodes = [];
            $menus = TableRegistry::get('Content.Menus')->find()->where(['site_id' => $site->id])->all();
            foreach ($menus as $menu) {
                $menuItems = TableRegistry::get('Content.MenuItems')->find()->where(['menu_id' => $menu->id, 'parent_id IS' => null])->all();

                $menuNode = [
                    'id' => 'menu_' . $menu->id,
                    'text' => $menu->title,
                    'icon' => null,
                    'state' => [
                        'opened' => true,
                        'disabled' => false,
                        'selected' => false,
                    ],
                    'children' => $nodesFormatter($menuItems),
                    'li_attr' => ['class' => ''],
                    'a_attr' => [],
                    'data' => [
                        'type' => 'menu',
                        'viewUrl' => Router::url(['controller' => 'Menus', 'action' => 'view', $menu->id])
                    ]
                ];
                $_siteNodes[] = $menuNode;
            }

            $siteNode = [
                'id' => 'site_' . $site->id,
                'text' => $site->alias,
                'icon' => null,
                'state' => [
                    'opened' => true,
                    'disabled' => false,
                    'selected' => false,
                ],
                'children' => $_siteNodes,
                'li_attr' => ['class' => ''],
                'a_attr' => [],
                'data' => [
                    'type' => 'site',
                    'viewUrl' => Router::url(['controller' => 'Sites', 'action' => 'view', $site->id])
                ]
            ];
            $nodes[] = $siteNode;
        }
        return $nodes;
    }
}
