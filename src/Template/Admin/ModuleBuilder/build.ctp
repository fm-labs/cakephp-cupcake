
<?= "Class: " . $class; ?>

<div class="module-builder ui two column grid">

    <div class="build-container column">
        <h2>- build -</h2>

        <?= $this->element($module->formElement, $module->formData) ?>
        <hr />
    </div>

    <div class="preview-container column">
        <h2>- preview -</h2>

        <?= $this->element($module->viewElement, $module->viewData) ?>
        <hr />
    </div>

</div>


<h3>Debug</h3>
<?php debug($module); ?>
<?php // debug($module->formElement); ?>
<?php // debug($module->formData); ?>
<?php // debug($module->viewElement); ?>
<?php // debug($module->viewData); ?>
- Request data -<br />
<?php debug($data); ?>
        