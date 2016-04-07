<?php $this->loadHelper('Banana.Content'); ?>
<div class="related posts">

    <?php if (count($posts) < 1): ?>
    No posts found
    <?php endif; ?>

    <?php foreach($posts as $post): ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $this->Html->link($post->title,
                    ['controller' => 'Posts', 'action' => 'edit', $post->id],
                    ['class' => 'link-frame']
                ); ?>

                <?= $this->Ui->statusLabel($post->is_published, ['label' => [__('Unpublished'), __('Published')]]); ?>
            </div>

            <div class="panel-body">
                <h4 class="">Teaser</h4>
                <div class="description">
                    <?= $this->Content->userHtml($post->teaser_html); ?>
                </div>
            </div>

            <div class="panel-body">
                <h4 class="">Content</h4>
                <div class="description">
                    <?= $this->Content->userHtml($post->body_html); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="actions">
        <?= $this->Html->link(
            __('Add {0}', __('Post')),
            ['controller' => 'Posts', 'action' => 'add', 'refscope' => 'Banana.Pages',  'refid' => $content->id],
            ['class' => 'ui default button']); ?>
    </div>

    <?php debug($posts); ?>
</div>
<script>
    $(document).on('click', '.related.posts .panel-body a', function(e) {
        e.preventDefault();

        $(this).attr("target", "_blank");
        window.open($(this).attr("href"));

        return false;
    });
</script>