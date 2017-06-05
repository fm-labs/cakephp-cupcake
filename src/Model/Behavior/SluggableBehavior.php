<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

/**
 * Class SluggableBehavior
 *
 * @package Banana\Model\Behavior
 * @see http://book.cakephp.org/3.0/en/orm/behaviors.html
 */
class SluggableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'field' => 'title', // the field to create the slug from
        'slug' => 'slug', // the slug field name
        'replacement' => '-', // the replacement string for non-standard characters
        'caseSensitive' => false, // case sensitive or lower case
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
    }

    /**
     * Create an URL-friendly slug string
     *
     * @param Entity $entity Entity to create a slug for
     * @return void
     */
    public function slug(Entity $entity)
    {
        $config = $this->config();
        $slug = $entity->get($config['slug']);

        if ($slug && !$entity->dirty($config['slug'])) {
            // no action for existing slug
            return;
        } elseif (!$slug && $config['field']) {
            // create slug from field, if any
            $slug = $entity->get($config['field']);
        }
        // basic sanitation
        $slug = strip_tags($slug);
        $slug = trim($slug);

        // case sensitive or lower case
        if ($config['caseSensitive'] !== true) {
            $slug = strtolower($slug);
        }

        $entity->set($config['slug'], Inflector::slug($slug, $config['replacement']));
    }

    /**
     * Automatically slug when saving.
     *
     * @param Event $event The event
     * @param Entity $entity The entity
     * @param \ArrayObject $options
     * @param $operation
     * @return void
     */
    public function beforeRules(Event $event, Entity $entity, ArrayObject $options, $operation)
    {
        $this->slug($entity);
    }

    /**
     * Automatically slug when saving.
     *
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        $this->slug($entity);
    }
}
