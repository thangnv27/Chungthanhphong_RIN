<?php get_header(); ?>

<div id="main">
    <div class="page">
        <?php
        while (have_posts()) : the_post();
        
        $profileImgs = rwmb_meta( 'product_pictures', array(
            'type' => 'image_advanced',
            'size' => '80x120'
        ));
        $images = rwmb_meta( 'product_images', array(
            'type' => 'image_advanced',
            'size' => 'full_url'
        ));
        ?>
        <div class="main-left product-left big-image">
            <div id="slide-carousel">
                <?php
                foreach ($images as $image) {
                    echo <<<HTML
                    <img class="slide-item panzoom" src="{$image['url']}" />
HTML;
                }
                ?>
            </div>
            <span class="view-image">Click To View Larger</span>
            <span class="close-btn">Close</span>
            <a id="prevslide"></a>
            <a id="nextslide"></a>
        </div>
        <div class="post product">
            <a class="button toggle" id="page-content-toggle" href="#">Toggle</a>
            <div class="breadcrumbs">
                <?php
                if (function_exists('bcn_display')) {
                    bcn_display();
                }
                ?>
            </div>
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div>
                <span class="price"><?php echo number_format(floatval(get_post_meta(get_the_ID(), "price", true)), 0, ',', '.'); ?> đ</span>
            </div>
            <div class="color-list">
                <?php
                $colors = get_the_terms( $post->ID, 'product_color' );
                if ($colors && !is_wp_error($colors)) {
                    $color_img = "";
                    foreach ( $colors as $term ) {
                        $tag_meta = get_option("tag_{$term->term_id}");
                        $color_img .= '<img src="' . $tag_meta['img'] . '" alt="' . $term->name . '" data-id="' . $term->term_id . '" />';
                    }
                    echo $color_img;
                }
                ?>
            </div>
            <span class="error color-error"></span>
            <div class="color-selected" data-id="0" data-text="<?php _e('None'); ?>">
                <?php _e('Color'); ?>: <span><?php _e('None'); ?></span>
            </div>
            <div class="size-list">
                <?php
                $sizes = get_the_terms( $post->ID, 'product_size' );
                if ($sizes && !is_wp_error($sizes)) {
                    $size_txt = "";
                    foreach ( $sizes as $term ) {
                        $tag_meta = get_option("tag_{$term->term_id}");
                        $size_txt .= '<span data-id="' . $term->term_id . '">' . $term->name . '</span>';
                    }
                    echo $size_txt;
                }
                ?>
            </div>
            <span class="error size-error"></span>
            <div class="size-selected" data-id="0" data-text="<?php _e('None'); ?>">
                <?php _e('Size') ?>: <span><?php _e('None'); ?></span>
            </div>
            <div class="product-status">
                Status: 
                <span><?php
                $tinh_trang = get_post_meta(get_the_ID(), "tinh_trang", true);
                switch ($tinh_trang) {
                    case "in_stock":
                        echo '<span>In stock</span>';
                        break;
                    case "out_of_stock":
                        echo '<span>Out of stock</span>';
                        break;
                    case "coming_soon":
                        echo '<span>Coming soon</span>';
                        break;
                    default:
                        break;
                }
                ?></span>
            </div>
            <?php if (in_array($tinh_trang, array("in_stock", "coming_soon"))): ?>
            <div class="mb20">
                <button class="btnAddToCart" onclick="AjaxCart.addToCart(<?php the_ID(); ?>, '<?php echo $profileImgs[key($profileImgs)]['url']; ?>', '<?php the_title(); ?>', <?php echo get_post_meta(get_the_ID(), "price", true); ?>, 1, '');"><?php _e('Add to cart'); ?></button>
            </div>
            <?php endif; ?>
            <div class="post-content"><?php the_content(); ?></div>
        </div>
        <?php endwhile; ?>
        <div class="clearfix"></div>
    </div>
</div>

<?php get_footer(); ?>