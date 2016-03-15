<div class="related content-modules">

    <?= $this->element('Banana.Admin/Content/related_content_modules', compact('content', 'sections')); ?>
    <br />
    <?= $this->Ui->link('Build a new module for this page', [
        'controller' => 'ModuleBuilder',
        'action' => 'build2',
        'refscope' => 'Banana.Pages',
        'refid' => $content->id
    ], ['class' => 'ui button', 'icon' => 'add']); ?>

    <div class="ui divider"></div>

    <h4>Link existing module</h4>
    <div class="ui form">
        <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
        <?= $this->Form->input('refscope', ['default' => 'Banana.Pages']); ?>
        <?= $this->Form->input('refid', ['default' => $content->id]); ?>
        <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
        <?= $this->Form->input('section'); ?>
        <?= $this->Form->submit('Link module'); ?>
        <?= $this->Form->end(); ?>
    </div>

</div>

