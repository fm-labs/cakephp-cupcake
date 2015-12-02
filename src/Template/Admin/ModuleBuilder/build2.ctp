
<?= "Class: " . $class; ?><br />
<?= "Refscope: " . $refscope; ?><br />
<?= "Refid: " . $refid; ?><br />
<?= "Section: " . $section; ?><hr />

<div class="module-builder ui grid">
    <div class="row">
        <div class="build-container ten wide column">
            <h2>- build -</h2>
            <div class="ui form">
                <?= $this->Form->create($module, ['url' => [
                    'action' => 'build2',
                    'path' => $class,
                    'refscope' => $refscope,
                    'refid' => $refid,
                    'section' => $section
                ] ]); ?>
                <?= $this->Form->input('id'); ?>
                <?= $this->Form->input('name'); ?>
                <?= $this->Form->input('path'); ?>

                <?php foreach ($formInputs as $field => $fieldOptions) {
                    echo $this->Form->input($field, $fieldOptions);
                }
                ?>

                <?= $this->Form->input('params', ['disabled' => true]); ?>
                <?= $this->Form->input('_save', ['type' => 'checkbox', 'default' => 0]); ?>

                <?= $this->Form->submit('Save'); ?>
                <?= $this->Form->end(); ?>

            </div>
        </div>
        <div class="preview-container six wide column">
            <h2>- preview -</h2>

            <?= $this->cell($module->path . 'Module', [$module]) ?>
            <hr />
        </div>
    </div>


</div>


<h3>Debug</h3>
<?php debug($module); ?>
- Request data -<br />
<?php debug($data); ?>
        