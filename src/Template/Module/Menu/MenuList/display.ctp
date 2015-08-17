<ul>
    <?php foreach($menu as $menuItem): ?>
    <?= $this->module('Banana.Menu/MenuItem', ['item' => $menuItem]); ?>
    <?php endforeach; ?>
</ul>
<?php //debug($menu); ?>