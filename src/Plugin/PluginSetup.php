<?php
declare(strict_types=1);

namespace Banana\Plugin;

use Cake\ORM\TableRegistry;

/**
 * Class PluginSetup
 *
 * @package Banana\Plugin
 *
 * @TODO !Experimental!
 */
class PluginSetup
{
    /**
     * Setup an entity type
     *
     * @param $typeName
     * @param $config
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function setupEntityType($typeName, $config)
    {
        $EntityTypes = TableRegistry::getTableLocator()->get('Eav.EntityTypes');

        $entityType = $EntityTypes->find()->where(['name' => $typeName])->first();
        if (!$entityType) {
            $entityType = $EntityTypes->newEmptyEntity();
        }

        $entityType = $EntityTypes->patchEntity($entityType, $config);

        return $EntityTypes->save($entityType);
    }

    /**
     * Setup an attribute group by code
     *
     * @param $code
     * @param $config
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function setupAttributeGroup($code, $config)
    {
        $AttributeGroups = TableRegistry::getTableLocator()->get('Eav.AttributeGroups');

        $attributeGroup = $AttributeGroups->find()->where(['name' => $code])->first();
        if (!$attributeGroup) {
            $attributeGroup = $AttributeGroups->newEmptyEntity();
        }

        $attributeGroup = $AttributeGroups->patchEntity($attributeGroup, $config);

        return $AttributeGroups->save($attributeGroup);
    }

    /**
     * Setup attribute by code
     *
     * @param $code
     * @param $config
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function setupAttribute($code, $config)
    {
        $Attributes = TableRegistry::getTableLocator()->get('Eav.Attributes');

        $attribute = $Attributes->find()->where(['name' => $code])->first();
        if (!$attribute) {
            $attribute = $Attributes->newEmptyEntity();
        }

        $attribute = $Attributes->patchEntity($attribute, $config);

        return $Attributes->save($attribute);
    }
}
