<div class="contents form">

    <!-- Toolbar
    <div class="be-toolbar">
        <?=
        $this->element('Backend.Menu/menu', [
            'menu' => $this->get('toolbarMenu'),
            'class' => 'ui secondary pointing menu toolbar actions'
        ]);
        ?>
    </div>
    -->


    <!-- header -->
    <h2 class="ui header">
        <?= $this->fetch('heading', "Add / Edit Content"); ?>
    </h2>

    <!-- content -->
    <?= $this->fetch('content'); ?>

    <!-- pagination -->
    <div class="paginator" style="margin: 1.33em 0;">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>


</div>
