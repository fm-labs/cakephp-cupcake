<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/18/15
 * Time: 9:52 PM
 */

namespace Banana\Model\Entity\Module;

use Banana\Model\Entity\Module;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Entity;

class BaseModule extends Module
{
    use InstanceConfigTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'name' => false,
        'title' => false,
        'path' => false,
        'params' => false,
    ];

    protected $_defaultConfig = [];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);
        $this->set($this->config());
    }
}