<?php get_header(); ?>
<div id="main">
    <div class="categories">
        <div class="categories-header">
            <div class="breadcrumbs">
                <?php
                if (function_exists('bcn_display')) {
                    bcn_display();
                }
                ?>
            </div>
            <?php
                global $wp_query;
            ?>
            <div class="inline uppercase pdl20">
                <?php printf(__("Total %d items"), $wp_query->found_posts); ?>
            </div>
        </div>
        <ul class="grid3 effect-1">
        <?php 
            while (have_posts()) : the_post(); 
            $profileImgs = rwmb_meta( 'product_pictures', array(
                'type' => 'image_advanced',
                'size' => '200x300'
            ));
        ?>
            <li>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
                    <?php 
                        $count = 0;
                        foreach ($profileImgs as $image) : 
                    ?>
                    <img class="<?php echo ($count == 1) ? "image-hidden" : ""; ?>" alt="" src="<?php echo $image['url']; ?>" />
                    <?php 
                            $count += 1;
                        endforeach;
                    ?>
                    <strong>
                        <span class="title"><?php the_title(); ?></span>
                        <span class="price">
                            <span class="old-price-label"></span>
                            <?php echo number_format(floatval(get_post_meta(get_the_ID(), "price", true)), 0, ',', '.'); ?> đ
                        </span>
                    </strong>
                </a>
            </li>
        <?php endwhile;?>
        </ul>
        <?php getpagenavi();?>
    </div>
</div>   
<?php get_footer(); ?>