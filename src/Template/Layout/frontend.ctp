<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?> | Banana
    </title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>


    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->Html->css('Banana.frontend'); ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="page-before">
        <?= $this->section('before'); ?>
    </div>

    <div id="page">
        <header id="header">
            <div class="ui one column page grid">
                <div class="column">
                    <h1 class="ui header"><?= $this->Html->link(\Cake\Core\Configure::read('Banana.site.title'), '/'); ?></h1>
                </div>
            </div>
        </header>

        <div id="main">
            <div id="flash">
                <?= $this->Flash->render() ?>
            </div>

            <div id="content-container">

                <div class="ui page two column grid">

                    <div id="left" class="four wide column">
                        <?= $this->section('left') ?>
                    </div>

                    <div id="content" class="twelve wide column">
                        <?= $this->fetch('content') ?>
                    </div>

                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <footer id="footer">
            <div class="ui one column page grid">
                <div class="column">
                    <?= $this->section('footer') ?>
                </div>
            </div>
        </footer>
    </div>
    <div id="page-after">
        <?= $this->section('after'); ?>
    </div>

    <?= $this->Html->script('Banana.jquery-1.11.2.min'); ?>
    <?= $this->Html->script('SemanticUi.semantic.min'); ?>
    <?= $this->fetch('script-bottom'); ?>
    <script>
        $('.menu-list .ui.dropdown')
            .dropdown()
        ;
    </script>
</body>
</html>
