<?php $this->Html->addCrumb(__d('banana','Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Gallery'))); ?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Posts')),
    ['controller' => 'Posts', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Post')),
    ['controller' => 'Posts', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('banana','Add {0}', __d('banana','Gallery')) ?>
    </h2>
    <?= $this->Form->create($gallery, ['class' => 'no-ajax']); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
        echo $this->Form->input('title');
        echo $this->Form->input('desc_html', [
            'type' => 'htmleditor',
            'editor' => [
                'image_list_url' => ['controller' => 'Data', 'action' => 'editorImageList'],
                'link_list_url' => ['controller' => 'Data', 'action' => 'editorLinkList'],
            ]
        ]);
        echo $this->Form->input('view_template');
        echo $this->Form->input('source');
        echo $this->Form->input('source_folder');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>