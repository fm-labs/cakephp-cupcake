<div class="section" data-section="<?= $section ?>">
    <?php foreach ($page_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', ['contentModule' => $contentModule]); ?>
    <?php endforeach; ?>
    <?php foreach ($layout_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', ['contentModule' => $contentModule]); ?>
    <?php endforeach; ?>
</div>