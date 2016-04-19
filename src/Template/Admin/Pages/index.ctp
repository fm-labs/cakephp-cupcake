<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','{0} (Table)', __d('banana','Pages')), ['action' => 'table'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'plus']);
$this->Toolbar->addLink(__d('banana','Repair'), ['action' => 'repair'], ['icon' => 'wrench']);

$this->extend('Backend./Admin/Base/Tree/index');
$this->assign('heading', __('Pages'));
?>
<?= __('Select a page'); ?>