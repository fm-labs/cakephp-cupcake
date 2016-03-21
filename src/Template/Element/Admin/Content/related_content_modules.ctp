<?php
$sections = (isset($sections)) ? $sections : ['top', 'before', 'main', 'after', 'bottom', 'header', 'footer'];
?>
<?php foreach($sections as $section): ?>
    <h4><?= \Cake\Utility\Inflector::humanize($section); ?></h4>
    <?php echo $this->element(
        'Banana.Admin/Content/list_content_modules_editable',
        ['contentModules' => $content->content_modules, 'section' => $section]);
    ?>
<?php endforeach; ?>