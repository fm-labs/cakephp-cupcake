<style>
    .post p {
        font-size: 24px;
        line-height: 1.55em;
    }
</style>
<div class="posts index">
    <?php foreach ($posts as $post): ?>
        <?php echo $this->element('Banana.Posts/post_item', ['post' => $post]); ?>
        <div class="ui divider"></div>
    <?php endforeach; ?>

    <!--
    <div class="paginator">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>
    -->

    <?php debug($posts->toArray()); ?>
</div>