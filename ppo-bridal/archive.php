<?php get_header(); ?>
<div id="main">
    <div class="archive">
        <h1><?php single_cat_title(); ?></h1>
        <ul class="grid2 effect-1" id="grid2">
            <?php while (have_posts()) : the_post(); ?>
            <li>
                <span class="item">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
                        <img src="<?php get_image_url(); ?>" alt="<?php the_title(); ?>" />
                    </a>
                    <span class="item-meta">
                        <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                    </span>
                </span>
            </li>
            <?php endwhile;?>
        </ul>
    </div>
</div>   
<?php get_footer(); ?>