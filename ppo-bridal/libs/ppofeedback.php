<?php
add_action('admin_menu', 'add_ppo_feedback_page');

function add_ppo_feedback_page() {
    add_submenu_page(MENU_NAME, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                //To link our submenu to a custom post type page we must specify - 
                                //edit.php?post_type=my_post_type
            __('FeedBack'), // Page title
            __('FeedBack'), // Menu title
            'edit_themes', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'ppo_feedback', // Submenu ID – Unique id of the submenu.
            'ppo_feedback_page' // render output function
    );
}
function ppo_feedback_page() {
    ?>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead">Send FeedBack</h2>
            <h4>
                Bạn cần hỗ trợ, yêu cầu hay có góp ý gì. Hãy liên hệ với chúng tôi:<br />
                <span>Email: <a href="mailto:info@ppo.vn">info@ppo.vn</a></span><br />
                <span>Điện thoại: 046 2929 445</span><br />
                Hoặc qua form bên dưới:
            </h4>
            <div id="message" class="updated fade" style="display: none;"></div>
            <form action="" method="post" id="frmFeedback">
                <input type="hidden" name="action" value="sendPPOFeedback" />
                <p>
                    <label for="feedback_name">Họ và tên (*):</label><br />
                    <input name="name" id="feedback_name" value="" class="regular-text" type="text" />
                </p>
                <p>
                    <label for="feedback_email">Địa chỉ email (*):</label><br />
                    <input name="email" id="feedback_email" value="" class="regular-text" type="text" />
                </p>
                <p>
                    <label for="feedback_phone">Điện thoại (*):</label><br />
                    <input name="phone" id="feedback_phone" value="" class="regular-text" type="text" />
                </p>
                <p>
                    <label for="feedback_content">Nội dung (*):</label><br />
                    <!--<textarea style="width: 100%;height: 300px;" name="content" id="feedback_content"></textarea>-->
                    <?php
                    wp_editor('', 'content', array(
                        'textarea_name' => 'content',
                    ));
                    ?>
                </p>
                <div class="submit">
                    <input name="send" type="submit" value="Gửi" class="button button-large button-primary" />
                </div>
            </form>
        </div>
    </div>
    <?php
}
/* ----------------------------------------------------------------------------------- */
# Send Feedback to Developer
/* ----------------------------------------------------------------------------------- */
function sendPPOFeedback(){
    $name = getRequest("name");
    $email = getRequest("email");
    $phone = getRequest("phone");
    $content = getRequest("content");
    
    $errMsg = "";
    if($name == ""){
        $errMsg .= "<p>Vui lòng nhập họ tên.</p>";
    }
    if($email == ""){
        $errMsg .= "<p>Vui lòng nhập địa chỉ Email.</p>";
    }elseif(!is_email($email)){
        $errMsg .= "<p>Địa chỉ Email không hợp lệ.</p>";
    }
    if($phone == ""){
        $errMsg .= "<p>Vui lòng nhập điện thoại.</p>";
    }
    if($content == ""){
        $errMsg .= "<p>Vui lòng nhập nội dung.</p>";
    }
    
    if($errMsg == ""){
        $subject = "Feedback - " . get_bloginfo('siteurl');
        $message = <<<HTML
<p>Chào PPO,</p>
<p>Bạn nhận được một thư góp ý từ:</p>
<p>
    Họ và tên: {$name}<br />
    Email: {$email}<br />
    Điện thoại: {$phone}<br />
</p>
<p>Nội dung:</p>
<div>{$content}</div>
<br />
Thank you,
HTML;
        
        add_filter( 'wp_mail_content_type', 'set_html_content_type' );
        wp_mail( 'info@ppo.vn', $subject, $message);
        // reset content-type to avoid conflicts
        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
        
        Response(json_encode(array(
                    'status' => 'success',
                    'message' => "<p>Thư gửi thành công. Cám ơn bạn đã gửi thư góp ý tới chúng tôi!</p>",
                )));
    }else{
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errMsg,
                )));
    }
    exit();
}