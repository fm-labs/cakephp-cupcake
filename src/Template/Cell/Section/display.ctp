<?php
/**
 * Render layout and page modules for section
 *
 * Default content module element: 'Banana.Content/content_module'
 */
?>
<?php
$sectionHtml = "";

foreach ($layout_modules as $contentModule):
    $sectionHtml .= $this->element('Banana.Content/content_module', compact('contentModule', 'section', 'refid', 'refscope'));
endforeach;

foreach ($page_modules as $contentModule):
    $sectionHtml .= $this->element('Banana.Content/content_module', compact('contentModule', 'section', 'refid', 'refscope'));
endforeach;

if ($sectionTag) {
    $sectionHtml = $this->Html->tag($sectionTag, $sectionHtml);
}

echo $sectionHtml;
?>

