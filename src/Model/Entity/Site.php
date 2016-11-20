<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * Site Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \Banana\Model\Entity\ParentSite $parent_site
 * @property string $alias
 * @property string $title
 * @property string $hostname
 * @property \Banana\Model\Entity\ChildSite[] $child_sites
 */
class Site extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
