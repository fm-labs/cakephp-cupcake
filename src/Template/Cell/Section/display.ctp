<div class="section" data-section="<?= $section ?>">
    <?php foreach ($page_modules as $pm): ?>
        <?php
        //debug($pm);
        $cell = $this->cell('Banana.ModuleRenderer', ['module' => $pm->module, 'template' => $pm->template]);
        //$cell->template = $pm->template;
        echo $cell;
        ?>
    <?php endforeach; ?>
    <?php foreach ($layout_modules as $pm): ?>
        <?php
        //debug($pm);
        $cell = $this->cell('Banana.ModuleRenderer', ['module' => $pm->module, 'template' => $pm->template]);
        //$cell = $this->cell('Banana.ModuleRenderer', ['module' => $pm->module]);
        echo $cell;
        ?>
    <?php endforeach; ?>
</div>