<div class="post">
    <div class="datetime">
        <?= h($post->created); ?>
    </div>
    <h1><?= $this->Html->link($post->title, ['controller' => 'Posts', 'action' => 'view', 'id' => $post->id, 'slug' => $post->slug]) ?></h1>
    <div class="contents">
        <?php echo $this->element('Banana.Content/content_modules', [
            'contentModules' => $post->content_modules
        ]); ?>
    </div>
</div>