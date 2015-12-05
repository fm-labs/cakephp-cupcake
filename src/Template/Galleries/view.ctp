<div class="gallery view slider-default">
    <div class="flexslider">
        <ul class="slides">
            <?php foreach ($gallery->images as $image): ?>
                <li>
                    <?= $this->Html->image($image, [
                        'alt' => basename($image),
                        'title' => basename($image)
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="description">
        <?= $gallery->desc_html; ?>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
        $('.flexslider').flexslider({
            controlNav: false,
            initDelay: 0,
            slideshowSpeed: 5000,
            animationSpeed: 600,
            start: function(){
            }
        });

    });
</script>