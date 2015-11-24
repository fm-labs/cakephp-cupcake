
<?= "Class: " . $class; ?>

<div class="module-builder ui two column grid">

    <div class="build-container column">
        <h2>- build -</h2>
        <div class="ui form">
            <?= $this->Form->create($module); ?>
            <?= $this->Form->input('id'); ?>
            <?= $this->Form->input('name'); ?>
            <?= $this->Form->input('path'); ?>
            <?= $this->Form->input('params', ['disabled' => true]); ?>

            <?php foreach ($formInputs as $field => $fieldOptions) {
                echo $this->Form->input($field, $fieldOptions);
            }
            ?>

            <?= $this->Form->input('_save', ['type' => 'checkbox', 'default' => 0]); ?>

            <?= $this->Form->submit('Save'); ?>
            <?= $this->Form->end(); ?>

        </div>
        <hr />
    </div>

    <div class="preview-container column">
        <h2>- preview -</h2>

        <?= $this->cell($module->path . 'Module', [$module]) ?>
        <hr />
    </div>

</div>


<h3>Debug</h3>
<?php debug($module); ?>
- Request data -<br />
<?php debug($data); ?>
        