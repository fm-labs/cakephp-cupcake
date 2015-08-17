<?php $this->Html->addCrumb(__('Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($post->title); ?>
<div class="posts view">
    <?php echo $this->module('Banana.Posts/ViewPost', ['post' => $post]); ?>
</div>
