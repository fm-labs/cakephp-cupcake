<div class="contents form">

    <!-- header -->
    <h2 class="ui header">
        <?= $this->fetch('heading', "Edit Content"); ?>
    </h2>

    <!-- content -->
    <?= $this->fetch('content'); ?>

    <!-- available modules -->
    <div class="ui hidden divider"></div>
    <div class="ui segment">
        <ul>
            <?php foreach ($this->get('modulesAvailable') as $aModule): ?>
                <li><?php
                    $url = ['action' => 'add_content_module', 'content_id' => $content->id, 'module' => $aModule];
                    echo $this->Ui->link(__('Add {0} Module', $aModule), $url, ['icon' => 'add']);
                    ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php debug($content); ?>
</div>