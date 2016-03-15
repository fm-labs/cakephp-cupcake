<div class="related posts">

    <?php if (count($posts) < 1): ?>
    No posts found
    <?php endif; ?>

    <?php foreach($posts as $post): ?>

        <!-- -->
        <div class="ui fluid card">
            <div class="content">

                <?= $this->Html->link($post->title,
                    ['controller' => 'Posts', 'action' => 'edit', $post->id],
                    ['class' => 'left floated header']
                ); ?>

                <span class="right floated edit">
                    <i class="edit icon"></i>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Posts', 'action' => 'edit', $post->id]); ?>
                </span>

            </div>

            <div class="extra">
                <span class="ui left floated">
                    <?= $this->Ui->statusLabel($post->is_published, ['label' => [__('Unpublished'), __('Published')]]); ?>
                </span>
                <!--
                <?php if ($post->is_published): ?>
                    <span class="right floated">
                        <i class="hide icon"></i>
                        <?= $this->Html->link(__('Unpublish'), ['controller' => 'Posts', 'action' => 'publish', $post->id]); ?>
                    </span>
                <?php else: ?>
                    <span class="right floated">
                        <i class="green unhide icon"></i>
                        <?= $this->Html->link(__('Publish'), ['controller' => 'Posts', 'action' => 'unpublish', $post->id]); ?>
                    </span>
                <?php endif; ?>
                -->
            </div>

            <div class="content">
                <h5 class="">Teaser</h5>
                <div class="description">
                    <?= strip_tags($post->teaser_html); ?>
                </div>
            </div>

            <div class="content">
                <h5 class="">Content</h5>
                <div class="description">
                    <?= strip_tags($post->body_html); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="actions">
        <?= $this->Html->link(
            __('Add {0}', __('Post')),
            ['controller' => 'Posts', 'action' => 'add', 'page_id' => $content->id],
            ['class' => 'ui default button']); ?>
    </div>

    <?php debug($posts); ?>
</div>