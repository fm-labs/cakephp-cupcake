<?php $this->Html->addCrumb(__d('content','Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('content','New {0}', __d('content','User'))); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__d('content','List {0}', __d('content','Users')), ['action' => 'index']) ?>
            </div>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="tasks icon"></i>Actions
                <div class="menu">
                    <div class="item">No Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="users ui form">
    <h2><?= __d('content','Add {0}', __d('content','User')) ?></h2>
    <?= $this->Form->create($user); ?>
    <?php
        echo $this->Form->input('username');
        //echo $this->Form->input('password');
        echo $this->Form->input('is_login_allowed');
    ?>
    <?= $this->Form->button(__d('content','Submit')) ?>
    <?= $this->Form->end() ?>
</div>
