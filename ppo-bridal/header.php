<?php //include_once 'libs/bbit-compress.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta http-equiv="Cache-control" content="no-store; no-cache"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="author" content="chungthanhphong.com" />
    <meta name="robots" content="index, follow" /> 
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <meta name="geo.region" content="VN" />
    <meta name="geo.position" content="14.058324;108.277199" />
    <meta name="ICBM" content="14.058324, 108.277199" />

<!--    <meta name="viewport" content="initial-scale=1.0" />-->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="keywords" content="<?php echo get_option('keywords_meta') ?>" />

<!--    <link rel="publisher" href="https://plus.google.com/+ThịnhNgô"/>-->
    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />        
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/dlmenu.min.css"/>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.mCustomScrollbar.min.css"/>
    <?php if(is_page_template( 'page-slideshow.php' )): ?>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/slideshow.min.css"/>
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/wp-default.css"/>
    <link href="<?php bloginfo('stylesheet_directory'); ?>/css/common.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/prettyPhoto.min.css" />
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var siteUrl = "<?php bloginfo('siteurl'); ?>";
        var themeUrl = "<?php bloginfo('stylesheet_directory'); ?>";
        var is_home = <?php echo is_home() ? 'true' : 'false'; ?>;
        var is_product = <?php echo is_singular('product') ? 'true' : 'false'; ?>;
        var no_image_src = themeUrl + "/images/no_image_available.jpg";
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        var cartUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>";
        var checkoutUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCheckoutID")); ?>";
        var lang = "<?php echo getLocale(); ?>";
    </script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/modernizr.js"></script>
    <style type="text/css">
        #depreload {
            background-color: #ccc;
            background-size: cover;
            position: fixed;
            text-align: center;
            z-index: 999;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: table;
        }
        #depreload .wrapper{
            display: table-cell;
            vertical-align: middle;
        }
        #depreload .circle {
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            box-shadow: 0 0 1px 0 rgb(255, 255, 255);
            height: 110px;
            margin: 0 auto;
            position: relative;
            width: 110px;
        }
        #depreload .line {
            margin: -20px;
            opacity: 0;
        }
        #depreload .logo {
            width: 102px;
            height: 102px;
            left: 50%;
            top: 50%;
            margin-left: -51px;
            margin-top: -51px;
            opacity: 0;
            position: absolute;
            background: url('<?php echo get_option("logo_preload"); ?>') no-repeat center;
        }
    </style>
    
    <?php
    if (is_singular())
        wp_enqueue_script('comment-reply');

    wp_head();
    ?>
</head>
<body>
    <!--<div id="loader"></div>-->
    <div id="depreload">
        <div class="wrapper">
            <div class="circle">
                <canvas class="line" width="150px" height="150px"></canvas>
                <span class="logo"></span>
            </div>
        </div>
    </div>
    
    <div id="ajax_loading" style="display: none;z-index: 99999" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!--Alert Message-->
    <div id="nNote" class="nNote" style="display: none;"></div>
    <!--END: Alert Message-->
    
    <nav id="navigation">
        <?php 
            wp_nav_menu( array(
                'container' => '',
                'theme_location' => 'primary',
                'menu_class'     => 'fancy-rollovers wf-mobile-hidden',
                'menu_id'        => 'main-nav',
            )); 
        ?>
         <a href="javascript://" rel="nofollow" id="mobile-menu">
            <span class="menu-open">MENU</span>
            <span class="menu-close">CLOSE</span>
            <span class="menu-back">BACK</span>
            <span class="wf-phone-visible">&nbsp;</span>
        </a>
    </nav>
    
    <div id="menu" style="left: 0px;">
        <a class="top" href="<?php echo home_url(); ?>"><?php _e('Home') ?></a>
        <span class="close"><?php _e('Hide menu') ?></span>

        <!-- header-->
        <header>
            <h1 class="ir" style="background: url(<?php echo get_option("sitelogo"); ?>) no-repeat center 0;"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
            <nav>
                <ul>
                    <li>
                        <a data-id="menu_0" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_1" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_2" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_3" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_4" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_5" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_6" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_7" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_8" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_9" href="#" class=""></a>
                    </li>
                    <li>
                        <a data-id="menu_10" href="#" class=""></a>
                    </li>
                </ul>
                <div class="search">
                    <form method="get" action="<?php echo home_url(); ?>">
                        <i class="ico-search"></i>
                        <input type="text" class="search-field" value="" name="s">
                        <input type="submit" class="search-btn" value="GO">
                    </form>
		</div>
            </nav>
        </header>

        <!-- footer -->
        <footer>
            <ul class="house">
                <li><a class="ir" style="background: url(<?php echo get_option("footerlogo"); ?>) no-repeat;" href="http://chungthanhphong.com/bridal">&nbsp;</a></li>
            </ul>
            <div id="footer_menu"><?php echo stripslashes(get_option(SHORT_NAME . "_footer_text")); ?></div>
            <ul class="social">
                <li class="facebook"><a href="<?php echo get_option(SHORT_NAME . "_fbURL"); ?>" target="_blank"></a></li>
                <li class="pinterest"><a href="<?php echo get_option(SHORT_NAME . "_pinterestURL"); ?>" target="_blank"></a></li>
                <li class="youtube"><a href="<?php echo get_option(SHORT_NAME . "_youtubeURL"); ?>" target="_blank"></a></li>
                <li class="instagram"><a href="<?php echo get_option(SHORT_NAME . "_instagramURL"); ?>" target="_blank"></a></li>
                <li class="weibo hidden"><a href="#" target="_blank"></a></li>
                <li class="wechat hidden"><a href="#" target="_blank"></a></li>
            </ul>
        </footer>
    </div>
    <div id="smenu" style="left: 110px;">
        <nav id="snav-menu_0" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_1" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_2" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_3" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_4" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_5" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_6" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_7" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_8" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_9" style="top: 0; display: none;"></nav>
        <nav id="snav-menu_10" style="top: 0; display: none;"></nav>
    </div>
    
    <div class="static-links">
        <?php if(!is_user_logged_in()): ?>
        <a href="<?php echo wp_login_url(getCurrentRquestUrl()); ?>" title="<?php echo _e('SIGN IN/SIGN UP'); ?>"><?php echo _e('SIGN IN/SIGN UP'); ?></a> / 
        <?php else: ?>
        <a href="<?php echo wp_logout_url(getCurrentRquestUrl()); ?>"><?php echo _e('LOGOUT'); ?></a> / 
        <?php endif; ?>
        <a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>" title="<?php echo _e('YOUR CART'); ?>">
            <?php echo _e('YOUR CART'); ?> (<span class="cart-count">
            <?php
            if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
                echo count($cart);
            } else {
                echo "0";
            }
            ?>
        </span>)</a>
    </div>