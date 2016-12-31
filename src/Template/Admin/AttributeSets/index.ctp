<?php $this->Breadcrumbs->add(__('Attribute Sets')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Attribute Set')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<div class="attributeSets index">

    <?php $fields = [
    'id','title',    ] ?>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.AttributeSets',
        'data' => $attributeSets,
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

