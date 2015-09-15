<?php
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __('Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__('List {0}', __('Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__('New {0}', __('Content Module')), ['action' => 'add_module'], ['icon' => 'add']);
$this->Toolbar->addLink(__('Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);


// HEADING
$this->assign('heading', __('Edit Page: {0}', $content->title));

// CONTENT
?>
<div class="pages">
    <?= $this->Form->create($content); ?>
    <div class="ui grid">
        <div class="twelve wide column">
            <div class="ui form">
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
                        'options' => $pagesTree
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
            <div class="ui divider"></div>

            <div class="select-type select-type-content">
                <h4 class="ui header">Contents</h4>
                <?php echo $this->element(
                    'Banana.Admin/Content/list_content_modules_editable',
                    ['contentModules' => $content->content_modules, 'section' => '']);
                ?>
            </div>

            <div>
                <div class="ui divider"></div>
                <?php foreach($sections as $section): ?>
                    <h4 class="ui header">Section: <?= h($section); ?></h4>
                    <?php echo $this->element(
                        'Banana.Admin/Content/list_content_modules_editable',
                        ['contentModules' => $content->content_modules, 'section' => $section]);
                    ?>

                <?php endforeach; ?>
            </div>

            <div class="">
                <ul>
                    <?php foreach ($this->get('modulesAvailable') as $aModule): ?>
                        <li><?php
                            $url = ['action' => 'add_content_module', 'content_id' => $content->id, 'module' => $aModule];
                            echo $this->Ui->link(__('Add {0} Module', $aModule), $url, ['icon' => 'add']);

                            foreach($sections as $section) {
                                $url = ['action' => 'add_content_module', 'content_id' => $content->id, 'module' => $aModule, 'section' => $section];
                                echo ' | ';
                                echo $this->Ui->link($section, $url);
                            }

                            ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
        <div class="four wide column">
            <div class="ui attached basic right aligned segment form">
                <?= $this->Form->button(__('Save Changes'), ['class' => 'ui positive fluid button']) ?>
            </div>
            <h5 class="ui attached header">Publish</h5>
            <div class="ui attached secondary segment form">
                <?php
                echo $this->Form->input('is_published');
                echo $this->Form->input('publish_start_date');
                echo $this->Form->input('publish_end_date');
                ?>
            </div>
            <h5 class="ui attached header">Structure</h5>
            <div class="ui attached secondary segment form">
                <?php
                echo $this->Form->input('parent_id',
                    ['options' => $pagesTree, 'empty' => '- Root Node -']);
                ?>
            </div>
            <h5 class="ui attached header">Layout</h5>
            <div class="ui attached secondary segment form">
                <?php
                echo $this->Form->input('page_layout_id',
                    ['empty' => __('- Default Layout -'), 'options' => $pageLayouts]);

                if ($content->page_layout_id) {
                    echo $this->Html->link('Edit Layout', '#');
                }
                ?>
                <?php
                echo $this->Form->input('page_template',
                    ['empty' => __('- Default Template -'), 'options' => $pageTemplates]);
                ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<?php $this->append('script-bottom'); ?>
    <script>
        $(document).ready(function() {
            $('.select-type').hide();
            $('#select-type').change(function() {
                var type = $(this).val();
                $('.select-type').fadeOut();
                $('.select-type-' + type).fadeIn();
            }).trigger('change');
        })
    </script>
<?php $this->end(); ?>