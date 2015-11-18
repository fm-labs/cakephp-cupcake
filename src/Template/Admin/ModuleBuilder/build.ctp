<div class="module-builder">
    <h2>- ModuleBuilder/build -</h2>
    <?= "Class Name: " . $className; ?>


    <div class="build-container">
        <h4>Build</h4>

        <?= $this->element(
            $module->getFormElement(),
            $module->getFormElementData(),
            $module->getFormElementOptions()
        ); ?>
    </div>

</div>





