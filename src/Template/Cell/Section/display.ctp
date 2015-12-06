<div class="section" data-section="<?= $section ?>">
    <?php foreach ($layout_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', compact('contentModule', 'section', 'refid', 'refscope')); ?>
    <?php endforeach; ?>
    <?php foreach ($page_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', compact('contentModule', 'section', 'refid', 'refscope')); ?>
    <?php endforeach; ?>
</div>