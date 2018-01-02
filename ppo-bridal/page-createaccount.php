<?php
/*
  Template Name: Sign Up / Sign In
 */
if (is_user_logged_in()) {
    header("location: " . home_url());
    exit;
}

get_header();
$msg = array(
    'warning' => array(),
    'success' => array()
);
// Create an Account
if (getRequestMethod() == 'POST' and $_POST['action_type'] == '7d2abf2d0fa7c3a0c13236910f30bc43') {
    $sltion = getRequest('user_sltion');
    $fname = getRequest('first_name');
    $lname = getRequest('last_name');
    $email = getRequest('email');
    $confirm_email = getRequest('confirm_email');
    $password = getRequest('password');
    $confirm_password = getRequest('confirm_password');
    $dob_month = getRequest('dob_month');
    $dob_day = getRequest('dob_day');
    $dob_year = getRequest('dob_year');
    
    if (!is_email($email)) {
        array_push($msg['warning'], "<p>Địa chỉ email không hợp lệ!</p>");
    } elseif (email_exists($email)) {
        array_push($msg['warning'], "<p>Địa chỉ email này đã tồn tại!</p>");
    } elseif ($confirm_email != $email) {
        array_push($msg['warning'], "<p>Xác nhận email không chính xác!</p>");
    } elseif (empty ($password)) {
        array_push($msg['warning'], "<p>Vui lòng nhập mật khẩu!</p>");
    } elseif ($confirm_password != $password) {
        array_push($msg['warning'], "<p>Xác nhận mật khẩu không chính xác!</p>");
    } elseif (!in_array($dob_month, range(1, 12))) {
        array_push($msg['warning'], "<p>Tháng sinh không chính xác!</p>");
    } elseif (!in_array($dob_day, range(1, 31))) {
        array_push($msg['warning'], "<p>Ngày sinh không chính xác!</p>");
    } elseif (!in_array($dob_year, range(1940, date('Y') - 5))) {
        array_push($msg['warning'], "<p>Năm sinh không chính xác!</p>");
    } else {
        $login = explode("@", $email);
        $sanitized_user_login = sanitize_user($login[0]);
        if (username_exists($sanitized_user_login)) {
            array_push($msg['warning'], "<p>Tài khoản đã tồn tại, vui lòng nhập email khác!</p>");
        } else {
            $user_id = wp_create_user($sanitized_user_login, $password, $email);
            if (!$user_id || is_wp_error($user_id)) {
                array_push($msg['warning'], "Đăng ký lỗi. Vui lòng liên hệ <a href='mailto:" . get_option('admin_email') . "'>quản trị website</a>!");
            } else {
                array_push($msg['success'], "Đăng ký thành công!");
                //Set up the Password change nag.
                update_user_option($user_id, 'default_password_nag', true, true);
                update_usermeta($user_id, 'user_sltion', $sltion);
                update_usermeta($user_id, 'first_name', $fname);
                update_usermeta($user_id, 'last_name', $lname);
                update_usermeta($user_id, 'dob_month', $phone);
                update_usermeta($user_id, 'dob_day', $address1);
                update_usermeta($user_id, 'dob_year', $address2);
                // notification for user
                //wp_new_user_notification($user_id, $password);
                custom_wp_new_user_notification($user_id, $password);
            }
        }
    }
}
// Sign In
if (getRequestMethod() == 'GET') {
    $login = getRequest('login');
    
    if ($login == 'failed') {
        array_push($msg['warning'], "<p>Đăng nhập lỗi, vui lòng kiểm tra thông tin chính xác và đăng nhập lại!</p>");
    } elseif ($login == 'empty') {
        array_push($msg['warning'], "<p>Đăng nhập lỗi, vui lòng kiểm tra thông tin chính xác và đăng nhập lại!</p>");
        //array_push($msg['warning'], "<p>Sai tài khoản hoặc mật khẩu!</p>");
    }
}
?>
<div id="main">
    <div class="page-newaccount">
        <div class="user-signup">
            <h2><?php echo _e('NEW CUSTOMER'); ?></h2>
            <form action="" method="post" id="frmSignup">
                <div class="form-group fl mr10" style="min-width: calc(16% - 20px)">
                    <label for="user_sltion" class="control-label"><?php echo _e('Salutation'); ?></label>
                    <select name="user_sltion" id="user_sltion" class="form-control">
                        <?php
                        $salutations = salutation_list();
                        foreach ($salutations as $value) {
                            echo "<option value=\"$value\">$value</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group fl mr10" style="min-width: 42%">
                    <label for="first_name" class="control-label"><?php echo _e('First Name'); ?></label>
                    <input type="text" id="first_name" name="first_name" value="" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 42%">
                    <label for="last_name" class="control-label"><?php echo _e('Last Name'); ?></label>
                    <input type="text" id="last_name" name="last_name" value="" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="email" class="control-label"><?php echo _e('Your E-mail Address'); ?></label>
                    <input type="email" id="email" name="email" value="" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="confirm_email" class="control-label"><?php echo _e('Confirm E-mail Address'); ?></label>
                    <input type="email" id="confirm_email" name="confirm_email" value="" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="password" class="control-label"><?php echo _e('Password'); ?></label>
                    <input type="password" id="password" name="password" value="" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="confirm_password" class="control-label"><?php echo _e('Confirm Password'); ?></label>
                    <input type="password" id="confirm_password" name="confirm_password" value="" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group mb10">
                    <label class="control-label"><?php echo _e('Birthday'); ?></label>
                    <div>
                        <select name="dob_month" class="form-control fl mr10" style="width: calc(45% - 10px)">
                            <?php
                            $months = month_list();
                            foreach ($months as $key => $value) {
                                echo "<option value=\"$key\">$value</option>";
                            }
                            ?>
                        </select>
                        <select name="dob_day" class="form-control fl mr10" style="width: calc(37% - 10px)">
                            <?php
                            for($i = 1; $i <= 31; $i++) {
                                echo "<option value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                        <input type="number" name="dob_year" value="" class="form-control fl" style="width: 15%" />
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="<?php echo _e('Create an Account'); ?>" id="btnSignup" class="form-control" />
                    <input type="hidden" name="action_type" value="7d2abf2d0fa7c3a0c13236910f30bc43" />
                </div>
            </form>
        </div>
        <div class="user-signin">
            <h2><?php echo _e('RETURNING CUSTOMER'); ?></h2>
            <form action="<?php bloginfo('siteurl'); ?>/wp-login.php" method="post" name="loginform" id="loginform">
                <div class="form-group mb17" style="min-width: calc(100% - 10px)">
                    <label for="user_login" class="control-label"><?php echo _e('Your E-mail Address'); ?></label>
                    <input type="text" id="user_login" name="log" value="" class="form-control" />
                </div>
                <div class="form-group" style="min-width: calc(100% - 10px)">
                    <label for="user_pass" class="control-label"><?php echo _e('Password'); ?></label>
                    <input type="password" id="user_pass" name="pwd" value="" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="rememberme">Ghi nhớ
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    </label>
                </div>
                <div class="form-group mb10">
                    <input type="submit" value="<?php echo _e('Sign In'); ?>" id="btnSignin" class="form-control" />
                    <?php
                    $redirect_to = getRequest('redirect_to');
                    if(empty($redirect_to)){
                        if(isset($_SESSION['redirect_to'])){
                            $redirect_to = $_SESSION['redirect_to'];
                        } else {
                            $redirect_to = home_url();
                        }
                    }
                    ?>
                    <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                    <!--<input type="hidden" name="action_type" value="cc0256df40cbc924af2b31aeccb869b0" />-->
                </div>
                <div class="form-group">
                    <a href="#" title="<?php echo _e('Forgot Your Password?'); ?>"><?php echo _e('Forgot Your Password?'); ?></a>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
        <div id="message" class="mt10 pdl15 pdr15 t_center font14 <?php
            if (!empty($msg['warning'])) {
                echo 'warning';
            } elseif (!empty($msg['success'])) {
                echo 'success';
            }
            ?>">
            <?php
            if (!empty($msg['warning'])) {
                foreach ($msg['warning'] as $m) {
                    echo $m;
                }
            }
            if (!empty($msg['success'])) {
                foreach ($msg['success'] as $m) {
                    echo $m;
                }
            }
            ?>
       </div>
    </div>
</div>
<?php get_footer(); ?>
