<?php
/**
 * PageCell::display template
 *
 */
?>
<div class="content-page">
    <?php
    /*
     * <h3>Page:<?= h($page->title); ?></h3>
     */
    ?>
    <?php
    foreach ($page->content_modules as $content_module) {
        //debug($content_module);
        echo $this->cell('Banana.ModuleRenderer', [
            'module' => $content_module->module,
            'template' => $content_module->template
        ]);
    }
    //debug($page);
    ?>
</div>