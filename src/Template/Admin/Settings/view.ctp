<?php $this->Html->addCrumb(__('Settings'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($setting->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Setting')),
                ['action' => 'edit', $setting->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Setting')),
                ['action' => 'delete', $setting->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Settings')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Setting')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="settings view">
    <h2 class="ui top attached header">
        <?= h($setting->id) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Ref') ?></td>
            <td><?= h($setting->ref) ?></td>
        </tr>
        <tr>
            <td><?= __('Scope') ?></td>
            <td><?= h($setting->scope) ?></td>
        </tr>
        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($setting->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Value String') ?></td>
            <td><?= h($setting->value_string) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($setting->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Type') ?></td>
            <td><?= $this->Number->format($setting->type) ?></td>
        </tr>
        <tr>
            <td><?= __('Value Int') ?></td>
            <td><?= $this->Number->format($setting->value_int) ?></td>
        </tr>
        <tr>
            <td><?= __('Value Double') ?></td>
            <td><?= $this->Number->format($setting->value_double) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Value Datetime') ?></td>
            <td><?= h($setting->value_datetime) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($setting->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Updated') ?></td>
            <td><?= h($setting->updated) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Value Boolean') ?></td>
            <td><?= $setting->value_boolean ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __('Published') ?></td>
            <td><?= $setting->published ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Value Text') ?></td>
            <td><?= $this->Text->autoParagraph(h($setting->value_text)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Description') ?></td>
            <td><?= $this->Text->autoParagraph(h($setting->description)); ?></td>
        </tr>
    </table>
</div>
