<div class="galleries view">
    <div class="flexslider">
        <ul class="slides">
            <?php for ($i = 1; $i <= 9; $i++): ?>
                <li>
                    <div class="ui grid">
                        <div class="row">
                            <div class="twelve wide column">
                                <?= $this->Html->image('/media/gallery/lederleitner/slider/beschattung_' . $i . '.jpg', [
                                    'alt' => 'naturstein_lederleitner_' . $i . '.jpg',
                                    'title' => 'naturstein_lederleitner_' . $i . '.jpg',
                                ]); ?>
                            </div>
                            <div class="four wide column">
                                <?= $gallery->desc_html; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endfor; ?>
        </ul>

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
                //$('.slider_kopf').show();
            }
        });

    });
</script>