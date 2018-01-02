<?php
/*
  Template Name: SlideShow
 */
get_header(); ?>

<div id="main">
    <div class="page-slideshow">
        <div id="fp_gallery" class="fp_gallery">
            <img src="" alt="" class="fp_preview" style="display:none;"/>
            <!--<div class="fp_overlay"></div>-->
            <div id="fp_loading" class="fp_loading"></div>
            <div id="fp_next" class="fp_next"></div>
            <div id="fp_prev" class="fp_prev"></div>
            <div id="outer_container">
                <div id="thumbScroller">
                    <div class="container">
                        <?php
                        while (have_posts()) : the_post();
                            $args = array(
                                'type' => 'image_advanced',
                                //'size' => 'full_url'
                            );
                            $images = rwmb_meta( 'page_images', $args );
                            foreach ($images as $image) {
                                echo <<<HTML
                                <div class="content">
                                    <div><a href="#"><img src="{$image['url']}" alt="{$image['full_url']}" class="thumb" /></a></div>
                                </div>
HTML;
                            }
                        endwhile;
                        ?>
                        </div>
                </div>
            </div>
            <div id="fp_thumbtoggle" class="fp_thumbtoggle"><?php echo _e('View Thumbs'); ?></div>
        </div>
    </div>
</div>

<?php get_footer(); ?>