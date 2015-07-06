<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<div id="container">
    <div id="header">
        <h1><?= $this->fetch('title') ?></h1>
    </div>
    <div id="content">
        <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>
    </div>
    <div id="footer">
    </div>
</div>
</body>
</html>
