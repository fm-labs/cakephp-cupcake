<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Post'))); ?>
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

<div class="row">
    <div class="col-md-9">

        <?php
        echo $this->Form->input('title');
        echo $this->Form->input('slug');
        echo $this->Form->input('subheading');
        ?>
        <?= $this->Form->fieldsetStart(['legend' => 'Teaser', 'collapsed' => !($content->use_teaser || $content->teaser_html)]);  ?>
        <?php
        echo $this->Form->input('use_teaser');
        echo $this->Form->input('teaser_html', [
            'type' => 'htmleditor',
            'editor' => $editor
        ]);
        echo $this->Form->input('teaser_link_caption');
        echo $this->Form->input('teaser_link_href');
        ?>
        <?= $this->Form->fieldsetEnd(); ?>

        <?php
        echo $this->Form->input('body_html', [
            'type' => 'htmleditor',
            'editor' => $editor
        ]);
        ?>

        <?= $this->Form->fieldsetStart(['legend' => 'Meta', 'collapsed' => true]); ?>

        <?= $this->Form->input('meta_title'); ?>
        <?= $this->Form->input('meta_desc'); ?>
        <?= $this->Form->input('meta_keywords'); ?>
        <?= $this->Form->input('meta_lang'); ?>
        <?= $this->Form->input('meta_robots'); ?>

        <?= $this->Form->fieldsetEnd(); ?>
    </div>
    <div class="col-md-3">

        <?= $this->Form->button(__d('banana','Save Changes')) ?>

        <?= $this->Form->fieldsetStart(['legend' => 'Publish']); ?>
        <?php
        echo $this->Form->input('is_published');
        echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
        echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
        ?>
        <?= $this->Form->fieldsetEnd(); ?>

        <?= $this->Form->fieldsetStart(['legend' => 'Layout', 'collapsed' => true]); ?>
        <?php
        echo $this->Form->input('teaser_template', ['empty' => '- Default -']);
        echo $this->Form->input('template', ['empty' => '- Default -']);
        ?>
        <?= $this->Form->fieldsetEnd(); ?>

        <?= $this->Form->fieldsetStart(['legend' => 'Media', 'collapsed' => true]); ?>
        <?= $this->cell('Media.ImageSelect', [[
            'label' => 'Teaser Image',
            'model' => 'Banana.Posts',
            'id' => $content->id,
            'scope' => 'teaser_image_file',
            'image' => $content->teaser_image_file,
            'imageOptions' => ['width' => 200]
        ]]); ?>

        <?= $this->cell('Media.ImageSelect', [[
            'label' => 'Primary Image',
            'model' => 'Banana.Posts',
            'id' => $content->id,
            'scope' => 'image_file',
            'image' => $content->image_file,
            'imageOptions' => ['width' => 200]
        ]]); ?>


        <?= $this->cell('Media.ImageSelect', [[
            'label' => 'Additional Images',
            'model' => 'Banana.Posts',
            'id' => $content->id,
            'scope' => 'images_file',
            'multiple' => true,
            'image' => $content->images_file,
            'imageOptions' => ['width' => 200]
        ]]); ?>
        <?= $this->Form->fieldsetEnd(); ?>



        <?= $this->Form->fieldsetStart(['legend' => 'Advanced', 'collapsed' => true]); ?>
        <div class="ui attached segment form">
            <?php
            echo $this->Form->input('refscope');
            echo $this->Form->input('refid');
            echo $this->Form->input('cssclass');
            echo $this->Form->input('cssid');
            echo $this->Form->input('order');
            ?>
        </div>
        <?= $this->Form->fieldsetEnd(); ?>
    </div>
</div>
<?= $this->Form->end() ?>