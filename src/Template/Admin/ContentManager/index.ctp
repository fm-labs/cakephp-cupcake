<div class="index content_manager">
    <h2 class="ui header">Contents</h2>

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

</div>

<?php $this->append('script-bottom'); ?>
<script>
$(document).ready(function() {
    $('.tabular.menu .item').tab({
        auto: true,
        path: '<?= $this->Url->build(['controller' => 'ContentManager', 'action' => 'tab']); ?>'
    });
});
</script>
<?php $this->end();