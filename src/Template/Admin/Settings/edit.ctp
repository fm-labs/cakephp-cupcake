<?php $this->Html->addCrumb(__('Settings'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Setting'))); ?>
<div class="settings">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __('Delete'),
                ['action' => 'delete', $setting->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __('List {0}', __('Settings')),
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

    <?= $this->Form->create($setting); ?>
    <h2 class="ui top attached header">
        <?= __('Edit {0}', __('Setting')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('ref');
                echo $this->Form->input('scope');
                echo $this->Form->input('name');
                echo $this->Form->input('type');
                echo $this->Form->input('value_int');
                echo $this->Form->input('value_double');
                echo $this->Form->input('value_string');
                echo $this->Form->input('value_text');
                echo $this->Form->input('value_boolean');
                //echo $this->Form->input('value_datetime');
                echo $this->Form->input('description');
                echo $this->Form->input('published');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>