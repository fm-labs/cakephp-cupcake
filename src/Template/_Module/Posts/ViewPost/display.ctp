<div class="post">
    <div class="datetime">
        <?= h($post->created); ?>
    </div>
    <div>Perma URL: <?= h($post->perma_url) ?></div>
    <h1><?= $this->Html->link($post->title, $post->url) ?></h1>
    <div class="contents">
        <?php echo $this->element('Banana.Content/content_modules', [
            'contentModules' => $post->content_modules
        ]); ?>
    </div>

    <h3>Comments</h3>
</div>