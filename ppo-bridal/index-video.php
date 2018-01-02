<?php 
/*
  Template Name: Home Video
 */

get_header(); ?>

<div id="main">
    <?php while(have_posts()) : the_post(); ?>
        <div class="homepage-video">
            <?php
            if(wp_is_mobile()){
                the_content();
            } else {
            ?>
            <div id="video_player">Đang tải...</div>
            <?php } ?>
        </div>
        <?php if(!wp_is_mobile()): ?>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jwplayer/jwplayer.js"></script>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jwplayer/jwplayer.html5.js"></script>
        <script type="text/javascript">
            jwplayer.key = "58TBujyyCUP+cEmwMmC6hv6KhP7bJJgI//VkMU65FYnU09bZUz+BbVB4+5L58ZCY";
            var playing = false;
            
            function setCookie(c_name,value,expiredays) {
                var exdate=new Date();
                exdate.setDate(exdate.getDate()+expiredays);
                document.cookie=c_name+ "=" +escape(value)+ ((expiredays==null) ? "" : ";expires="+exdate.toUTCString());
            }
            function rememberPosition() {
                if (jwplayer().getState() == "IDLE") {
                    setCookie("mv_<?php the_ID(); ?>", 0,-1);
                } else {
                    setCookie("mv_<?php the_ID(); ?>", Math.round(jwplayer().getPosition()),7);
                    setTimeout("rememberPosition()", 1000); 
                }
            }
            function resizePlayerDiv(){
                if(jQuery(window).width() <= 1038){
                    jQuery("#video_player_wrapper").height(jQuery(window).height() - 45);
                } else {
                    jQuery("#video_player_wrapper").height('100%');
                }
            }
            function loadVideo(){
                player = jwplayer("video_player").setup({
                    skin: "<?php bloginfo('stylesheet_directory'); ?>/js/jwplayer/vapor.xml",
                    file: "<?php echo trim(strip_tags(get_the_content())); ?>",
                    start: <?php echo intval($_COOKIE['mv_' . get_the_ID()]); ?>,
                    primary: 'flash',
                    autostart: true,
                    repeat: true,
                    controls: false,
                    stretching: "fill",
                    width: "100%",
                    height: '100%',
                    events: {
                        onPause: function(e) {
                            playing = false;
                            setTimeout("rememberPosition()", 1000);
                        }, 
                        onPlay: function(e) {
                            playing = true;
                            setTimeout("rememberPosition()", 1000);
                        },
                        onComplete: function(e) {
                            setCookie("mv_<?php the_ID(); ?>", 0,-1);
                        },
                        onReady: function (e){
                            resizePlayerDiv();
                            jwplayer('video_player').seek(<?php echo intval($_COOKIE['mv_' . get_the_ID()]); ?>);
                        },
                        onDisplayClick: function (){
                            if(playing)
                                player.pause();
                            else
                                player.play();
                        }
                    }
                });
            }
            function detectmobile() { 
                if( navigator.userAgent.match(/Android/i)
                || navigator.userAgent.match(/webOS/i)
                || navigator.userAgent.match(/iPhone/i)
                || navigator.userAgent.match(/iPad/i)
                || navigator.userAgent.match(/iPod/i)
                || navigator.userAgent.match(/BlackBerry/i)
                || navigator.userAgent.match(/Windows Phone/i)){
                    return true;
                }
                else {
                    return false;
                }
            }

            if(detectmobile()){
                window.location = "<?php echo trim(strip_tags(get_the_content())); ?>";
            }else{
                loadVideo();
            }

            (function ($){
                $(window).bind('resize', function (){
                    resizePlayerDiv();
                });
            })(jQuery);
        </script>
        <?php endif; ?>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>