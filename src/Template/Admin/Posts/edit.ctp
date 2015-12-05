<?php $this->Html->addCrumb(__('Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Post'))); ?>
<?php $this->extend('/Admin/Content/edit'); ?>
<?php
// TOOLBAR

$this->Toolbar->addPostLink([
    'title' => __('Delete'),
    'url' => ['action' => 'delete', $content->id],
    'attr' => ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)],
]);
$this->Toolbar->addLink([
    'title' => __('List {0}', __('Posts')),
    'url' => ['action' => 'index'],
    'attr' => ['icon' => 'list']
]);

$this->assign('heading', __('Edit {0}', __('Post')));
?>
<div class="posts">
    <?php if (isset($content->refscope)): ?>
    <div class="ref" style="margin: 0.5em 0;">
        <?= $this->Html->link(__('This post is linked to {0}', $content->reftitle), $content->refurl); ?>
    </div>
    <?php endif; ?>

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
                echo $this->Form->input('body_html', [
                    'type' => 'htmleditor',
                    'editor' => [
                        'image_list_url' => ['controller' => 'Data', 'action' => 'editorImageList'],
                        'link_list_url' => ['controller' => 'Data', 'action' => 'editorLinkList'],
                    ]
                ]);
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
                echo $this->Form->input('teaser_template');
                echo $this->Form->input('template');
                ?>
            </div>

            <h5 class="ui attached header">Teaser Image</h5>
            <div class="ui attached segment form">
                <?php
                if ($content->teaser_image_file) {
                    echo $this->Html->image($content->teaser_image_file->url, ['width' => 200]) . '<br />';
                    echo h($content->teaser_image_file->basename) . '<br />';
                }

                /*
                echo $this->Form->input('teaser_image_file', [
                    'type' => 'imageselect', 'options' => '@default'
                ]);
                */

                ?>
                <?php
                echo $this->Html->link(
                    __('Select Image'),
                    ['action' => 'setImage', $content->id, 'scope' => 'teaser_image_file' ]
                );
                ?>
                <?php
                echo $this->Html->link(
                    __('Remove Image'),
                    ['action' => 'deleteImage', $content->id, 'scope' => 'teaser_image_file' ]
                );
                ?>


            </div>

            <h5 class="ui attached header">Primary Image</h5>
            <div class="ui attached segment form">
                <?php
                if ($content->image_file) {
                    echo $this->Html->image($content->image_file->url, ['width' => 200]) . '<br />';
                    echo h($content->image_file->basename) . '<br />';
                }

                /*
                echo $this->Form->input('image_file', [
                    'type' => 'imageselect', 'options' => '@default'
                ]);
                */

                ?>
                <?php
                echo $this->Html->link(
                    __('Select Image'),
                    ['action' => 'setImage', $content->id, 'scope' => 'image_file' ]
                );
                ?>
                <?php
                echo $this->Html->link(
                    __('Remove Image'),
                    ['action' => 'deleteImage', $content->id, 'scope' => 'image_file' ]
                );
                ?>


            </div>

            <h5 class="ui attached header">Additional Images</h5>
            <div class="ui attached segment form">
                <?php
                if ($content->image_files) {
                    foreach ($content->image_files as $imageFile) {
                        echo $this->Html->image($imageFile->url, ['width' => 200]) . '<br />';
                        echo h($imageFile->basename) . '<br />';
                    }
                }

                /*
                echo $this->Form->input('image_files', [
                    'type' => 'imageselect', 'options' => '@default'
                ]);
                */

                ?>
                <?php
                echo $this->Html->link(
                    __('Select Images'),
                    ['action' => 'setImage', $content->id, 'scope' => 'image_files', 'multiple' => true ]
                );
                ?>
                <?php
                echo $this->Html->link(
                    __('Remove Images'),
                    ['action' => 'deleteImage', $content->id, 'scope' => 'image_files' ]
                );
                ?>


            </div>


            <h5 class="ui attached header">Advanced</h5>
            <div class="ui attached segment form">
                <?php
                echo $this->Form->input('refscope');
                echo $this->Form->input('refid');
                echo $this->Form->input('cssclass');
                echo $this->Form->input('cssid');
                ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>


</div>

<div class="ui modal" id="imagepicker-modal">
    <i class="close icon"></i>
    <div class="header">
        Select image
    </div>
    <div class="content" style="overflow: scroll; max-height: 500px;">
    </div>
    <div class="actions">
        <div class="ui black deny button">
            <?= __('Cancel'); ?>
        </div>
        <div class="ui approve button">
            <?= __('Ok'); ?>
        </div>
    </div>
</div>

<?php $this->append('scriptBottom'); ?>
<script>
    /*
    $('#set-image').click(function (e) {
        e.preventDefault();
        $('#imagepicker-modal .content')
            .html(
                $('<iframe>', { src: $(this).attr('href'), id: 'imagepicker-iframe' })
            );

        $('#imagepicker-modal')
            .modal({
                closable: false,
                onVisible: function() {
                    $('#imagepicker-iframe').height('500px').width($('#imagepicker-modal').width());
                },
                onDeny: function() {
                    console.log("denied");
                },
                onApprove: function(el) {
                    console.log("approved");
                }
            })
            .modal('show')
        ;
    });
    */
</script>
<?php $this->end(); ?>