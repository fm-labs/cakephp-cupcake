<?php $this->Breadcrumbs->add(__('Attribute Sets'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($attributeSet->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Attribute Set')),
    ['action' => 'edit', $attributeSet->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Attribute Set')),
    ['action' => 'delete', $attributeSet->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attributeSet->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Attribute Sets')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attribute Set')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="attributeSets view">
    <h2 class="ui header">
        <?= h($attributeSet->title) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $attributeSet ], [
        'title' => $attributeSet->title,
        'model' => 'Banana.AttributeSets',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($attributeSet->title) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($attributeSet->id) ?></td>
        </tr>

    </table>
</div>
-->



