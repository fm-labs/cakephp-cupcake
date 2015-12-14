<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($post->title); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','Edit {0}', __d('banana','Post')),
                ['action' => 'edit', $post->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('banana','Delete {0}', __d('banana','Post')),
                ['action' => 'delete', $post->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $post->id)]) ?>

            <?= $this->Ui->link(
                __d('banana','List {0}', __d('banana','Posts')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Post')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="posts view">
    <h2 class="ui top attached header">
        <?= h($post->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('banana','Label'); ?></th>
            <th><?= __d('banana','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('banana','Title') ?></td>
            <td><?= h($post->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Slug') ?></td>
            <td><?= h($post->slug) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Subheading') ?></td>
            <td><?= h($post->subheading) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Image File') ?></td>
            <td><?= h($post->image_file) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($post->id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('banana','Publish Start Datetime') ?></td>
            <td><?= h($post->publish_start_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Publish End Datetime') ?></td>
            <td><?= h($post->publish_end_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Created') ?></td>
            <td><?= h($post->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Modified') ?></td>
            <td><?= h($post->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('banana','Is Published') ?></td>
            <td><?= $post->is_published ? __d('banana','Yes') : __d('banana','No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('banana','Teaser') ?></td>
            <td><?= $this->Text->autoParagraph(h($post->teaser)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('banana','Body Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($post->body_html)); ?></td>
        </tr>
    </table>
</div>
