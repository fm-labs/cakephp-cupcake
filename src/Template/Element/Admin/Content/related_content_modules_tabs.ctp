<div class="ui top attached tabular menu">
    <?php foreach($sections as $section): ?>
        <a class="item" data-tab="tab-<?= $section ?>"><?= \Cake\Utility\Inflector::humanize($section); ?></a>
    <?php endforeach; ?>
</div>
<?php foreach($sections as $section): ?>
    <h4><?= \Cake\Utility\Inflector::humanize($section); ?></h4>
    <div class="ui bottom attached tab segment" data-tab="tab-<?= $section; ?>">
        <?php echo $this->element(
            'Banana.Admin/Content/list_content_modules_editable',
            ['contentModules' => $content->content_modules, 'section' => $section]);
        ?>
    </div>
<?php endforeach; ?>