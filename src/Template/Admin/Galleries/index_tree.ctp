<?php $this->Html->addCrumb(__d('banana','Galleries')); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','{0} (Table)', __d('banana','Galleries')), ['action' => 'indexTable'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Gallery')), ['action' => 'add'], ['icon' => 'plus']);

$this->extend('/Admin/Base/index_jstree_ajax');

//$this->set('dataUrl', ['action' => 'treeData']);
//$this->set('viewUrl', ['action' => 'treeView']);

$this->assign('treeHeading', __d('banana','Galleries'));

?>
<?= __('Loading {0}', __d('banana','Galleries')); ?>&nbsp;...