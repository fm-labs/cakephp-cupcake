<div class="contents form">

    <!-- header -->
    <h2 class="ui header">
        <?= $this->fetch('heading', "Add / Edit Content"); ?>
    </h2>

    <!-- content -->
    <?= $this->fetch('content'); ?>

    <!-- pagination -->
    <div class="paginator" style="margin: 1.33em 0;">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__d('banana','previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('banana','next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>


</div>
