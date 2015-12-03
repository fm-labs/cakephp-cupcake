<?php $this->Html->addCrumb(__('Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($gallery->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Gallery')),
    ['action' => 'edit', $gallery->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Gallery')),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $gallery->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Gallery')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Posts')),
    ['controller' => 'Posts', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Post')),
    ['controller' => 'Posts', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="galleries view">
    <h2 class="ui header">
        <?= h($gallery->title) ?>
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
            <td><?= h($gallery->title) ?></td>
        </tr>
        <tr>
            <td><?= __('View Template') ?></td>
            <td><?= h($gallery->view_template) ?></td>
        </tr>
        <tr>
            <td><?= __('Source') ?></td>
            <td><?= h($gallery->view_template) ?></td>
        </tr>

        <tr>
            <td><?= __('Source Folder') ?></td>
            <td><?= h($gallery->view_template) ?></td>
        </tr>



        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($gallery->id) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Desc Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($gallery->desc_html)); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('Posts')) ?></h4>
    <?php if (!empty($gallery->posts)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Refscope') ?></th>
            <th><?= __('Refid') ?></th>
            <th><?= __('Parent Id') ?></th>
            <th><?= __('Type') ?></th>
            <th><?= __('Title') ?></th>
            <th><?= __('Slug') ?></th>
            <th><?= __('Subheading') ?></th>
            <th><?= __('Teaser Html') ?></th>
            <th><?= __('Teaser Link Href') ?></th>
            <th><?= __('Teaser Link Caption') ?></th>
            <th><?= __('Teaser Image File') ?></th>
            <th><?= __('Body Html') ?></th>
            <th><?= __('Image File') ?></th>
            <th><?= __('Image Link Href') ?></th>
            <th><?= __('Image Link Target') ?></th>
            <th><?= __('Image Desc') ?></th>
            <th><?= __('Image Files') ?></th>
            <th><?= __('Template') ?></th>
            <th><?= __('Cssclass') ?></th>
            <th><?= __('Cssid') ?></th>
            <th><?= __('Meta Description') ?></th>
            <th><?= __('Meta Keywords') ?></th>
            <th><?= __('Is Published') ?></th>
            <th><?= __('Publish Start Datetime') ?></th>
            <th><?= __('Publish End Datetime') ?></th>
            <th><?= __('Modified') ?></th>
            <th><?= __('Created') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($gallery->posts as $posts): ?>
        <tr>
            <td><?= h($posts->id) ?></td>
            <td><?= h($posts->refscope) ?></td>
            <td><?= h($posts->refid) ?></td>
            <td><?= h($posts->parent_id) ?></td>
            <td><?= h($posts->type) ?></td>
            <td><?= h($posts->title) ?></td>
            <td><?= h($posts->slug) ?></td>
            <td><?= h($posts->subheading) ?></td>
            <td><?= h($posts->teaser_html) ?></td>
            <td><?= h($posts->teaser_link_href) ?></td>
            <td><?= h($posts->teaser_link_caption) ?></td>
            <td><?= h($posts->teaser_image_file) ?></td>
            <td><?= h($posts->body_html) ?></td>
            <td><?= h($posts->image_file) ?></td>
            <td><?= h($posts->image_link_href) ?></td>
            <td><?= h($posts->image_link_target) ?></td>
            <td><?= h($posts->image_desc) ?></td>
            <td><?= h($posts->image_files) ?></td>
            <td><?= h($posts->template) ?></td>
            <td><?= h($posts->cssclass) ?></td>
            <td><?= h($posts->cssid) ?></td>
            <td><?= h($posts->meta_description) ?></td>
            <td><?= h($posts->meta_keywords) ?></td>
            <td><?= h($posts->is_published) ?></td>
            <td><?= h($posts->publish_start_datetime) ?></td>
            <td><?= h($posts->publish_end_datetime) ?></td>
            <td><?= h($posts->modified) ?></td>
            <td><?= h($posts->created) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Posts', 'action' => 'view', $posts->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Posts', 'action' => 'edit', $posts->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Posts', 'action' => 'delete', $posts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $posts->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>


    <?= $this->Html->link(__('Add Item'), ['action' => 'addItem', $gallery->id]) ?>
    </div>
</div>
