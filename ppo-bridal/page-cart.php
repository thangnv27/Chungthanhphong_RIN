<?php
/*
  Template Name: Page Cart
*/
get_header(); ?>
<div id="main">
    <div class="page page-cart">
        <h1 class="page-title"><?php _e('Your Cart'); ?></h1>
        <div class="cartInfo">
            <table>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])):
                        $cart = $_SESSION['cart'];
                        $totalAmount = 0;
                        foreach ($cart as $product) :
                            $totalAmount += $product['amount'];
                            $product_id = $product['id'];
                            $permalink = get_permalink($product_id);
                            $title = get_the_title($product_id);
                            ?>
                            <tr id="product_item_<?php echo $product_id; ?>">
                                <td class="thumb">
                                    <a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>" target="_blank">
                                        <img style="width: 80px" src="<?php echo $product['thumb']; ?>" alt="" />
                                    </a>
                                </td>
                                <td>
                                    <span class="uppercase"><?php echo $title; ?></span><br />
                                    <a href="#" onclick="if (confirm('<?php _e('Are you sure you want to remove this item from your cart?'); ?>')) {
                                           AjaxCart.deleteItem(<?php echo $product_id; ?>);
                                       } return false;" title="<?php _e('Remove this product'); ?>" style="color: #999"><?php _e('Remove'); ?></a>
                                </td>
                                <td class="t_center font14 uppercase">
                                    Color: <?php echo $product['color']; ?><br />
                                    Size: <?php echo $product['size']; ?>
                                </td>
                                <td class="t_center font16"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                                <td class="t_center discount font16">-<?php echo number_format($product['discount'], 0, ',', '.'); ?>%</td>
                                <td class="quantity t_right">
                                    <a href="#" class="plus">+</a>
                                    <input type="text" data-value="<?php echo $product['quantity']; ?>" value="<?php echo $product['quantity']; ?>" class="qtyval" />
                                    <a href="#" class="minus">-</a>
                                    <input type="hidden" id="qtyval" value="<?php echo $product['quantity']; ?>" onchange="AjaxCart.updateItem(<?php echo $product_id; ?>, this.value)" />
                                </td>
                                <td class="t_right font16"><span class="product-subtotal"><?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</span></td>
                            </tr>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="cart-price t_right">
            <span><?php _e('Total Amount') ?>: </span> <span class="total_price t_red"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</span>
        </div>
        <div class="btnCart t_right">
            <a href="<?php echo home_url(); ?>"><?php _e('Continue Shopping'); ?></a>
            <a href="javascript://" onclick="AjaxCart.preCheckout();"><?php _e('Checkout'); ?></a>
        </div>
    </div>
</div>  
<?php get_footer(); ?>