<?php $this->Html->addCrumb('Module Builder', ['action' => 'index']) ?>
<div class="index">
    <h1>Module Builder</h1>

    <table class="ui table striped">
        <thead>
        <tr>
            <th>Module</th>
            <th>Actions</th>
        </tr>
        </thead>
        <?php foreach ($availableModules as $moduleClass => $moduleInfo): ?>
            <tr>
                <td><?= h($moduleClass); ?></td>
                <td class="actions">
                    <?= $this->Html->link('Create new module', [
                        'action' => 'build2',
                        'path' => $moduleInfo['class'],
                        'refscope' => $refscope,
                        'refid' => $refid
                    ], ['class' => 'ui small button']); ?>
                    <!--
                    <?= $this->Html->link('View', ['action' => 'view', 'class' => $moduleInfo['class']]); ?>
                    -->
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>