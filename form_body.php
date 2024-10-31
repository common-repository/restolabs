<?php

if (!defined('ABSPATH'))
    exit;
if (!empty($result->widget_option)) {
    $select_option = $result->widget_option;
}
if ($select_option == "iframe") {
    $checked_popup = 'checked="checked"';
}
if ($select_option == "link") {
    $checked_link = 'checked="checked"';
}

wp_enqueue_style('gfonts', '//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800');
wp_enqueue_style('gfonts', '//fonts.googleapis.com/css?family=Quicksand:300,400,500,700');

echo '
<!-- //web font -->
  <!-- main -->
<div class="main resto-plug">
    
    <div class="resto_admin_page">
      <h4 class="plug-title"><span>Restolabs</span></h4>
      <div class="logged-in">
      <div class="restolabs-account-info" id="restolabs-account-info">
        <div class="account-status"><h4><span><img class="connected-green-tick" src="' . plugins_url('/images/check-mark.png', __FILE__) . '"/>Your account is connected</span></h4><a class="resto-url" href="' . esc_url($result->brand_info_login_url) . '" target="_blank">Sign in to Restolabs dashboard</a></div>
        <div class="res-id"><span class="resto-name">' . esc_attr($result->profile_name) . '</span>      <div class="restolabs-logout" id="restolabs-logout">
      <form action="" method="post">
    <input type="submit" name="rls_restolabs_logout_submit" value="Logout" />
</form>
      </div></a></div>
        <div class="clear"> </div>
      </div>
      <div class="restolabs-show-option" id="restolabs-show-option">
  <span >Please select the method you would like to show your online ordering</span><div class="restolabs-show-option-list"><form action="' . $_SERVER['REQUEST_URI'] . '" " method="post">
  <input type="radio" name="rls_restolabs_option_val" value="link" ' . $checked_link . '>Create hyperlink<br><div class="option-description"><span class="option-desc">This option will create widget with online ordering hyperlink which you will place on your site.</span><br><!-- <span class="shortcode-text">Shortcode</span><span class="shortcode-desc"> [Restolabs-online-order-link]</span> --></div><br>
  <input type="radio" name="rls_restolabs_option_val" value="iframe" ' . $checked_popup . '>Create Popup<br><div class="option-description"><span class="option-desc">This option will create widget with online ordering popup option which you will place on your site.</span><br><!-- <span class="shortcode-text">Shortcode</span><span class="shortcode-desc">[Restolabs-online-order-popup]</span> --></div><br>
  
   <input class="choice-submit" type="submit" name="rls_restolabs_option_submit" value="Submit"> 
  </form></div>
</div>
      </div>
<div class="resto-form login" id="restolabs-login-block">
        <h3>Please login to connect with your Restolabs</h3><form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
          <div class="resto-form-left ">
            <input type="email" name="resto_user_email" placeholder="Enter Email" required="">
          </div>
          <div class="resto-form-right">
            <input class="password-field" type="password" name="resto_user_password" placeholder="Enter Password" required="">
             
          </div>
           <div class="login-form-center"><input class="login-submit" type="submit" name="rls_restolabs_login_submit" value="Login">';
            wp_nonce_field( 'rls_restolabs_log_action', 'rls_restolabs_log_field' );

echo '<div class="register-here-link"><span><a href="#" id="reg-link" onclick="rls_restolabs_show_reg_block()">Don\'t have Restolabs account? Signup for 30 days free trial (No credit card required)</a></span></div></div> 
          <div class="clear"></div>
        </form>
      </div>
      
      <div class="resto-form register" id="restolabs-reg-block">
        <h3>Registration</h3><form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
          <div class="resto-form-left ">
            
<input type="text" name="full_name" placeholder="Enter Full Name" required=""><input type="text" name="restaurant_name" placeholder="Enter Restaurant Name" required=""> 
            <input type="email" class="Email" name="resto_user_email" placeholder="Enter Email" required="">
<select name="country" class="select" required>';
foreach ($countries as $key => $country) {
    echo '<option value="' . $key . '">' . $country . '</option>';
}
echo '</select> 
          </div>
          <div class="resto-form-right">
            <input class="password-field" type="password" name="resto_user_password" placeholder="Create Password" required="" id="password"><input class="password-field" type="password" name="resto_confirm_password" placeholder="Confirm Password" required="" id="confirm_password">
            <input type="submit" class="reg-submit"  name="rls_restolabs_reg_submit" value="SUBMIT">';
            wp_nonce_field( 'rls_restolabs_reg_action', 'rls_restolabs_reg_field' );
echo '<div class="back-to-login-link"><span> <a href="#" id="login-link" onclick="rls_restolabs_show_login_block()">Back to Login</a></span></div>
          </div> 
          <div class="clear"> </div>
        </form> 
      </div>  
    </div>  
  </div>  
  <!-- //main -->
  <!-- copyright -->
  <div class="resto-copyright">
    <p>© 2015. Restolabs. All Rights Reserved</p>
  </div>
  <!-- //copyright --> 
';

