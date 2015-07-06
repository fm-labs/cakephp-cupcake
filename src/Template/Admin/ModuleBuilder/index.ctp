<?php $this->Html->addCrumb('Module Builder', ['action' => 'index']) ?>
<div class="index">
    <h1>Module Builder</h1>

    <table class="ui table striped">
        <thead>
            <tr>
                <th>Path</th>
            </tr>
        </thead>
        <?php foreach ($modulesAvailable as $moduleName): ?>
        <tr>
            <td><?= $this->Html->link($moduleName, ['action' => 'create', 'path' => $moduleName]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>