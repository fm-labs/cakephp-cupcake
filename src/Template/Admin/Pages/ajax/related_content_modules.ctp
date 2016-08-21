<div class="related content-modules">

    <?= $this->element('Banana.Admin/Content/related_content_modules', compact('content', 'sections')); ?>
    <!--
    <br />
    <?= $this->Ui->link('Build a new module for this page', [
        'controller' => 'ModuleBuilder',
        'action' => 'build2',
        'refscope' => 'Banana.Pages',
        'refid' => $content->id
    ], ['class' => 'btn btn-default', 'icon' => 'plus']); ?>
    -->

    <hr />

    <h3><?= __('Link existing module'); ?></h3>
    <div class="form">
        <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
        <?= $this->Form->hidden('refscope', ['default' => 'Banana.Pages']); ?>
        <?= $this->Form->hidden('refid', ['default' => $content->id]); ?>
        <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
        <?= $this->Form->input('section'); ?>
        <?= $this->Form->button('Link module'); ?>
        <?= $this->Form->end(); ?>
    </div>

</div>

