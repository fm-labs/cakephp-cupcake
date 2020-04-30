<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;

/**
 * Class PublishBehavior
 * @package Cupcake\Model\Behavior
 */
class PublishBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'statusField' => 'is_published', // the field to store published flag
    ];

    /**
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findPublished(Query $query, array $options)
    {
        $statusField = $query->getRepository()->getAlias() . '.' . $this->getConfig('statusField');
        $options = array_merge([
            'published' => true,
        ], $options);

        return $query->where([$statusField => $options['published']]);
    }

    /**
     * @param EventInterface $event
     * @param \Cake\ORM\Query $query
     * @param \ArrayObject $options
     * @param bool $primary
     */
    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, $primary)
    {
        if (isset($options['published'])) {
            $this->findPublished($query, [
                'published' => $options['published'],
            ]);
        }
    }
}
