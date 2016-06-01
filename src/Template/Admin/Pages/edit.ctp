<?php
//$this->loadHelper('Backend.Tabs');
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __d('banana','Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__d('banana','List {0}', __d('banana','Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','View'), ['action' => 'view', $content->id], ['icon' => 'file']);
$this->Toolbar->addLink(__d('banana','Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'plus']);


// HEADING
$this->assign('heading',$content->title);
$this->assign('title', $content->title);

// CONTENT
?>
<div class="pages form">

    <?= $this->Form->create($content, ['class' => 'no-ajax']); ?>

    <div class="row">
        <div class="col-md-9">

            <?php
            echo $this->Form->input('title');
            echo $this->Form->input('slug');
            echo $this->Form->input('type', ['id' => 'select-type']);
            ?>
            <div class="select-type select-type-redirect select-type-root">
                <?php
                echo $this->Form->input('redirect_location', [
                ]);
                ?>
            </div>
            <div class="select-type select-type-controller select-type-module select-type-cell">
                <?php
                echo $this->Form->input('redirect_controller', [
                ]);
                ?>
            </div>
            <div class="select-type select-type-page select-type-root">
                <?php
                echo $this->Form->input('redirect_page_id', [
                    'options' => $pagesTree,
                    'empty' => __d('banana','No selection')
                ]);
                ?>
            </div>
            <div class="select-type select-type-redirect select-type-controller select-type-page select-type-root">
                <?php
                echo $this->Form->input('redirect_status', [
                    'options' => [301 => 'Permanent (301)', 302 => 'Temporary (302)'],
                    'default' => 302
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-3">

            <?= $this->Form->button(__d('banana','Save Changes'), ['class' => 'save btn btn-primary btn-block']) ?>

            <?= $this->Form->fieldsetStart(['legend' => __('Navigation'), 'collapsed' => true]); ?>
            <?= $this->Form->input('hide_in_nav'); ?>
            <?= $this->Form->input('hide_in_sitemap'); ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __('Publish'), 'collapsed' => true]); ?>
            <?php
            echo $this->Form->input('is_published');
            echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
            echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __('Advanced'), 'collapsed' => true]); ?>
            <?php
            echo $this->Form->input('parent_id',
                ['options' => $pagesTree, 'empty' => '- Root Node -']);
            ?>
            <?php
            echo $this->Form->input('page_layout_id',
                ['empty' => true, 'options' => $pageLayouts, 'data-placeholder' => __('Use default')]);

            if ($content->page_layout_id) {
                echo $this->Html->link('Edit Layout', '#');
            }
            ?>
            <?php
            echo $this->Form->input('page_template',
                //['type' => 'text']
                ['empty' => true, 'options' => $pageTemplates, 'data-placeholder' => __('Use default')]
            );
            ?>
            <?= $this->Form->input('cssid'); ?>
            <?= $this->Form->input('cssclass'); ?>
            <?= $this->Form->fieldsetEnd(); ?>
        </div>
    </div>



    <?= $this->Form->end() ?>
    <!-- EOF PAGE EDIT FORM -->


</div>

