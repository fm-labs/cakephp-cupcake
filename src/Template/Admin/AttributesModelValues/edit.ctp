<?php $this->Breadcrumbs->add(__('Attributes Model Values'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Edit {0}', __('Attributes Model Value'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $attributesModelValue->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attributesModelValue->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attributes Model Values')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Attributes Model Value')) ?>
    </h2>
    <?= $this->Form->create($attributesModelValue, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->input('model');
                echo $this->Form->input('modelid');
                    echo $this->Form->input('attribute_set_id', ['options' => $attributeSets, 'empty' => true]);
                    echo $this->Form->input('attribute_id', ['options' => $attributes]);
                echo $this->Form->input('value');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>