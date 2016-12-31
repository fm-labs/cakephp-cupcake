<?php $this->Breadcrumbs->add(__('Attributes'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($attribute->name); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Attribute')),
    ['action' => 'edit', $attribute->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Attribute')),
    ['action' => 'delete', $attribute->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Attributes')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attribute')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="attributes view">
    <h2 class="ui header">
        <?= h($attribute->name) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $attribute ], [
        'title' => $attribute->title,
        'model' => 'Banana.Attributes',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($attribute->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($attribute->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Type') ?></td>
            <td><?= h($attribute->type) ?></td>
        </tr>
        <tr>
            <td><?= __('Ref') ?></td>
            <td><?= h($attribute->ref) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($attribute->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Required') ?></td>
            <td><?= $attribute->is_required ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __('Is Searchable') ?></td>
            <td><?= $attribute->is_searchable ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __('Is Filterable') ?></td>
            <td><?= $attribute->is_filterable ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __('Is Protected') ?></td>
            <td><?= $attribute->is_protected ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
-->



