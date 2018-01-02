<?php
/* ----------------------------------------------------------------------------------- */
# adds the plugin initalization scripts that add styles and functions
/* ----------------------------------------------------------------------------------- */
if(!current_theme_supports('deactivate_layerslider')) require_once( "config-layerslider/config.php" );//layerslider plugin

######## BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/config.php';
include 'libs/HttpFoundation/Request.php';
include 'libs/HttpFoundation/Response.php';
include 'libs/HttpFoundation/Session.php';
include 'libs/custom.php';
include 'libs/common-scripts.php';
include 'libs/meta-box.php';
include 'libs/theme_functions.php';
include 'libs/theme_settings.php';
include 'libs/nganluong.php';
######## END: BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/custom-user.php';
include 'includes/product.php';
include 'includes/product-metabox.php';
include 'includes/page-metabox.php';
include 'includes/post-metabox.php';
include 'includes/ppocart.php';
include 'includes/orders.php';
include 'ajax.php';

if (is_admin()) {
    $basename_excludes = array('plugin-install.php', 'plugin-editor.php', 'themes.php', 'theme-editor.php', 
        'tools.php', 'import.php', 'export.php');
    if (in_array($basename, $basename_excludes)) {
        wp_redirect(admin_url());
    }
    
    include 'includes/userguide.php';
    add_action('admin_menu', 'custom_remove_menu_pages');
    add_action('admin_menu', 'remove_menu_editor', 102);
}

/**
 * Remove admin menu
 */
function custom_remove_menu_pages() {
    remove_menu_page('edit-comments.php');
    remove_menu_page('plugins.php');
    remove_menu_page('tools.php');
}

function remove_menu_editor() {
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('plugins.php', 'plugin-editor.php');
    remove_submenu_page('options-general.php', 'options-writing.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
    remove_submenu_page('options-general.php', 'options-media.php');
}

/* ----------------------------------------------------------------------------------- */
# Setup Theme
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_theme_setup")) {

    function ppo_theme_setup() {
        ## Enable Links Manager (WP 3.5 or higher)
        //add_filter('pre_option_link_manager_enabled', '__return_true');

        ## Post Thumbnails
        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }
        add_image_size('200x300', 200, 300, true);
        add_image_size('80x120', 80, 120, true);
        ## Post formats
        add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video', 'image', 'audio', 'aside'));

        ## Register menu location
        register_nav_menus(array(
            'primary' => 'Primary Location',
            //'mobile' => 'Mobile Location',
            //'footermenu' => 'Footer Menu',
        ));
    }

}

add_action('after_setup_theme', 'ppo_theme_setup');
/* ----------------------------------------------------------------------------------- */
# Widgets init
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_widgets_init")) {

    // Register Sidebar
    function ppo_widgets_init() {
        register_sidebar(array(
            'id' => 'sidebar',
            'name' => __('Sidebar'),
            'before_widget' => '<section class="widget">',
            'after_widget' => '</section>',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
        ));
    }

    // Register widgets
//    register_widget('Ads_Widget');
}

add_action('widgets_init', 'ppo_widgets_init');

/* ----------------------------------------------------------------------------------- */
# Unset size of post thumbnails
/* ----------------------------------------------------------------------------------- */

function ppo_filter_image_sizes($sizes) {
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);

    return $sizes;
}

add_filter('intermediate_image_sizes_advanced', 'ppo_filter_image_sizes');
/*
  function ppo_custom_image_sizes($sizes){
  $myimgsizes = array(
  "image-in-post" => __("Image in Post"),
  "full" => __("Original size")
  );

  return $myimgsizes;
  }

  add_filter('image_size_names_choose', 'ppo_custom_image_sizes');
 */

//PPO Feed all post type

function ppo_feed_request($qv) {
    if (isset($qv['feed']))
        $qv['post_type'] = get_post_types();
    return $qv;
}

add_filter('request', 'ppo_feed_request');

function remove_admin_bar() {
//    if (!current_user_can('administrator') && !current_user_can('editor') && !is_admin()) {
//        show_admin_bar(false);
//    }
    show_admin_bar(false);
}

add_action('after_setup_theme', 'remove_admin_bar');

