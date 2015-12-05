<?php if (!isset($image_url)) {
    debug("No image selected");
    return;
}
?>
<?= $this->Html->image($image_url); ?>
