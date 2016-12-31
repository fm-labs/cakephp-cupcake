<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * AttributesModelValue Entity
 *
 * @property int $id
 * @property string $model
 * @property int $modelid
 * @property int $attribute_set_id
 * @property int $attribute_id
 * @property string $value
 *
 * @property \Banana\Model\Entity\AttributeSet $attribute_set
 * @property \Banana\Model\Entity\Attribute $attribute
 */
class AttributesModelValue extends Entity
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
        'id' => false
    ];
}
