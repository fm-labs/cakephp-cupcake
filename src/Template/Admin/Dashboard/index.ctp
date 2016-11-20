<div class="dashboard">
    <h1>Dashboard</h1>

    <ul>
        <li><?= $this->Html->link('Pages', ['controller' => 'Pages', 'action' => 'index']); ?></li>
        <li><?= $this->Html->link('PageModules', ['controller' => 'PageModules', 'action' => 'index']); ?></li>
        <li><?= $this->Html->link('Posts', ['controller' => 'Posts', 'action' => 'index']); ?></li>
        <li><?= $this->Html->link('Modules', ['controller' => 'Modules', 'action' => 'index']); ?></li>
        <li><?= $this->Html->link('ModuleBuilder', ['controller' => 'ModuleBuilder', 'action' => 'index']); ?></li>
        <li><?= $this->Html->link('ThemesManager', ['controller' => 'ThemesManager', 'action' => 'index']); ?></li>
    </ul>

</div>