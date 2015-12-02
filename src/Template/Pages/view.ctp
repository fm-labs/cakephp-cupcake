<?php
$this->assign('title', $page->title);
?>
<div class="page view">
    <div class="debug">
        Page: <?= h($page->title); ?> [ID <?= h($page->id); ?>]<br />
        Type: <?= h($page->type); ?><br />
        Published: <?= h($page->is_published); ?><br />
    </div>

    <div class="modules">
        <?php foreach ($contentModules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', ['contentModule' => $contentModule]); ?>
        <?php endforeach; ?>
    </div>

    <div class="debug">
        <?php debug($page); ?>
    </div>
</div>
