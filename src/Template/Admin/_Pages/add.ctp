<?php $this->Html->addCrumb(__d('banana','Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Page'))); ?>
<div class="pages">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __d('banana','List {0}', __d('banana','Pages')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __d('banana','List {0}', __d('banana','Page Modules')),
                            ['controller' => 'PageModules', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __d('banana','New {0}', __d('banana','Page Module')),
                            ['controller' => 'PageModules', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($page); ?>
    <h2 class="ui top attached header">
        <?= __d('banana','Add {0}', __d('banana','Page')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('lft');
                echo $this->Form->input('rght');
                echo $this->Form->input('parent_id');
                echo $this->Form->input('title');
                echo $this->Form->input('slug');
                echo $this->Form->input('layout_template');
                echo $this->Form->input('page_template');
                echo $this->Form->input('is_published');
                echo $this->Form->input('publish_start_date');
                echo $this->Form->input('publish_end_date');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>