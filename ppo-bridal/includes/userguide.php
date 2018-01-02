<?php
add_action('admin_menu', 'add_ppo_userguide_page');

function add_ppo_userguide_page() {
    add_submenu_page(MENU_NAME, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                //To link our submenu to a custom post type page we must specify - 
                                //edit.php?post_type=my_post_type
            __('Hướng dẫn sử dụng'), // Page title
            __('Hướng dẫn'), // Menu title
            'edit_themes', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'ppo_userguide', // Submenu ID – Unique id of the submenu.
            'ppo_userguide_page' // render output function
    );
}
function ppo_userguide_page() {
    ?>
<style>
    .userguide-content{
        margin-top: 20px;
    }
    .userguide-content ol{
        list-style: decimal;
        padding-left: 20px;
        display: block;
        margin-bottom: 20px;
    }
    .userguide-content h3{
        display: block;
    }
</style>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead">Hướng dẫn sử dụng</h2>
            <div class="userguide-content">
                <iframe src="http://docs.google.com/viewer?url=<?php bloginfo('stylesheet_directory'); ?>/includes/CTP_Guide.pdf&embedded=true" width="100%" height="500" style="border: none;"></iframe>
            </div>
        </div>
    </div>
    <?php
}

