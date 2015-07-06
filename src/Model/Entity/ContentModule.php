<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContentModule Entity.
 */
class ContentModule extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'refscope' => true,
        'refid' => true,
        'module_id' => true,
        'section' => true,
        'module' => true,
        'template' => true,
    ];
}
