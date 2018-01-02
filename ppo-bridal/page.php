<?php get_header(); ?>

<div id="main">
    <div class="page">
        <?php while (have_posts()) : the_post(); ?>
        <div class="main-left">
            <div id="slide-carousel">
                <?php
                $args = array(
                    'type' => 'image_advanced',
                    //'size' => 'full_url'
                );
                $images = rwmb_meta( 'page_images', $args );
                foreach ($images as $image) {
                    echo <<<HTML
                    <img class="slide-item no-width" src="{$image['url']}" style="position: relative ! important; width: 1066px; margin-left: -3px;" />
HTML;
                }
                ?>
            </div>
            <a id="prevslide"></a>
            <a id="nextslide"></a>
        </div>
        <div class="post">
            <a class="button toggle" id="page-content-toggle" href="#">Toggle</a>
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-content">
                <?php the_content(); ?>
                <?php show_share_socials(); ?>
            </div>
        </div>
        <?php endwhile; ?>
        <div class="clearfix"></div>
    </div>
</div>

<?php get_footer(); ?>