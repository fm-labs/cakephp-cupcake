<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenDate;
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
        'startField' => false, // 'publish_start', // publish start date(time)
        'endField' => false, // 'publish_end', // publish end date(time)
    ];

    /**
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findPublished(Query $query, array $options): Query
    {
        $statusField = $query->getRepository()->getAlias() . '.' . $this->getConfig('statusField');
        $startField = $this->getConfig('startField')
            ? $query->getRepository()->getAlias() . '.' . $this->getConfig('startField')
            : false;
        $endField = $this->getConfig('endField')
            ? $query->getRepository()->getAlias() . '.' . $this->getConfig('endField')
            : false;

        $options = array_merge([
            'published' => true,
        ], $options);

        $query = $query->where([$statusField => $options['published'] ?? true]);

        if ($startField) {
            $query->andWhere(['OR' => [$startField . ' IS NULL', $startField . ' <=' => new \DateTime('now')]]);
        }
        if ($endField) {
            $query->andWhere(['OR' => [$endField . ' IS NULL', $endField . ' >=' => new FrozenDate('now')]]);
        }

        //debug($query->sql());

        return $query;
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
