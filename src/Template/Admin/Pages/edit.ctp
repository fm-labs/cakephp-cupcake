<?php
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __('Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__('List {0}', __('Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__('Add {0}', __('Content Module')), ['action' => 'add_module'], ['icon' => 'add']);
$this->Toolbar->addLink(__('Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);
$this->Toolbar->addLink(__('New {0}', __('Page')), ['action' => 'add'], ['icon' => 'add']);


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
                        'options' => $pagesTree,
                        'empty' => __('No selection')
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

                <fieldset class="collapsed">
                    <legend>Meta</legend>
                    <div>
                        <?= $this->Form->input('meta_desc'); ?>
                        <?= $this->Form->input('meta_keywords'); ?>
                        <?= $this->Form->input('meta_lang'); ?>
                        <?= $this->Form->input('meta_robots'); ?>
                    </div>
                </fieldset>


                <fieldset class="collapsed">
                    <legend>Navigation</legend>
                    <div>
                        <?= $this->Form->input('hide_in_nav'); ?>
                        <?= $this->Form->input('hide_in_sitemap'); ?>
                    </div>
                </fieldset>

            </div>
            <div class="ui divider"></div>

            <!-- RELATED POSTS -->
            <h3>Related Posts</h3>
            <div class="related posts">
                <table class="ui compact table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th class="actions">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($content->posts as $post): ?>
                        <tr>
                            <td><?= h($post->title); ?></td>
                            <td class="actions">
                                <?= $this->Ui->link('Edit',
                                    ['controller' => 'Posts', 'action' => 'edit', $post->id],
                                    ['class' => 'ui mini button', 'icon' => 'edit']
                                ); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="actions">
                    <?= $this->Ui->link('Add Post',
                        ['controller' => 'Posts', 'action' => 'add', 'refid' => $content->id, 'refscope' => 'Banana.Pages'],
                        ['class' => 'ui button', 'icon' => 'add']
                    ); ?>
                </div>

            </div>
            <div class="ui divider"></div>

            <!-- RELATED CONTENT MODULES -->
            <div class="content-modules be-tabs">
                <h3>Related content modules</h3>
                <div class="ui top attached tabular menu">
                    <?php foreach($sections as $section): ?>
                        <a class="item" data-tab="tab-<?= $section ?>"><?= \Cake\Utility\Inflector::humanize($section); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php foreach($sections as $section): ?>
                    <div class="ui bottom attached tab segment" data-tab="tab-<?= $section; ?>">
                        <?php echo $this->element(
                            'Banana.Admin/Content/list_content_modules_editable',
                            ['contentModules' => $content->content_modules, 'section' => $section]);
                        ?>
                    </div>
                <?php endforeach; ?>
                <br />
                <?= $this->Ui->link('Build a new module for this page', [
                    'controller' => 'ModuleBuilder',
                    'action' => 'build2',
                    'refscope' => 'Banana.Pages',
                    'refid' => $content->id
                ], ['class' => 'ui button', 'icon' => 'add']); ?>

            </div>


            <!--
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
            -->

        </div>
        <div class="four wide column">
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
            <h5 class="ui attached header">Structure</h5>
            <div class="ui attached segment form">
                <?php
                echo $this->Form->input('parent_id',
                    ['options' => $pagesTree, 'empty' => '- Root Node -']);
                ?>
            </div>
            <h5 class="ui attached header">Layout</h5>
            <div class="ui attached segment form">
                <?php
                echo $this->Form->input('page_layout_id',
                    ['empty' => __('- Default Layout -'), 'options' => $pageLayouts]);

                if ($content->page_layout_id) {
                    echo $this->Html->link('Edit Layout', '#');
                }
                ?>
                <?php
                echo $this->Form->input('page_template',
                    ['type' => 'text']
                    //['empty' => __('- Default Template -'), 'options' => $pageTemplates]
                );
                ?>
                <?= $this->Form->input('cssid'); ?>
                <?= $this->Form->input('cssclass'); ?>
            </div>

        </div>
    </div>
    <?= $this->Form->end() ?>

    <div class="ui divider"></div>

    <h3>Link existing module</h3>
    <div class="ui form">
        <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
        <?= $this->Form->input('refscope', ['default' => 'Banana.Pages']); ?>
        <?= $this->Form->input('refid', ['default' => $content->id]); ?>
        <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
        <?= $this->Form->input('section'); ?>
        <?= $this->Form->submit('Link module'); ?>
        <?= $this->Form->end(); ?>
    </div>


</div>

<?php $this->append('scriptBottom'); ?>
    <script>
        /*
        $(document).ready(function() {

            //$('.content-modules .menu .item:first-child').addClass('active');
            //$('.content-modules .tab:first-child').addClass('active');
            $('.content-modules .menu .item').tab();
            $('.content-modules .menu .item:first-child').trigger('click');

            $('.select-type').hide();
            $('#select-type').change(function() {
                var type = $(this).val();
                $('.select-type').fadeOut();
                $('.select-type-' + type).fadeIn();
            }).trigger('change');
        })
        */
    </script>
<?php $this->end(); ?>