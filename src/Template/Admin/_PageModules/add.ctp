<?php $this->Html->addCrumb(__d('banana','Page Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Page Module'))); ?>
<div class="pageModules">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __d('banana','List {0}', __d('banana','Page Modules')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __d('banana','List {0}', __d('banana','Pages')),
                            ['controller' => 'Pages', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __d('banana','New {0}', __d('banana','Page')),
                            ['controller' => 'Pages', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
    
                        <?= $this->Ui->link(
                            __d('banana','List {0}', __d('banana','Modules')),
                            ['controller' => 'Modules', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __d('banana','New {0}', __d('banana','Module')),
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
        <?= __d('banana','Add {0}', __d('banana','Page Module')) ?>
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
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>