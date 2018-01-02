<!-- script references -->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.DEPreLoad.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        setTimeout(function(){
            $("#depreload .wrapper").animate({ opacity: 1 });
        }, 400);

        setTimeout(function(){
            $("#depreload .logo").animate({ opacity: 1 });
        }, 800);

        var canvas  = $("#depreload .line")[0],
            context = canvas.getContext("2d");

        context.beginPath();
        context.arc(75, 75, 54, Math.PI * 1.5, Math.PI * 1.6);
        context.strokeStyle = '#fff';
        context.lineWidth = 5;
        context.stroke();
        
        var loader = jQuery("body").DEPreLoad({
            // callbacks
            OnStep: function(percent) {
                $("#depreload .line").animate({ opacity: 1 });

                if (percent > 5) {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.beginPath();
                    context.arc(75, 75, 54, Math.PI * 1.5, Math.PI * (1.5 + percent / 50), false);
                    context.stroke();
                }
            },
            OnComplete: function() {
                setTimeout(function (){
                    $("#depreload").fadeOut('slow');
                }, 1500);
            }
        });
    });
</script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.dlmenu.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.imagefit.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.touchSwipe.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.carouFredSel.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.panzoom.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.prettyPhoto.js"></script>
<?php if(is_home()): ?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.hoverIntent.min.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/disable-copy.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/custom.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/ppo.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/app.js"></script>
<?php if(is_page_template( 'page-slideshow.php' )): ?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/slideshow.js"></script>
<?php endif; ?>
<?php if(is_archive() and !is_tax()): ?>
<!--Archive Load Item-->
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/masonry.pkgd.min.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/imagesloaded.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/classie.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/AnimOnScroll.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //Load Item masonry
        new AnimOnScroll(document.getElementById('grid2'), {
            minDuration: 0.4,
            maxDuration: 0.7,
            viewportFactor: 0.2
        });
    });
</script>
<?php endif; ?>
<?php if(is_singular( 'product' )): ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".color-list img").each(function (){
            $(this).click(function (){
                $(".color-list img").removeClass('active');
                $(this).addClass('active');
                $(".color-selected").attr({
                    'data-id': $(this).attr('data-id'),
                    'data-text': $(this).attr('alt')
                });
                $(".color-selected span").text($(this).attr('alt'));
            });
        });
        $(".size-list span").each(function (){
            $(this).click(function (){
                $(".size-list span").removeClass('active');
                $(this).addClass('active');
                $(".size-selected").attr({
                    'data-id': $(this).attr('data-id'),
                    'data-text': $(this).text()
                });
                $(".size-selected span").text($(this).text());
            });
        });
    });
</script>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>