<?php
$this->assign('title', __('Model Attributes for model {0}', $this->get('modelName')));
?>
<div class="index">
    <div class="row">
        <div class="col-md-6">
            <h3>Attached Attributes</h3>
            Model::getAttributes(ENTITY)
            <?php debug($this->get('attributes')); ?>

            Model::getAttributesList(ENTITY)
            <?php debug($this->get('attributesList')); ?>

            Entity::getAttribute(ATTRNAME)
            <?php debug($this->get('entity')->getAttribute('html_meta_title')); ?>

            Entity::getAttributes()
            <?php debug($this->get('entity')->getAttributes()->toArray()); ?>

            Entity::getAttributesList()
            <?php debug($this->get('entity')->getAttributesList()->toArray()); ?>
        </div>
        <div class="col-md-6">
            <h3>Available Attributes</h3>

            Model::getAvailableAttributes(ENTITY)
            <?php debug($this->get('availableAttributes')); ?>

            Model::getAvailableAttributesList(ENTITY)
            <?php debug($this->get('availableAttributesList')); ?>
        </div>
    </div>

    Entity
    <?php debug($this->get('entity')); ?>
</div>