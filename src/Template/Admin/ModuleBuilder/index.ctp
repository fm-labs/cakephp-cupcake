<?php $this->Html->addCrumb('Module Builder', ['action' => 'index']) ?>
<div class="index">
    <h1>Module Builder</h1>


    <table class="ui table striped">
        <thead>
        <tr>
            <th>Class</th>
            <th>Build</th>
            <th>View</th>
        </tr>
        </thead>
        <?php foreach (\Cake\Core\Configure::read('Banana.modules') as $moduleClass => $moduleInfo): ?>
            <tr>
                <td><?= $this->Html->link($moduleClass, ['action' => 'build', 'class' => $moduleInfo['class']]); ?></td>
                <td><?= $this->Html->link('Build', ['action' => 'build', 'class' => $moduleInfo['class']]); ?></td>
                <td><?= $this->Html->link('Build2', ['action' => 'build2', 'mod' => $moduleInfo['class']]); ?></td>
                <td><?= $this->Html->link('View (default)', ['action' => 'view', 'class' => $moduleInfo['class']]); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Legacy</h3>
    <table class="ui table striped">
        <thead>
        <tr>
            <th>Class</th>
        </tr>
        </thead>
        <?php foreach ($modulesAvailable as $moduleClass): ?>
            <tr>
                <td><?= $this->Html->link($moduleClass, ['action' => 'create', 'path' => $moduleClass]); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>