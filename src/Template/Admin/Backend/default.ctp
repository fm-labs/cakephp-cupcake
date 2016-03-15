<?= $this->Html->css('Banana.backend', ['block' => true]); ?>
<?= $this->Html->script('Backend.underscore-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.backbone-min', ['block' => true]); ?>
<?= $this->Html->css('Backend.jstree/themes/default/style.min', ['block' => true]); ?>
<?= $this->Html->script('Backend.jstree/jstree.min', ['block' => true]); ?>
<?= $this->fetch('content'); ?>
