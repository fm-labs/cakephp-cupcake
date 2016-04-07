<div class="contents form">

    <!-- header -->
    <h2 class="header">
        <?= $this->fetch('heading', "Add Content"); ?>
    </h2>

    <!-- content -->
    <?= $this->fetch('content'); ?>

    <?php debug($content); ?>
</div>