<ul class="<?= $class; ?> level-<?= $level ?>">
<?php foreach ((array) $menu as $menuItem): ?>
    <?php
        $title = $menuItem['title'];
        $url = $menuItem['url'];
        $attr = (isset($menuItem['attr']) && is_array($menuItem['attr'])) ? $menuItem['attr'] : [];
    ?>
    <li><?= $this->Html->link($title, $url, $attr); ?>
    <?php if ($menuItem['_children']): ?>
        <?php echo $this->element($element, ['menu' => $menuItem['_children'], 'level' => $level + 1, 'class' => $class]); ?>
    <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
