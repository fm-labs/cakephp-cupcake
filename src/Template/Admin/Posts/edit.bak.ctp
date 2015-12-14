<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Post'))); ?>
<div class="posts">
    <div class="be-toolbar actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __d('banana','Delete'),
                ['action' => 'delete', $post->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $post->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __d('banana','List {0}', __d('banana','Posts')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="setting icon"></i>Actions
                    <div class="menu">
                                <div class="item">No Actions</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($post); ?>
    <h2 class="ui top attached header">
        <?= __d('banana','Edit {0}', __d('banana','Post')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('title');
                echo $this->Form->input('slug');
                echo $this->Form->input('subheading');
                echo $this->Form->input('teaser');
                echo $this->Form->input('body_html');
                echo $this->Form->input('image_file');
                echo $this->Form->input('is_published');
                //echo $this->Form->input('publish_start_datetime');
                //echo $this->Form->input('publish_end_datetime');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>