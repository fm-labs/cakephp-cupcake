<?= $this->Html->css('Backend.jstree/themes/default/style.min', ['block' => true]); ?>
<?= $this->Html->css('Backend.filebrowser', ['block' => true]); ?>
<?= $this->Html->script('Backend.underscore-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.backbone-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.jstree/jstree.min', ['block' => true]); ?>
<div class="pages index">

    <div id="pages-container" class="flowui-container">

        <h4 class="ui top attached header flowui-header">
            Pages
        </h4>

        <div class="ui attached segment">
            <div class="ui grid">
                <div class="row">
                    <div class="four wide column">
                        <div id="pages-tree" class="flowui-tree">
                            Loading Pages ...
                        </div>
                    </div>
                    <div class="twelve wide column">
                        <div class="flowui-content-container">
                            Loading Contents ...
                        </div>
                    </div>
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

        $.jstree.defaults.checkbox.three_state = false;
        $.jstree.defaults.checkbox.cascade = 'up+undetermined';

        $.jstree.defaults.dnd.is_draggable = function() { return true; };

        $('#pages-tree')


        /*
            .on('changed.jstree', function (e, data) {
                var i, j, r = [];
                console.log(data);
                if (data.action === "select_node") {
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }
                    //$('.filepicker .folder-selected').html('Selected: ' + r.join(', '));
                    console.log('Selected: ' + r.join(', '));

                    path = r.join('/');
                    console.log('Path: ' + path);

                    var config = '';
                    var url = 'filesData.json?config='+config+'&id='+path;

                    $.ajax({
                        method: 'GET',
                        url: url,
                        dataType: 'json',
                        data: {'selected': r },
                        beforeSend: function() {
                          $('#browser-files').html('<div class="ui active small inline loader"></div>');
                        },
                        success: function(data) {

                            $('#browser-path').html("<i class=\"ui green outline folder icon\" />&nbsp;" + path);

                            // no files in folder
                            if (data.length === 0) {
                                $('#browser-files').html('<div class="ui info message"><i class="info icon"></i>No files in folder ' + path + '</div>');
                                return;
                            }

                            $('#browser-files').html("");

                            var filesView = new FilesView();
                            for(var i in data) {
                                var file = data[i];

                                filesView.appendFile(new File(file));
                            }
                        }
                    });


                }

            })
    */
            .jstree({
                "core" : {
                    "themes" : {
                        //"variant" : "large"
                    },
                    'data' : {
                        'url': function (node) {
                            //console.log(node);
                            return 'treeData.json';
                        },
                        'data': function (node) {
                            console.log(node)
                            return {'id': node.id};
                        },
                    },
                    "check_callback" : true
                },
                "checkbox" : {
                    "keep_selected_style" : false
                },
                "plugins" : [ "wholerow" ] // , "checkbox"
            })

            /*
            .on('move_node.jstree', function (e, data) {
                console.log('Moved');
                console.log(data);
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