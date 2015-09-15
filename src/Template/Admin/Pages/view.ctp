<?php $this->Html->addCrumb(__('Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($page->title); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Page')),
                ['action' => 'edit', $page->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Page')),
                ['action' => 'delete', $page->id],
                ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $page->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Pages')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Page')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                    <?= $this->Ui->link(
                        __('List {0}', __('Page Modules')),
                        ['controller' => 'PageModules', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __('New {0}', __('Page Module')),
                        ['controller' => 'PageModules', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="pages view">
    <h2 class="ui top attached header">
        <?= h($page->title) ?>
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
            <td><?= h($page->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Slug') ?></td>
            <td><?= h($page->slug) ?></td>
        </tr>
        <tr>
            <td><?= __('Page Template') ?></td>
            <td><?= h($page->page_template) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($page->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Lft') ?></td>
            <td><?= $this->Number->format($page->lft) ?></td>
        </tr>
        <tr>
            <td><?= __('Rght') ?></td>
            <td><?= $this->Number->format($page->rght) ?></td>
        </tr>
        <tr>
            <td><?= __('Parent Id') ?></td>
            <td><?= $this->Number->format($page->parent_id) ?></td>
        </tr>
        <tr>
            <td><?= __('Page Layout Id') ?></td>
            <td><?= $this->Number->format($page->page_layout_id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Publish Start Date') ?></td>
            <td><?= h($page->publish_start_date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Publish End Date') ?></td>
            <td><?= h($page->publish_end_date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($page->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Modified') ?></td>
            <td><?= h($page->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Published') ?></td>
            <td><?= $page->is_published ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __('Related {0}', __('PageModules')) ?></h4>
    <?php if (!empty($page->content_modules)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Refscope') ?></th>
            <th><?= __('Refid') ?></th>
            <th><?= __('Module Id') ?></th>
            <th><?= __('Section') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($page->content_modules as $pageModules): ?>
        <tr>
            <td><?= h($pageModules->id) ?></td>
            <td><?= h($pageModules->refscope) ?></td>
            <td><?= h($pageModules->refid) ?></td>
            <td><?= h($pageModules->module_id) ?></td>
            <td><?= h($pageModules->section) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'PageModules', 'action' => 'view', $pageModules->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'PageModules', 'action' => 'edit', $pageModules->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'PageModules', 'action' => 'delete', $pageModules->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pageModules->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
