<ul class="<?= $class; ?> level-<?= $level ?>">
<?php foreach ((array) $menu as $menuItem): ?>
    <li><?= $this->Html->link($menuItem['title'], $menuItem['url'], $menuItem['attr']); ?>
    <?php if ($menuItem['_children']): ?>
        <?php echo $this->element($element, ['menu' => $menuItem['_children'], 'level' => $level + 1, 'class' => $class]); ?>
    <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
