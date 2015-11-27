<?php foreach ($contentModules as $contentModule): ?>
    <div class="content-module">
        <?php
        $cell = $this->cell('Banana.ModuleRenderer', ['module' => $contentModule->module]);
        echo $cell;
        ?>
    </div>
<?php endforeach; ?>