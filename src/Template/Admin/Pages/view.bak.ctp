<?php $this->Html->addCrumb(__d('banana','Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($page->title); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','Edit {0}', __d('banana','Page')),
                ['action' => 'edit', $page->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('banana','Delete {0}', __d('banana','Page')),
                ['action' => 'delete', $page->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $page->id)]) ?>

            <?= $this->Ui->link(
                __d('banana','List {0}', __d('banana','Pages')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Page')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'plus']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                    <?= $this->Ui->link(
                        __d('banana','List {0}', __d('banana','Page Modules')),
                        ['controller' => 'PageModules', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __d('banana','New {0}', __d('banana','Page Module')),
                        ['controller' => 'PageModules', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'plus']
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
            <th><?= __d('banana','Label'); ?></th>
            <th><?= __d('banana','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('banana','Title') ?></td>
            <td><?= h($page->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Slug') ?></td>
            <td><?= h($page->slug) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Page Template') ?></td>
            <td><?= h($page->page_template) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($page->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Lft') ?></td>
            <td><?= $this->Number->format($page->lft) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Rght') ?></td>
            <td><?= $this->Number->format($page->rght) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Parent Id') ?></td>
            <td><?= $this->Number->format($page->parent_id) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Page Layout Id') ?></td>
            <td><?= $this->Number->format($page->page_layout_id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('banana','Publish Start Date') ?></td>
            <td><?= h($page->publish_start_date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Publish End Date') ?></td>
            <td><?= h($page->publish_end_date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Created') ?></td>
            <td><?= h($page->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('banana','Modified') ?></td>
            <td><?= h($page->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('banana','Is Published') ?></td>
            <td><?= $page->is_published ? __d('banana','Yes') : __d('banana','No'); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __d('banana','Related {0}', __d('banana','PageModules')) ?></h4>
    <?php if (!empty($page->content_modules)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('banana','Id') ?></th>
            <th><?= __d('banana','Refscope') ?></th>
            <th><?= __d('banana','Refid') ?></th>
            <th><?= __d('banana','Module Id') ?></th>
            <th><?= __d('banana','Section') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
        <?php foreach ($page->content_modules as $pageModules): ?>
        <tr>
            <td><?= h($pageModules->id) ?></td>
            <td><?= h($pageModules->refscope) ?></td>
            <td><?= h($pageModules->refid) ?></td>
            <td><?= h($pageModules->module_id) ?></td>
            <td><?= h($pageModules->section) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('banana','View'), ['controller' => 'PageModules', 'action' => 'view', $pageModules->id]) ?>

                <?= $this->Html->link(__d('banana','Edit'), ['controller' => 'PageModules', 'action' => 'edit', $pageModules->id]) ?>

                <?= $this->Form->postLink(__d('banana','Delete'), ['controller' => 'PageModules', 'action' => 'delete', $pageModules->id], ['confirm' => __d('banana','Are you sure you want to delete # {0}?', $pageModules->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
