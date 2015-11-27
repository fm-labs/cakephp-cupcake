<div class="contents form">

    <!-- Toolbar -->
    <div class="be-toolbar">
        <?=
        $this->element('Backend.Menu/menu', [
            'menu' => $this->get('toolbarMenu'),
            'class' => 'ui pointing menu toolbar actions'
        ]);
        ?>
    </div>


    <!-- header -->
    <h2 class="ui header">
        <?= $this->fetch('heading', "Add Content"); ?>
    </h2>

    <!-- content -->
    <?= $this->fetch('content'); ?>

    <?php debug($content); ?>
</div>