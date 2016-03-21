<?php $this->loadHelper('Backend.Tabs'); ?>
<div class="contents form container-fluid">

    <!-- header
    <h2 class="ui header">
        <?= $this->fetch('heading', "Edit Content"); ?>
    </h2>
     -->


    <!-- content -->
    <?= $this->fetch('content'); ?>
    <?php ///debug($content); ?>
</div>