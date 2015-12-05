<?php $this->Html->addCrumb(__('Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Module'))); ?>
<div class="modules">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __('Delete'),
                ['action' => 'delete', $module->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $module->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __('List {0}', __('Modules')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
                                <div class="item">No Actions</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($module); ?>
    <h2 class="ui top attached header">
        <?= __('Edit {0}', __('Module')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('name');
                echo $this->Form->input('title');
                echo $this->Form->input('path');
                echo $this->Form->input('params');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>