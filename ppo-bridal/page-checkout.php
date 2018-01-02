<?php
/*
  Template Name: Page Checkout
*/

get_header();
global $current_user;
get_currentuserinfo();
$cities = vn_city_list();
?>
<div id="main">
    <div class="page page-cart">
        <h1 class="page-title pdl10"><?php _e('Checkout'); ?></h1>
        <form action="" method="post" id="frmOrder">
            <input type="hidden" name="action" value="orderComplete" />
            <input type="hidden" name="locale" value="<?php echo getLocale(); ?>" />
            <div class="checkout">
                <div class="customer-info">
                    <div class="customer">
                        <div class="title"><?php _e('Customer Information') ?></div>
                        <div class="form-group">
                            <input name="cName" type="text" placeholder="<?php _e('Fullname') ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->display_name : ""; ?>" />
                        </div>
                        <div class="form-group">
                            <input name="cEmail" type="text" placeholder="<?php _e('E-mail address') ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->user_email : ""; ?>" />
                        </div>
                        <div class="form-group">
                            <input name="cPhone" type="text" placeholder="<?php _e('Phone number') ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_phone', $current_user->ID)) : ""; ?>" />
                        </div>
                        <div class="form-group">
                            <input name="cAddress" type="text" placeholder="<?php _e('Address') ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_address1', $current_user->ID)) : ""; ?>" />
                        </div>
                        <div class="form-group">
                            <select name="cCity" class="form-control">
                                <?php
                                foreach ($cities as $city) {
                                    if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                        echo "<option value=\"$city\" selected>$city</option>";
                                    } else {
                                        echo "<option value=\"$city\">$city</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="ship" value="0" />
                            <input type="checkbox" id="ship" name="ship" />
                            <label for="ship"><?php _e('Other shipping address') ?></label>
                        </div>
                    </div>
                    <div class="shiping" id="divShip" style="display: none">
                        <div class="title"><?php _e('Shipping Information') ?></div>
                        <div class="form-group">
                            <input name="shipName" class="form-control" type="text" placeholder="<?php _e('Fullname') ?>"/>
                        </div>
                        <div class="form-group">
                            <input name="shipEmail" class="form-control" type="text" placeholder="<?php _e('E-mail address') ?>"/>
                        </div>
                        <div class="form-group">
                            <input name="shipPhone" class="form-control" type="text" placeholder="<?php _e('Phone number') ?>"/>
                        </div>
                        <div class="form-group">
                            <input name="shipAddress" class="form-control" type="text" placeholder="<?php _e('Address') ?>"/>
                        </div>
                        <div class="form-group">
                            <select name="shipCity" class="form-control">
                                <?php
                                foreach ($cities as $city) {
                                    if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                        echo "<option value=\"$city\" selected>$city</option>";
                                    } else {
                                        echo "<option value=\"$city\">$city</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="cart-info">
                    <div class="payment-info">
                        <div class="title"><?php _e('Payment Method') ?></div>
                        <div class="PaymentMethod">
                            <div class="PaymentMethod_Name">
                                <input type="radio" name="payment_method" value="<?php _e('Payment on delivery') ?>" id="ck1" checked>
                                <label for="ck1"><?php _e('Payment on delivery') ?></label>
                            </div>
                            <div class="PaymentMethod_Info" id="method1">
                                <?php echo stripslashes(get_option('payment_cashOnDelivery')); ?>
                            </div>
                        </div>
                        <div class="PaymentMethod">
                            <div class="PaymentMethod_Name">
                                <input type="radio" name="payment_method" value="<?php _e('Contact support') ?>" id="ck2">
                                <label for="ck2"><?php _e('Contact support') ?></label>
                            </div>
                            <div class="PaymentMethod_Info" id="method2" style="display: none;">
                                <?php echo stripslashes(get_option('payment_atOffice')); ?>
                            </div>
                        </div>
                        <div class="PaymentMethod">
                            <div class="PaymentMethod_Name">
                                <input type="radio" name="payment_method" value="<?php _e('Online payment') ?>" id="ck3">
                                <label for="ck3"><?php _e('Online payment') ?></label>
                            </div>
                            <div class="PaymentMethod_Info" id="method3" style="display: none;">
                                <?php echo stripslashes(get_option('payment_atNganLuong')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="cartCheckout">
                        <table style="width: 100%">
                            <tbody>
                                <?php
                                if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])):
                                    $cart = $_SESSION['cart'];
                                    $totalAmount = 0;
                                    foreach ($cart as $product) :
                                        $totalAmount += $product['amount'];
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="uppercase"><?php echo get_the_title($product['id']); ?></span><br />
                                                <span class="cart-meta">
                                                    Color: <?php echo $product['color']; ?><br />
                                                    Size: <?php echo $product['size']; ?><br />
                                                    Quantity: <?php echo $product['quantity']; ?><br />
                                                    Discount: <?php echo number_format($product['discount'], 0, ',', '.'); ?>%
                                                </span>
                                            </td>
                                            <td class="t_right"><?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</td>
                                        </tr>
                                    <?php 
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="t_right">
                        <div class="cartInfo-price">
                            <span><?php _e('Total') ?>:</span>
                            <span class="totalAmount"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ
                        </div>
                        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $totalAmount; ?>" />
                        <div class="btnCart">
                            <a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>"><?php _e('Back to Cart'); ?></a>
                            <a href="javascript://" id="btnNganLuong" style="display: none"><?php _e('Process To Payment'); ?></a>
                            <a href="javascript://" id="btnMuaHang"><?php _e('Process To Payment'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
        <!--END PAGE CHECKOUT-->
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#ship').change(function(){
                    if(this.checked){
                        $('#divShip').fadeIn('fast');
                        $('input[name=ship]').val(1);
                    }else{
                        $('#divShip').fadeOut('normal');
                        $('input[name=ship]').val(0);
                    }
                });
                $(window).load(function(){
                    if($('#ship').get(0).checked){
                        $('#divShip').fadeIn('fast');
                        $('input[name=ship]').val(1);
                    }else{
                        $('#divShip').fadeOut('normal');
                        $('input[name=ship]').val(0);
                    }
                    $("#ck1").click();
                });

                /* switch payment method */
                $("#ck1").click(function () {
                    $("#method1").show();
                    $("#method2").hide();
                    $("#method3").hide();
                    $("#btnNganLuong").hide();
                    $("#btnMuaHang").show();
                });
                $("#ck2").click(function () {
                    $("#method1").hide();
                    $("#method2").show();
                    $("#method3").hide();
                    $("#btnNganLuong").hide();
                    $("#btnMuaHang").show();
                });
                $("#ck3").click(function () {
                    $("#method1").hide();
                    $("#method2").hide();
                    $("#method3").show();
                    $("#btnNganLuong").show();
                    $("#btnMuaHang").hide();
                });

                // Complete order
                $("#btnMuaHang").click(function () {
                    if (validate_info()) {
                        $("#frmOrder input[name=action]").val('orderComplete');
                        AjaxCart.orderComplete($("#frmOrder").serialize());
                    } else {
                        displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
                    }
                });
                $("#btnNganLuong").click(function(){
                    if(validate_info()){
                        $("#frmOrder input[name=action]").val('orderNganLuong');
                        AjaxCart.orderNganLuong($("#frmOrder").serialize());
                    }else{
                        displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
                    }
                    return false;
                });

                function validate_info() {
                    var valid = true;
                    $(".customer input[type=text], .customer select").each(function () {
                        if ($(this).val().length == 0) {
                            $(this).parent().addClass('has-error');
                            valid = false;
                        } else {
                            $(this).parent().removeClass('has-error');
                        }
                    });
                    if ($('#ship').is(":checked")) {
                        $(".shiping input[type=text], .shiping select").each(function () {
                            if ($(this).val().length == 0) {
                                $(this).parent().addClass('has-error');
                                valid = false;
                            } else {
                                $(this).parent().removeClass('has-error');
                            }
                        });
                    } else {
                        $(".shiping input[type=text], .shiping select").each(function () {
                            $(this).parent().removeClass('has-error');
                        });
                    }
                    return valid;
                }
            });
        </script>
    </div>
</div>
<?php get_footer(); ?>