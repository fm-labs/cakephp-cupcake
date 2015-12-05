<?php $this->Html->addCrumb(__('Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($post->title); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Post')),
                ['action' => 'edit', $post->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Post')),
                ['action' => 'delete', $post->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $post->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Posts')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Post')),
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
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($post->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Slug') ?></td>
            <td><?= h($post->slug) ?></td>
        </tr>
        <tr>
            <td><?= __('Subheading') ?></td>
            <td><?= h($post->subheading) ?></td>
        </tr>
        <tr>
            <td><?= __('Image File') ?></td>
            <td><?= h($post->image_file) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($post->id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Publish Start Datetime') ?></td>
            <td><?= h($post->publish_start_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Publish End Datetime') ?></td>
            <td><?= h($post->publish_end_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($post->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Modified') ?></td>
            <td><?= h($post->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Published') ?></td>
            <td><?= $post->is_published ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Teaser') ?></td>
            <td><?= $this->Text->autoParagraph(h($post->teaser)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Body Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($post->body_html)); ?></td>
        </tr>
    </table>
</div>
