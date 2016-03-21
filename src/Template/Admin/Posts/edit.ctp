<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Post'))); ?>
<?php $this->extend('/Admin/Content/edit'); ?>
<?php $this->loadHelper('Backend.Tabs'); ?>
<?php
// TOOLBAR

$this->Toolbar->addPostLink([
    'title' => __d('banana','Delete'),
    'url' => ['action' => 'delete', $content->id],
    'attr' => ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)],
]);
$this->Toolbar->addLink([
    'title' => __d('banana','List {0}', __d('banana','Posts')),
    'url' => ['action' => 'index'],
    'attr' => ['icon' => 'list']
]);

$this->assign('heading', __d('banana','Edit {0}', __d('banana','Post')));
$this->assign('title', sprintf('%s [%s #%s]', $content->title, 'Post', $content->id));

// HtmlEditor config
$editor = \Cake\Core\Configure::read('Banana.HtmlEditor.default');
$editor['body_class'] = $content->cssclass;
$editor['body_id'] = $content->cssid;
?>
<?= $this->Form->create($content); ?>
<?php
echo $this->Form->input('title');
echo $this->Form->input('slug');
echo $this->Form->input('subheading');
echo $this->Form->input('use_teaser');
echo $this->Form->input('teaser_html', [
    'type' => 'htmleditor',
    'editor' => $editor
]);
echo $this->Form->input('teaser_link_caption');
echo $this->Form->input('teaser_link_href');

echo $this->Form->input('body_html', [
    'type' => 'htmleditor',
    'editor' => $editor
]);
?>

<fieldset>
    <legend>Meta</legend>
    <div>
        <?= $this->Form->input('meta_title'); ?>
        <?= $this->Form->input('meta_desc'); ?>
        <?= $this->Form->input('meta_keywords'); ?>
        <?= $this->Form->input('meta_lang'); ?>
        <?= $this->Form->input('meta_robots'); ?>
    </div>
</fieldset>

    <h5 class="ui attached header">Publish</h5>
<?php
echo $this->Form->input('is_published');
echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
?>

    <h5 class="ui attached header">Layout</h5>
<?php
echo $this->Form->input('teaser_template', ['empty' => '- Default -']);
echo $this->Form->input('template', ['empty' => '- Default -']);
?>


    <h5 class="ui attached header">Teaser Image</h5>
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
    echo $this->Ui->link(
        __d('banana','Select Image'),
        ['action' => 'setImage', $content->id, 'scope' => 'teaser_image_file' ],
        ['class' => 'iframe-modal', 'icon' => 'folder open outline']
    );
    ?>
    <?php
    echo $this->Ui->link(
        __d('banana','Remove Image'),
        ['action' => 'deleteImage', $content->id, 'scope' => 'teaser_image_file' ],
        ['icon' => 'remove circle']
    );
    ?>

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
        echo $this->Ui->link(
            __d('banana','Select Image'),
            ['action' => 'setImage', $content->id, 'scope' => 'image_file' ],
            ['class' => 'iframe-modal', 'icon' => 'folder open outline']
        );
        ?>
        <?php
        echo $this->Ui->link(
            __d('banana','Remove Image'),
            ['action' => 'deleteImage', $content->id, 'scope' => 'image_file' ],
            ['icon' => 'remove circle']
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
        echo $this->Ui->link(
            __d('banana','Select Images'),
            ['action' => 'setImage', $content->id, 'scope' => 'image_files', 'multiple' => true ],
            ['class' => 'iframe-modal', 'icon' => 'folder open outline']
        );
        ?>
        <?php
        echo $this->Ui->link(
            __d('banana','Remove Images'),
            ['action' => 'deleteImage', $content->id, 'scope' => 'image_files' ],
            ['icon' => 'remove circle']
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
        echo $this->Form->input('order');
        ?>
    </div>

<?= $this->Form->button(__d('banana','Save Changes')) ?>
<?= $this->Form->end() ?>