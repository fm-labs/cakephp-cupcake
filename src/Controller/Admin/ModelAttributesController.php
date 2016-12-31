<?php

namespace Banana\Controller\Admin;


use Cake\Network\Exception\BadRequestException;
use Cake\ORM\TableRegistry;

class ModelAttributesController extends AppController
{
    public function index($model = null, $modelId = null) {

        if (!$model) {
            throw new BadRequestException('Model name missing');
        }

        $Model = TableRegistry::get($model);
        if (!$Model) {
            throw new BadRequestException('Model ' . $model . ' not found');
        }

        if (!$Model->behaviors()->has('Attributes')) {
            throw new BadRequestException('Model ' . $model . ' has no Attributes behavior attached');
        }

        if (!$modelId) {
            throw new BadRequestException('Model ID missing');
        }

        $entity = $Model->find('attributes')
            ->where([$Model->primaryKey() => $modelId])
            ->first();

        $attributes = $Model->getAttributes($entity)->toArray();
        $attributesList = $Model->listAttributes($entity)->toArray();

        $availableAttributes = $Model->getAvailableAttributes($entity);
        $availableAttributesList = $Model->listAvailableAttributes($entity);

        $this->set('modelName', $model);
        $this->set('entity', $entity);
        $this->set('attributes', $attributes);
        $this->set('attributesList', $attributesList);
        $this->set('availableAttributes', $availableAttributes);
        $this->set('availableAttributesList', $availableAttributesList);
    }
}