<div class="section" data-section="<?= $section ?>">
    <?php foreach ($layout_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', ['contentModule' => $contentModule, 'section' => $section, 'page_id' => $page_id]); ?>
    <?php endforeach; ?>
    <?php foreach ($page_modules as $contentModule): ?>
        <?= $this->element('Banana.Content/content_module', ['contentModule' => $contentModule, 'section' => $section, 'page_id' => $page_id]); ?>
    <?php endforeach; ?>
</div>