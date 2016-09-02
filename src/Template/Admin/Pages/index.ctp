<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','{0} (Table)', __d('banana','Pages')), ['action' => 'table'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'plus', 'class' => 'link-frame-modal']);
$this->Toolbar->addLink(__d('banana','Repair'), ['action' => 'repair'], ['icon' => 'wrench']);

$this->extend('/Admin/Base/index_jstree_ajax');
//$this->assign('heading', __('Pages'));


$dataUrl = ['action' => 'treeData'];
$viewUrl = ['action' => 'treeView'];

$this->set('dataUrl', $dataUrl);
$this->set('viewUrl', $viewUrl);

$this->assign('treeHeading', __('Pages'));

?>
<?= __('Loading'); ?>&nbsp;...