function getLocale() {
    $locale = "en";
    if (get_query_var("lang") != null) {
        $locale = get_query_var("lang");
    } else if (function_exists("qtrans_getLanguage")) {
        $locale = qtrans_getLanguage();
    }
    if ($locale == "vi") {
        $locale = "vn";
    }
    return $locale;
}
/* ----------------------------------------------------------------------------------- */
# Custom Login / Logout
/* ----------------------------------------------------------------------------------- */
function change_username_wps_text($text) {
    if (in_array($GLOBALS['pagenow'], array('wp-login.php'))) {
        if ($text == 'Username') {
            $text = 'Username or Email';
        }
    }
    return $text;
}

add_filter('gettext', 'change_username_wps_text');

function login_failed() {
    $login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
    wp_redirect($login_page . '?login=failed');
    exit;
}

add_action('wp_login_failed', 'login_failed');

function verify_username_password($user, $username, $password) {
    $login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
    if ($username == "" || $password == "") {
        wp_redirect($login_page . "?login=empty".$username);
        exit;
    }
}

add_filter('authenticate', 'verify_username_password', 1, 3);

// remove the default filter
remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);

// add custom filter
add_filter('authenticate', 'ppo_authenticate_username_password', 20, 3);

function ppo_authenticate_username_password($user, $username, $password) {

    // If an email address is entered in the username box, 
    // then look up the matching username and authenticate as per normal, using that.
    if(is_email($username)){
        if (!empty($username))
            $user = get_user_by('email', $username);

        if (isset($user->user_login, $user))
            $username = $user->user_login;
    }

    // using the username found when looking up via email
    return wp_authenticate_username_password(NULL, $username, $password);
}

function redirect_after_logout() {
//    $login_page  = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
//    wp_redirect( $login_page . "?login=false" );
    wp_redirect(home_url());
    exit;
}

add_action('wp_logout','redirect_after_logout');

/* ----------------------------------------------------------------------------------- */
# Custom search
/* ----------------------------------------------------------------------------------- */
add_action('pre_get_posts', 'custom_search_filter');

function custom_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        $post_type = 'product';
        $products_per_page = intval(get_option(SHORT_NAME . "_product_pager"));
        if ($query->is_search) {
            $query->set('post_type', $post_type);
            $query->set('posts_per_page', $products_per_page);
        } else if($query->is_tax and is_tax('product_category')){
            $size = getRequest('size');
            $query->set('post_type', $post_type);
            $query->set('posts_per_page', $products_per_page);
            if(!empty($size)){
                $term_id = get_queried_object_id();
                $query->set('tax_query', array(
                    array(
                        'taxonomy' => 'product_category',
                        'field' => 'id',
                        'terms' => $term_id,
                    ),
                    array(
                        'taxonomy' => 'product_size',
                        'field' => 'slug',
                        'terms' => $size,
                    ),
                ));
            }
        }
    }
    return $query;
}
/* ----------------------------------------------------------------------------------- */
# History Orders
/* ----------------------------------------------------------------------------------- */
function get_history_order() {
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblOrders = $wpdb->prefix . 'orders';
        $query = "SELECT $tblOrders.*, $wpdb->users.display_name, $wpdb->users.user_email FROM $tblOrders 
            JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id 
            WHERE $tblOrders.customer_id = $current_user->ID ORDER BY $tblOrders.ID DESC";
        $records = $wpdb->get_results($query);
    }
    return $records;
}

function admin_add_custom_js() {
    ?>
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function ($) {
            var arrayHideAll = new Array('page-createaccount.php', 'page-print-order.php', 'page-cart.php', 'page-checkout.php', 'page-complete.php');
            $("#page_template").change(function (){
                var val = $(this).val();
                if(val === "page-slideshow.php"){
                    $("#postdivrich").hide();
                    $("#standard").show();
                } else if (val === "page-contact.php") {
                    $("#postdivrich").show();
                    $("#standard").hide();
                } else if (arrayHideAll.lastIndexOf(val) !== -1) {
                    $("#postdivrich").hide();
                    $("#standard").hide();
                } else {
                    $("#postdivrich").show();
                    $("#standard").show();
                }
            });
            $(window).load(function (){
                var val = $("#page_template").val();
                if(val === "page-slideshow.php"){
                    $("#postdivrich").hide();
                } else if (val === "page-contact.php") {
                    $("#standard").hide();
                } else if (arrayHideAll.lastIndexOf(val) !== -1) {
                    $("#postdivrich").hide();
                    $("#standard").hide();
                } else {
                    $("#postdivrich").show();
                    $("#standard").show();
                }
            });
        });
        /* ]]> */
    </script>
    <?php
}

add_action('admin_print_footer_scripts', 'admin_add_custom_js', 99);