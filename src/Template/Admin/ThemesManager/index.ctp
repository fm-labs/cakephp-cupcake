<div class="index">
    <h1 class="ui header">Themes Manager</h1>

    <h3 class="ui top attached header">Available Themes</h3>
    <div class="ui segment attached">
    <table class="ui striped table">
        <thead>
        <tr>
            <th>Theme</th>
            <th>Loaded</th>
            <th class="actions">Actions</th>
        </tr>
        </thead>
        <?php foreach($themesAvailable as $theme): ?>
        <tr>
            <td><?= h($theme['name']); ?></td>
            <td><?= h($theme['loaded']); ?></td>
            <td class="actions">
                <?= $this->Html->link('Details', ['action' => 'details', $theme['name']]); ?>
                <?= $this->Html->link('Install', ['action' => 'install', $theme['name']]); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>


    <h3 class="ui top attached header">Installed Themes</h3>
    <div class="ui segment attached">
        <table class="ui striped table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Path</th>
                <th class="actions">Actions</th>
            </tr>
            </thead>
            <?php foreach($themesInstalled as $theme): ?>
                <tr>
                    <td><?= h($theme->name); ?></td>
                    <td><?= h($theme->path); ?></td>
                    <td class="actions">
                        <?= $this->Html->link('View', ['controller' => 'Themes', 'action' => 'view', $theme->id]); ?>
                        <?= $this->Html->link('Uninstall', ['action' => 'uninstall', $theme->id]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>