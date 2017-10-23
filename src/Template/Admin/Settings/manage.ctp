<?php // $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="settings index">

    <div class="form" style="max-width: 1000px;">

        <?= $this->Form->create(null, ['horizontal' => true]); ?>
        <?php // $this->Tabs->create(); ?>

        <?php foreach($result as $namespace => $settings): ?>

            <?php // $this->Tabs->add($namespace); ?>
            <h2><?= __("{0} settings",$namespace); ?></h2>
            <?php foreach($settings as $key => $setting): ?>
                <?= $this->Form->input($namespace.'.'.$key, $setting['input']); ?>
                <small>
                    <?= sprintf("Default: %s", $setting['input']['default']); ?><br />
                    <?= sprintf("Current: %s", \Cake\Core\Configure::read($namespace.'.'.$key)); ?><br />
                </small>
            <?php endforeach; ?>
            <hr />

        <?php endforeach; ?>

        <?php // echo $this->Tabs->render(); ?>
        <div class="submit">
            <?= $this->Form->button(__('Update settings')); ?>
        </div>
        <?= $this->Form->end(); ?>

    </div>

    <?php debug($result); ?>
</div>