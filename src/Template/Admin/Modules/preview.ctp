<?php
use Cake\Core\Configure;

Configure::write('debug', false);
debug($this->request->query);
debug($modulePath);
debug($moduleParams);
?>

<?php echo $this->cell($modulePath . 'Module' , [], ['params' => $moduleParams]); ?>
