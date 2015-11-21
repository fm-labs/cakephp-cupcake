<?php
/**
 * PageCell::display template
 *
 */
?>
<?php foreach ($contentModules as $contentModule): ?>
    <?php
    /**
     * Kinda dirty workaround to inject custom module template from content module
     */
    $contentModule->module->template = $contentModule->template;

    echo $this->element('Banana.Pages/page_module', [
        'module' => $contentModule->module
    ]);
    ?>
<?php endforeach; ?>