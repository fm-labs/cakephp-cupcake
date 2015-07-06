<?php $this->Html->addCrumb(__('Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Page'))); ?>
<div class="pages">
    <?php foreach ($page->page_modules as $pageModule): ?>
    <div class="ui segment">
        <?php echo $this->cell('Banana.ModuleEditor', ['module' => $pageModule->module]); ?>
    </div>
    <?php endforeach; ?>

    <div class="ui hidden divider"></div>
    <div class="ui segment">
        <ul>
        <?php foreach ($availableModules as $aModule): ?>
        <li><?php
            $url = ['action' => 'add_page_module', 'page' => $page->id, 'module' => $aModule];
            echo $this->Ui->link(__('Add {0} Module', $aModule), $url, ['icon' => 'add']);
        ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <?php debug($availableModules); ?>
</div>