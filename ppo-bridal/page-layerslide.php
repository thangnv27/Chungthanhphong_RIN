<?php
/*
  Template Name: Layer Slide
 */
get_header(); ?>

<div id="main">
    <div class="page-layerslider">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>