
<?= "Class: " . $class; ?>

<div class="module-builder ui two column grid">

    <div class="build-container column">
        <h2>- build -</h2>

        <?= $this->element($module->getFormElement(), $module->getFormElementData()) ?>
        <hr />
    </div>

    <div class="preview-container column">
        <h2>- preview -</h2>

        <?= $this->element($module->getViewElement(), $module->getViewElementData()) ?>
        <hr />
    </div>

</div>


<h3>Debug</h3>
<?php debug($module->toArray()); ?>
<?php debug($module->getFormElement()); ?>
<?php debug($module->getFormElementData()); ?>
<?php debug($module->getViewElement()); ?>
<?php debug($module->getViewElementData()); ?>
- Request data -<br />
<?php debug($data); ?>
        