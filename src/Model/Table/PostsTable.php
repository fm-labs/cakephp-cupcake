<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\Post;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 */
class PostsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('bc_posts');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Banana.Sluggable');
        $this->addBehavior('Banana.ContentModule', [
            'alias' => 'ContentModules',
            'scope' => 'Banana.Posts',
            'concat' => 'body_html'
        ]);
        $this->addBehavior('Attachment.Attachment', [
            'dataDir' => WWW_ROOT . 'attachments' . DS . 'posts' . DS,
            'dataUrl' => '/attachments/posts/',
            'fields' => [
                'image_file' => ['uploadConfig' => 'posts_images']
            ]
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
            ->allowEmpty('slug');
            
        $validator
            ->allowEmpty('subheading');
            
        $validator
            ->allowEmpty('teaser');
            
        $validator
            ->allowEmpty('body_html');
            
        $validator
            ->allowEmpty('image_file');
            
        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_published', 'create')
            ->notEmpty('is_published');
            
        $validator
            ->add('publish_start_datetime', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publish_start_datetime');
            
        $validator
            ->add('publish_end_datetime', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publish_end_datetime');

        return $validator;
    }
}
