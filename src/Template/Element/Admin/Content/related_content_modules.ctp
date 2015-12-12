<?php
$sections = (isset($sections)) ? $sections : ['top', 'before', 'main', 'after', 'bottom', 'header', 'footer'];
?>
<?php foreach($sections as $section): ?>
    <h4 class="ui header"><?= \Cake\Utility\Inflector::humanize($section); ?></h4>
    <div class="ui basic segment" data-section="tab-<?= $section; ?>">
        <?php echo $this->element(
            'Banana.Admin/Content/list_content_modules_editable',
            ['contentModules' => $content->content_modules, 'section' => $section]);
        ?>
    </div>
<?php endforeach; ?>