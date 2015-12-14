<div class="posts index">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="datetime">
                <?= h($post->created); ?>
            </div>
            <h1><?= $this->Html->link($post->title, ['controller' => 'Posts', 'action' => 'view', 'id' => $post->id, 'slug' => $post->slug]) ?></h1>
            <div>Perma URL: <?= $this->Html->link($post->perma_url) ?></div>
            <div class="contents">
                <?php echo $this->element('Banana.Content/content_modules', [
                    'contentModules' => $post->content_modules
                ]); ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!--
    <div class="paginator">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__d('banana','previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('banana','next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>
    -->

    <?php debug($posts->toArray()); ?>
</div>