<?php $this->Html->addCrumb(__('Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Post'))); ?>
<?php $this->extend('/Admin/Content/edit'); ?>
<?php
// TOOLBAR
$toolbarMenu = [
    'delete' => [
        'title' => __('Delete'),
        'url' => ['action' => 'delete', $content->id],
        'attr' => ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)],
        '_children' => [],
    ],
    'list' => [
        'title' => __('List {0}', __('Posts')),
        'url' => ['action' => 'index'],
        'attr' => ['icon' => 'list']
    ],
    'add' => [
        'title' => __('Add'),
        'url' => false,
        'attr' => [],

        '_children' => [
            [
                'title' => __('New {0}', __('Content Module')),
                'url' => ['action' => 'add_module'],
                'attr' => ['icon' => 'add']
            ]
        ]

    ]
];

$this->set('toolbarMenu', $toolbarMenu);
$this->assign('heading', __('Edit {0}', __('Post')));
?>
<div class="posts">
    <?= $this->Form->create($content); ?>
    <div class="ui grid">
        <div class="twelve wide column">
            <div class="ui form">
                <?php
                echo $this->Form->input('title');
                echo $this->Form->input('slug');
                echo $this->Form->input('subheading');
                echo $this->Form->input('teaser_html', ['type' => 'htmleditor']);
                echo $this->Form->input('teaser_link_caption');
                echo $this->Form->input('teaser_link_href');
                echo $this->Form->input('body_html', ['type' => 'htmleditor']);
                ?>
            </div>


        </div>
        <div class="four wide column">
            <!--
            <h5 class="ui top attached header">Actions</h5>
            -->
            <div class="ui attached basic right aligned segment form">
                <?= $this->Form->button(__('Save Changes'), ['class' => 'ui positive fluid button']) ?>
            </div>
            <h5 class="ui attached header">Publish</h5>
            <div class="ui attached segment form">
                <?php
                echo $this->Form->input('is_published');
                echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
                echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
                ?>
            </div>
            <h5 class="ui attached header">Layout</h5>
            <div class="ui attached segment form">
                <?php
                echo $this->Form->input('template');
                echo $this->Form->input('cssclass');
                echo $this->Form->input('cssid');
                ?>
            </div>

            <h5 class="ui attached header">Image</h5>
            <div class="ui attached segment form">
                <?php
                if ($content->image_file) {
                    echo h($content->image_file) . '<br />';
                    echo $this->Html->image($content->image_file->url, ['width' => 200]);
                }
                //echo $this->Form->input('image_file');
                echo $this->Html->link(
                    __('Change image'),
                    [
                        'controller' => 'Attachments',
                        'action' => 'select',
                        'refmodel' => 'Banana.Posts',
                        'refid' => $content->id,
                        'scope' => 'image'
                    ],
                    [
                        'class' => 'modal attachment-select'
                    ]
                );
                ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <div class="ui hidden divider"></div>
    <div class="upload form">
        <h4 class="ui top attached header">Primary Image</h4>
        <div class="ui attached segment">
            <?= $this->Form->create($content, ['type' => 'file']); ?>
            <?php

            if ($content->image_file) {
                echo h($content->image_file) . '<br />';
                echo $this->Html->image($content->image_file->url, ['width' => 200]);
            }
            echo $this->Form->input('image_file_upload', ['type' => 'file', 'label' => false]);

            //$this->Form->addWidget('upload', ['Banana\\View\\Widget\\UploadWidget', 'html', 'form']);
            //echo $this->Form->input('image_upload', ['type' => 'upload']);
            ?>
            <?= $this->Form->button(__('Upload image')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>


</div>
