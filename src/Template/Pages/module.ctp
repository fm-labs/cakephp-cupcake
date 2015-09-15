<?php
debug($moduleName);

//debug($pm);
$cell = $this->cell('Banana.ModuleRenderer::named', ['moduleName' => $moduleName, 'template' => $moduleTemplate]);
//$cell->template = $pm->template;
echo $cell;
