<div class="ui form">
<?= $this->Form->create($content); ?>
    <?php
    echo $this->Form->input('image_file', [
        'type' => 'imageselect',
        'options' => $imageFiles,
        'class' => 'grouped',
        'id' => 'imagepicker-select',
        'value' => $content->image_file->path
    ]); ?>

<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>
</div>


<?php $this->append('scriptBottom'); ?>
<script>

$('#imagepicker-select').imagepicker({
    show_label: false,
    initialized: function() {

        $(this)[0].picker.addClass('grouped');

        /*
         $(this)[0].picker.find('img.image_picker_image').each(function() {
         var $label = $(this).next('p');
         if ($label.length > 0) {
         $(this).attr('title', $label.html());
         }
         });
         */
    }
});

</script>
<?php $this->end(); ?>