<html>
<head>
    <title>[Module Iframe]</title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>
</head>
<body>
<?= $this->fetch('content'); ?>


<?= $this->Backend->jquery(); ?>
<?= $this->fetch('script-bottom'); ?>
</body>
</html>