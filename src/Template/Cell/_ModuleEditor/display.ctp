<?php
$module = $this->get('module');
$modulePath = $this->get('modulePath');
$moduleParams = $this->get('moduleParams');
$moduleForm = $this->get('moduleForm');
$moduleFormInputs = $this->get('moduleFormInputs');
$moduleFormUrl = $this->get('moduleFormUrl')
?>
<div class="form">
    <?php
    $this->request->data = $moduleParams;
    echo $this->Form->create($moduleForm, [
        'class' => 'ui form',
        'url' => $moduleFormUrl,
        //'context' => ['entity' => $module], //@TODO Create ModuleFormContext
    ]);
    echo $this->Form->allInputs($moduleFormInputs, ['legend' => false, 'fieldset' => false]);
    echo $this->Form->button('Save');
    echo $this->Form->end();
    ?>
</div>