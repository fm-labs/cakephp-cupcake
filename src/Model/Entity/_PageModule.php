<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * PageModule Entity.
 */
class PageModule extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'page_id' => true,
        'module_id' => true,
        'section' => true,
        'page' => true,
        'module' => true,
    ];
}
