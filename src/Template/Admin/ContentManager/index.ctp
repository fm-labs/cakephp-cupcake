<div class="index content-manager">
    <h2 class="ui header">Contents</h2>

    <!--
    <div class="ui tabular menu">
        <?php foreach ($tabs as $id => $tab): ?>
            <?php
            echo sprintf('<div class="item" data-tab="%s">%s</div>', $id, $tab['title']);
            ?>
        <?php endforeach; ?>
    </div>

    <?php foreach ($tabs as $id => $tab): ?>
        <div class="ui tab" data-tab="<?= $id ?>">
            <h2 class="ui header"><?= $tab['title'] ?></h2>
        </div>
    <?php endforeach; ?>
    -->


        <table class="ui sortable table" data-sort-url="<?= $this->Url->build(['action' => 'tree_sort']) ?>">
            <tbody>
            <?php foreach ($contents as $content): ?>
                <tr data-id="<?= h($content->id) ?>">
                    <td><?= $this->Html->link($pagesTree[$content->id], ['action' => 'edit', $content->id]); ?></td>
                    <td><?= h($content->type); ?></td>
                    <td><?= h($content->layout_template); ?></td>
                    <td><?= $this->Url->build($content->url); ?></td>
                    <td class="actions">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php debug($pagesTree); ?>




</div>

<?php $this->append('script-bottom'); ?>
<script>
$(document).ready(function() {
    /*
    $('.tabular.menu .item').tab({
        auto: true,
        path: '<?= $this->Url->build(['controller' => 'ContentManager', 'action' => 'tab']); ?>'
    });
    */
});
</script>
<?php $this->end();