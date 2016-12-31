<?php $this->Breadcrumbs->add(__('Attributes Model Values'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($attributesModelValue->id); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Attributes Model Value')),
    ['action' => 'edit', $attributesModelValue->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Attributes Model Value')),
    ['action' => 'delete', $attributesModelValue->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attributesModelValue->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Attributes Model Values')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attributes Model Value')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attribute Sets')),
    ['controller' => 'AttributeSets', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attribute Set')),
    ['controller' => 'AttributeSets', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attributes')),
    ['controller' => 'Attributes', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attribute')),
    ['controller' => 'Attributes', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="attributesModelValues view">
    <h2 class="ui header">
        <?= h($attributesModelValue->id) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $attributesModelValue ], [
        'title' => $attributesModelValue->title,
        'model' => 'Banana.AttributesModelValues',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Model') ?></td>
            <td><?= h($attributesModelValue->model) ?></td>
        </tr>
        <tr>
            <td><?= __('Attribute Set') ?></td>
            <td><?= $attributesModelValue->has('attribute_set') ? $this->Html->link($attributesModelValue->attribute_set->title, ['controller' => 'AttributeSets', 'action' => 'view', $attributesModelValue->attribute_set->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Attribute') ?></td>
            <td><?= $attributesModelValue->has('attribute') ? $this->Html->link($attributesModelValue->attribute->name, ['controller' => 'Attributes', 'action' => 'view', $attributesModelValue->attribute->id]) : '' ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($attributesModelValue->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Modelid') ?></td>
            <td><?= $this->Number->format($attributesModelValue->modelid) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Value') ?></td>
            <td><?= $this->Text->autoParagraph(h($attributesModelValue->value)); ?></td>
        </tr>
    </table>
</div>
-->



