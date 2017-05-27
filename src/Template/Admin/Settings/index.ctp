<?php $this->Breadcrumbs->add(__d('backend','Settings')); ?>
<?php $this->Toolbar->addLink(
    __d('backend','New {0}', __d('backend','Setting')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend','Import {0}', __d('backend','Settings')),
    ['action' => 'import'],
    ['data-icon' => 'download']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend','Dump {0}', __d('backend','Settings')),
    ['action' => 'dump'],
    ['data-icon' => 'arrow down']
) ?>
<?php

$this->Form->addContextProvider('settings_form', function($request, $context) {
    if ($context['entity'] instanceof \Settings\Form\SettingsForm) {
        return new \Settings\View\Form\SettingsFormContext($request, $context);
    }
});
?>
<div class="settings index">

    <?php echo $this->Form->create($form, ['horizontal' => true]); ?>
    <?php echo $this->Form->allInputs($form->inputs(), ['fieldset' => false] ); ?>
    <?php echo $this->Form->button(__('Save')); ?>
    <?php echo $this->Form->end(); ?>

    <?php debug($form->inputs()); ?>
    <?php //debug($settings); ?>
</div>
