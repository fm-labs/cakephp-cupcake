<?= $this->extend('/Admin/Backend/default'); ?>
<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'add']);
$this->Toolbar->addLink(__d('banana','Repair'), ['action' => 'repair'], ['icon' => 'configure']);

$this->assign('heading', __('Pages'));
?>
<?= $this->Html->css('Backend.jstree/themes/default/style.min', ['block' => true]); ?>
<?= $this->Html->script('Backend.jstree/jstree.min', ['block' => true]); ?>
<style>
    #pages-container {
        border-left: 1px solid #e8e8e8;
        padding-left: 1em;
    }
</style>
<div class="pages index">


    <div class="container-fluid">

        <h2><?= __('Pages'); ?></h2>

        <div class="row">
            <div class="col-md-3">


                <?= $this->Html->div('flowui-tree', 'Loading Pages ...', [
                    'id' => 'pages-tree',
                    'data-tree-url' => $this->Html->Url->build(['action' => 'treeData']),
                    'data-view-url' => $this->Html->Url->build(['action' => 'treeView'])
                ]); ?>
            </div>
            <div class="col-md-9">
                <div id="pages-container">
                    Select a page
                </div>
            </div>
        </div>
    </div>

</div>


<script>

    $(document).ready(function() {
        //return;

        var selected = {};
        var path;
        var $tree = $('#pages-tree');
        var $container = $('#pages-container');

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
                    //$('.filepicker .folder-selected').html('Selected: ' + r.join(', '));
                    //console.log('Selected: ' + r.join(', '));


                    var config = '';
                    var url = $tree.data('viewUrl') + '?id=' + r.join(',');

                    $.ajax({
                        method: 'GET',
                        url: url,
                        dataType: 'html',
                        data: {'selected': r },
                        beforeSend: function() {
                          $container.html('<div class="ui active small inline loader"></div>');
                        },
                        success: function(data) {

                            // no files in folder
                            if (data.length === 0) {
                                $container.html('<div class="ui info message"><i class="info icon"></i>Nothing found</div>');
                                return;
                            }

                            $container.html(data);
                        }
                    });

                }

            })

            .jstree({
                "core" : {
                    "themes" : {
                        "variant" : "large"
                    },
                    'data' : {
                        'url': function (node) {
                            //console.log(node);
                            //console.log($tree.data('treeUrl'));
                            return $tree.data('treeUrl');
                        },
                        'data': function (node) {
                            //console.log(node)
                            return {'id': node.id};
                        },
                    },
                    "check_callback" : true
                },
                "checkbox" : {
                    "keep_selected_style" : false
                },
                "plugins" : [ "wholerow", "state" ] // , "checkbox", "dnd"
            })

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