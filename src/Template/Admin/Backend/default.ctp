<?= $this->Html->css('Banana.backend', ['block' => true]); ?>
<?= $this->Html->script('Backend.underscore-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.backbone-min', ['block' => true]); ?>
<div style="padding: 0 1em; text-align: right;">
    <?= $this->Html->link(__('Open in new window'), $this->request->url, ['target' => '_blank']); ?>
</div>
<?= $this->fetch('content'); ?>
