<?php
/*
  Template Name: Contact
 */
get_header(); ?>

<div id="main">
    <div class="page">
        <div class="main-left">
            <?php echo stripslashes(get_option(SHORT_NAME . "_gmaps")); ?>          
        </div>
        <div class="post">
            <a class="button toggle" id="page-content-toggle" href="#">Toggle</a>
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-content">
                <?php 
                while (have_posts()) : the_post(); 
                    the_content();
                endwhile;
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php get_footer(); ?>