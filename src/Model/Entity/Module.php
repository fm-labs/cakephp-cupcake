<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * Module Entity.
 */
class Module extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'title' => true,
        'path' => true,
        'params' => true,
        'params_arr' => true,
        'template' => true, //@TODO add field in database
    ];

}
