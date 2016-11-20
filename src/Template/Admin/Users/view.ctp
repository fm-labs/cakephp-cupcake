<?php $this->Html->addCrumb(__d('content','Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($user->id); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="edit icon"></i>
                <?= $this->Html->link(__d('content','Edit {0}', __d('content','User')), ['action' => 'edit', $user->id]) ?>
            </div>
            <div class="item">
                <i class="remove icon"></i>
                <?= $this->Form->postLink(__d('content','Delete {0}', __d('content','User')), ['action' => 'delete', $user->id], ['confirm' => __d('content','Are you sure you want to delete # {0}?', $user->id)]) ?>
            </div>
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__d('content','List {0}', __d('content','Users')), ['action' => 'index']) ?>
            </div>
            <div class="item">
                <i class="add icon"></i>
                <?= $this->Html->link(__d('content','New {0}', __d('content','User')), ['action' => 'add']) ?>
            </div>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="users view">
    <h2><?= h($user->id) ?></h2>
    <div class="ui list">

        <div class="item">
            <div class="content">
                <span class="header"><?= __d('content','Username') ?></span>
                <div class="description"><?= h($user->username) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __d('content','Password') ?></span>
                <div class="description"><?= '' // h($user->password) ?></div>
            </div>
        </div>


        <div class="item">
            <div class="content">
                <span class="header"><?= __d('content','Id') ?></span>
                <div class="description"><?= $this->Number->format($user->id) ?></div>
            </div>
        </div>


            <div class="item">
                <div class="content">
                    <span class="header"><?= __d('content','Created') ?></span>
                    <div class="description"><?= h($user->created) ?></div>
                </div>
            </div>
            <div class="item">
                <div class="content">
                    <span class="header"><?= __d('content','Modified') ?></span>
                    <div class="description"><?= h($user->modified) ?></div>
                </div>
            </div>

        <div class="booleans">
            <div class="item">
                <div class="content">
                    <span class="header"><?= __d('content','Is Login Allowed') ?></span>
                    <div class="description"><?= $user->is_login_allowed ? __d('content','Yes') : __d('content','No'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
