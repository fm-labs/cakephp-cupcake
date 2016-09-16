<?php
/**
 * Extendable HTML view template
 * A two-column template using jsTree as tree navigation and triggers jquery's ajax
 * to load html contents into the content column
 *
 * Parameters:
 * @param dataUrl string Url to fetch tree data json
 * @param viewUrl string Url to fetch tree node content
 * @param jsTree array jsTree params. See jsTree documentation for options
 * @link https://www.jstree.com/
 */
$dataUrl = $this->get('dataUrl', ['action' => 'treeData']);
$viewUrl = $this->get('viewUrl', ['action' => 'treeView']);

$defaultJsTree = [
    'core' => [
        'data' => [
            'url' => $this->Html->Url->build($dataUrl)
        ],
        'check_callback' => true,
    ],
    'plugins' => ['wholerow', 'state']
];
$jsTree = $this->get('jsTree', $defaultJsTree);
?>
<?= $this->Html->css('Backend.jstree/themes/backend/style.min', ['block' => true]); ?>
<?= $this->Html->script('Backend.jstree/jstree.min', ['block' => true]); ?>
<div class="index index-tree">

    <?php if ($this->fetch('heading')): ?>
        <div class="page-heading">
            <h1><?= $this->fetch('heading'); ?></h1>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="panel panel-primary panel-nopad">
                <?php if ($this->fetch('treeHeading')): ?>
                <div class="panel-heading">
                    <?= $this->fetch('treeHeading'); ?>
                </div>
                <?php endif; ?>

                <div class="panel-body">
                    <?= $this->Html->div('be-index-tree', 'Loading Pages ...', [
                        'id' => 'index-tree',
                        'data-url' => $this->Html->Url->build($dataUrl)
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
            <div id="index-tree-container">
                <?= $this->fetch('content'); ?>
            </div>
        </div>
    </div>

    <div id="index-tree-noview" style="display: none">
        <?= $this->fetch('noview', '<div class="alert alert-info">Nothing found</div>'); ?>
    </div>

</div>
<script>
    var jsTreeConf;
</script>
<?php
/**
 * Inject custom jsTree json config from the extending template
 * Example script, which can be inserted in the extending template:
 * <?php $this->start('jsTreeScript'); ?>
 * <script>
 * jsTreeConf = {
 *  core: {
 *    data: {
 *      data: function(node) {
 *        return { 'id': node.id };
 *      }
 *    }
 *  }
 * }
 * </script>
 * <?php $this->end(); ?>
 */
$this->fetch('jsTreeScript');
?>
<script>

    jsTreeConf = jsTreeConf || JSON.parse('<?= json_encode($jsTree); ?>');

    if (!jsTreeConf.core || !jsTreeConf.data || !jsTreeConf.data.data) {
        jsTreeConf.core.data.data = function (node) {
            return {'id': node.id};
        };
    }


    $(document).ready(function() {

        var selected = {};
        var path;
        var $tree = $('#index-tree');
        var $container = $('#index-tree-container');
        var $noview = $('#index-tree-noview');

        $.jstree.defaults.checkbox.three_state = false;
        $.jstree.defaults.checkbox.cascade = 'up+undetermined';

        $.jstree.defaults.dnd.is_draggable = function() { return true; };

        $tree
            .on('changed.jstree', function (e, data) {
                var i, j, r = [];
                //console.log(data);
                if (data.action === "select_node") {
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }

                    //console.log('Selected: ' + r.join(', '));

                    var config = '';
                    //var url = $tree.data('viewUrl') + '?id=' + r.join(',');
                    var url = data.node.data.viewUrl;

                    if (url) {

                        $.ajax({
                            method: 'GET',
                            url: url,
                            dataType: 'html',
                            data: {'selected': r },
                            beforeSend: function() {
                                Backend.Loader.show();
                                Backend.Util.saveScrollPosition('jstree', 0);
                            },
                            complete: function() {
                                Backend.Loader.hide();
                                Backend.Util.restoreScrollPosition();
                            },
                            success: function(data) {

                                // no files in folder
                                if (data.length === 0) {
                                    $container.html($noview.html());
                                    return;
                                }

                                $container.html(data);
                                Backend.beautify();
                            }
                        });
                    }

                }

            })

            .jstree(jsTreeConf);

        /*
         .on('move_node.jstree', function (e, data) {
         console.log('Moved');
         console.log(data);

         var movedId = data.node.id;

         var movedB
         });
         */

        /*
         $(document)

         .on('dnd_scroll.vakata', function (e, data) {
         console.log("dnd_scroll");
         console.log(data);
         })


         .on('dnd_start.vakata', function (e, data) {
         console.log("dnd_start");
         console.log(data);
         })

         .on('dnd_stop.vakata', function (e, data) {
         console.log("dnd_stop");
         console.log(data);
         })
         */

    });
</script>