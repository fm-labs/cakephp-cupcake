<?php $this->Html->addCrumb(__('Page Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Page Module'))); ?>
<div class="pageModules">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __('List {0}', __('Page Modules')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __('List {0}', __('Pages')),
                            ['controller' => 'Pages', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __('New {0}', __('Page')),
                            ['controller' => 'Pages', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
    
                        <?= $this->Ui->link(
                            __('List {0}', __('Modules')),
                            ['controller' => 'Modules', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __('New {0}', __('Module')),
                            ['controller' => 'Modules', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($pageModule); ?>
    <h2 class="ui top attached header">
        <?= __('Add {0}', __('Page Module')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                    echo $this->Form->input('page_id', ['options' => $pages, 'empty' => true]);
                    echo $this->Form->input('module_id', ['options' => $modules]);
                echo $this->Form->input('section');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>