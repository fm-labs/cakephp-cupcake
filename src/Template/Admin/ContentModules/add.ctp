<?php $this->Html->addCrumb(__d('banana','Content Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Content Module'))); ?>
<div class="contentModules">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __d('banana','List {0}', __d('banana','Content Modules')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
    
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

    <?= $this->Form->create($contentModule); ?>
    <h2 class="ui top attached header">
        <?= __d('banana','Add {0}', __d('banana','Content Module')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('refscope');
                echo $this->Form->input('refid');
                echo $this->Form->input('template');
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