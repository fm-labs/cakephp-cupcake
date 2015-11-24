<?php $this->Html->script('Banana.jquery.flexslider-min'); ?>
<?php $this->Html->css('Banana.flexslider/flexslider', ['block' => true]); ?>
<!-- flex -->
<style type="text/css">
    #slider_kopf { width:100%; height:500px; overflow:hidden; vertical-align: middle; text-align: center;  }
    #slider_kopf li { list-style-image:url();display:none }
    #slider_kopf img { position:absolute; top:0px; left:0px }
</style>


<div id="slider_kopf">

    <div class="flexslider">

        <ul class="slides">
            <?php foreach ($images as $imageUrl): ?>
            <li>
                <div>
                    <?= $this->Html->image($imageUrl); ?>
                </div>
            </li>
            <?php endforeach; ?>
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
                    $('.slider_kopf').show();
                }
            });

        });
    </script>

<?php debug($images); ?>