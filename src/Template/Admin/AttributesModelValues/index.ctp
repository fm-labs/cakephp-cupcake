<?php $this->Breadcrumbs->add(__('Attributes Model Values')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Attributes Model Value')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
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
<div class="attributesModelValues index">

    <?php $fields = [
    'id','model','modelid','attribute_set_id','attribute_id','value',    ] ?>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.AttributesModelValues',
        'data' => $attributesModelValues,
        'fields' => $fields,
        'debug' => true,
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

</div>

