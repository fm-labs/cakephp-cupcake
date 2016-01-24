<?php
$this->loadHelper('Backend.Tabs');
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __d('banana','Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__d('banana','Edit {0}', __d('banana','Page')), ['action' => 'edit', $content->id], ['icon' => 'edit']);
$this->Toolbar->addLink(__d('banana','Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);
$this->Toolbar->addLink(__d('banana','List {0}', __d('banana','Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'add']);


// HEADING
$this->assign('heading', __d('banana','Page: {0}', $content->title));
$this->assign('title', sprintf('[%s] %s (#%s)', 'Pages', $content->title, $content->id));

// CONTENT
?>
<div class="pages">

    <div class="ui basic segment">
        Slug: <?= h($content->slug); ?><br />
        Type: <?= h($content->type); ?><br />
        Published: <?= $this->Ui->statusLabel($content->is_published); ?><br />
        <?= $this->Html->link(__('Edit {0}', __('Page')), ['action' => 'edit', $content->id]); ?>
    </div>
    <div class="ui divider"></div>

    <?php $this->Tabs->start(); ?>

    <?php $this->Tabs->add(__d('banana','Related Posts')); ?>

    <h3>Related Posts</h3>
    <table class="ui compact table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Title</th>
            <th>Is Published</th>
            <th class="actions">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($content->posts as $post): ?>
            <tr>
                <td><?= h($post->order); ?></td>
                <td><?= h($post->title); ?></td>
                <td><?= $this->Ui->statusLabel($post->is_published); ?></td>
                <td class="actions">
                    <?= $this->Ui->link('Edit',
                        ['controller' => 'Posts', 'action' => 'edit', $post->id],
                        ['class' => 'ui mini button', 'icon' => 'edit']
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="actions">
        <?= $this->Ui->link('Add Post',
            ['controller' => 'Posts', 'action' => 'add', 'refid' => $content->id, 'refscope' => 'Banana.Pages'],
            ['class' => 'ui button', 'icon' => 'add']
        ); ?>
    </div>


    <?php $this->Tabs->add('Related Content Modules'); ?>
    <!-- RELATED CONTENT MODULES -->
        <h3>Related content modules</h3>
        <?= $this->element('Banana.Admin/Content/related_content_modules', compact('content', 'sections')); ?>
        <br />
        <?= $this->Ui->link('Build a new module for this page', [
            'controller' => 'ModuleBuilder',
            'action' => 'build2',
            'refscope' => 'Banana.Pages',
            'refid' => $content->id
        ], ['class' => 'ui button', 'icon' => 'add']); ?>


    <?php $this->Tabs->add('Link existing module'); ?>
        <h3>Link existing module</h3>
        <div class="ui form">
            <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
            <?= $this->Form->input('refscope', ['default' => 'Banana.Pages']); ?>
            <?= $this->Form->input('refid', ['default' => $content->id]); ?>
            <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
            <?= $this->Form->input('section'); ?>
            <?= $this->Form->submit('Link module'); ?>
            <?= $this->Form->end(); ?>
        </div>

    <?php echo $this->Tabs->render(); ?>

</div>

<?php $this->append('scriptBottom'); ?>
    <script>
        /*
        $(document).ready(function() {

            //$('.content-modules .menu .item:first-child').addClass('active');
            //$('.content-modules .tab:first-child').addClass('active');
            $('.content-modules .menu .item').tab();
            $('.content-modules .menu .item:first-child').trigger('click');

            $('.select-type').hide();
            $('#select-type').change(function() {
                var type = $(this).val();
                $('.select-type').fadeOut();
                $('.select-type-' + type).fadeIn();
            }).trigger('change');
        })
        */
    </script>
<?php $this->end(); ?>