<?php
$this->assign('title', $page->title);
?>
<div class="page view">
    <div class="page-debug">
        Page: <?= h($page->title); ?> [ID <?= h($page->id); ?>]<br />
        Type: <?= h($page->type); ?><br />
        Published: <?= h($page->is_published); ?><br />
    </div>

    <div class="page-modules">
        <?php echo $this->cell('Banana.PageModules', ['page' => $page->id]); ?>
    </div>
</div>
