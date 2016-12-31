<?php $this->Breadcrumbs->add(__('Attributes')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Attribute')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<div class="attributes index">

    <?php $fields = [
    'id','name','title','type','is_required','is_searchable','is_filterable','is_protected','ref',    ] ?>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.Attributes',
        'data' => $attributes,
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

