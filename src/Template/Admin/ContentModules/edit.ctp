<?php $this->Html->addCrumb(__('Content Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Content Module'))); ?>
<div class="contentModules">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __('Delete'),
                ['action' => 'delete', $contentModule->id],
                ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $contentModule->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __('List {0}', __('Content Modules')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
    
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

    <?= $this->Form->create($contentModule); ?>
    <h2 class="ui top attached header">
        <?= __('Edit {0}', __('Content Module')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('refscope');
                echo $this->Form->input('refid');
                echo $this->Form->input('template');
                echo $this->Form->input('module_id', ['options' => $modules]);
                echo $this->Form->input('section');
                echo $this->Form->input('cssid');
                echo $this->Form->input('cssclass');
                echo $this->Form->input('priority');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>