<?php
/*
  Plugin Name: Restolabs
  Description: Provide pannel for Restolabs integration.
  Version: 1.0
  Author: Restolabs
 */

if (!defined('ABSPATH'))
    exit;
wp_enqueue_style('resto_integration_style', plugins_url('/css/resto_integration.css', __FILE__));
wp_enqueue_script('resto-custom-script', plugins_url('/js/resto-custom-script.js', __FILE__));

//Table creation
include 'resto_plugin_db.php';
//ADMIN MENU CREATION
add_action('admin_menu', 'resto_plugin_menu');

function resto_plugin_menu() {
    add_menu_page('Restolabs', 'Restolabs', 'manage_options', 'resto-plugin', 'rls_restolabs_integration_url_function', plugin_dir_url(__FILE__) . 'resto-plug-icon.png');
}

register_activation_hook(__FILE__, 'resto_plugin_create_db');

//widget creation
function rls_restolabs_load_widget() {
    register_widget('rls_restolabs_widget');
}

add_action('widgets_init', 'rls_restolabs_load_widget');

// Creating the widget 
class rls_restolabs_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
// Base ID of widget
                'rls_restolabs_widget',
// Widget name will appear in UI
                __('Restolabs', 'resto_widget_domain'),
// Widget description
                array('description' => __('Widget for showing ordering page ', 'resto_widget_domain'),)
        );
    }

// Creating widget front-end

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        global $wpdb;
        $current_user = wp_get_current_user();
        $current_user = $current_user->ID;
        $sql = "SELECT * FROM " . $wpdb->prefix . "resto_plugin_user";
        $result = $wpdb->get_row($sql);
        $resto_widget_data = $result->widget_option;
// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

// This is where we run the code and display the output

        $resto_widget_content_first = '<div class="resto-plugin"><label class="restolabs-ordering-btn" for="resto-modal-1">Visit Here</label>
            <input class="resto-modal-state" id="resto-modal-1" type="checkbox" />
            <div class="resto-modal">
              <label class="resto_modal__bg" for="resto-modal-1"></label>
              <div class="resto_modal__inner">
                <label class="resto_modal__close" for="resto-modal-1"></label>';
        $resto_widget_content_third = '</div></div></div>';

        if ($resto_widget_data == 'iframe') {
            $resto_widget_content_second = '<iframe src="' . $result->menu_url . '"></iframe>';
            echo __($resto_widget_content_first . $resto_widget_content_second . $resto_widget_content_third, 'resto_widget_domain');
        }

        if ($resto_widget_data == 'link') {
            $resto_widget_link_data = '<div class="resto-plugin"><a href="' . $result->menu_url . '" class="restolabs-ordering-btn" target="_blank">Visit here</a></div>';
            echo __($resto_widget_link_data, 'resto_widget_domain');
        } else {
            echo __('', 'resto_widget_domain');
        }
        echo $args['after_widget'];
    }

// Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Order Online', 'resto_widget_domain');
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

// Class rls_restolabs_widget ends here
//Creating Shortcode


function rls_restolabs_integration_url() {
    $user_id = wp_get_current_user();
    $current_user = $user_id->ID;
    global $wpdb;
    if (isset($_POST['rls_restolabs_logout_submit'])) {

        $wpdb->query(
                'DELETE  FROM ' . $wpdb->prefix . 'resto_plugin_user'
        );
    }
    $sql = "SELECT * FROM " . $wpdb->prefix . "resto_plugin_user";
    $result = $wpdb->get_row($sql);


    $current_user = wp_get_current_user();
    $current_user = $current_user->ID;
    if (isset($_POST['rls_restolabs_option_submit'])) {
        $option_val = sanitize_text_field($_POST['rls_restolabs_option_val']);

        $wpdb->query("UPDATE " . $wpdb->prefix . "resto_plugin_user SET widget_option='$option_val'");
    }
    $sql = "SELECT * FROM " . $wpdb->prefix . "resto_plugin_user";
    $result = $wpdb->get_row($sql);

    include ('country_code.php');
//PAGE STYLING
    include ('form_body.php');


    if (empty($result->profile_id)) {

        echo '<script>rls_restolabs_disable_profile_info();</script>';
    } else {
        echo '<script>rls_restolabs_enable_profile_info();</script>';
        echo '<script>rls_restolabs_disable_login_block();</script>';
        echo '<script>rls_restolabs_disable_reg_block();</script>';
    }
}

//USER LOGIN

function rls_restolabs_complete_user_login() {

    if (isset($_POST['rls_restolabs_login_submit'])) {
        if (isset($_POST['resto_user_email']) && isset($_POST['resto_user_password'])) {
            global $reg_errors, $resto_user_email, $resto_user_password;
            if (!isset($_POST['rls_restolabs_log_field']) || !wp_verify_nonce($_POST['rls_restolabs_log_field'], 'rls_restolabs_log_action')
            ) {
                print 'Security check fail.';
                exit;
            } else {
                $userdata = array(
                    'request_operation' => 'user_profile_info',
                    'usermail' => sanitize_email($_POST['resto_user_email']),
                    'password' => sanitize_text_field($_POST['resto_user_password']),
                );
                echo rls_restolabs_httpPostData("https://app1.restolabs.com/api/user-profile-check", $userdata);
            }
        } else {
            ?>
            <div class="error notice">
                <p><?php _e('you can\'t left username or Password blank. '); ?></p>
            </div>
            <?php
        }
    }
}

//USER REGISTERATION
function rls_restolabs_complete_user_registration() {
    if (isset($_POST['rls_restolabs_reg_submit'])) {
        if (isset($_POST['resto_user_email']) && isset($_POST['resto_confirm_password'])) {
            if ($_POST['resto_user_password'] == $_POST['resto_confirm_password']) {
                if (!isset($_POST['rls_restolabs_reg_field']) || !wp_verify_nonce($_POST['rls_restolabs_reg_field'], 'rls_restolabs_reg_action')
                ) {
                    print 'Security check fail.';
                    exit;
                } else {
                    global $reg_errors, $resto_user_email, $resto_user_password;
                    if (isset($_POST['full_name'])) {
                        $full_name = sanitize_text_field($_POST['full_name']);
                    }
                    if (isset($_POST['restaurant_name'])) {
                        $restaurant_name = sanitize_text_field($_POST['restaurant_name']);
                    }
                    if (isset($_POST['resto_user_email'])) {
                        $resto_user_email = sanitize_email($_POST['resto_user_email']);
                    }
                    if (isset($_POST['country'])) {
                        $country = sanitize_text_field($_POST['country']);
                    }
                    if (isset($_POST['resto_confirm_password'])) {
                        $resto_confirm_password = sanitize_text_field($_POST['resto_confirm_password']);
                    }

                    $userdata = array(
                        'cust_name' => $full_name,
                        'restaurant_name' => $restaurant_name,
                        'mail' => $resto_user_email,
                        'country' => $country,
                        'password' => $resto_confirm_password,
                        'brand_id' => "10727",
                        'api_key' => "4519726",
                        'plan' => "Free",
                    );

                    echo rls_restolabs_httpPostReg("https://app1.restolabs.com/api/subscribe", $userdata);
                }
            } else {
                ?>
                <div class="error notice">
                    <p><?php _e('Password not mattched'); ?></p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="error notice">
                <p><?php _e('you can\'t left any field blank.'); ?></p>
            </div>
            <?php
        }
    }
}

function rls_restolabs_integration_url_function($decode_data_val) {
    rls_restolabs_complete_user_login();
    rls_restolabs_complete_user_registration();
    rls_restolabs_integration_url();
}

function rls_restolabs_httpPostReg($url, $userdata) {
    $postData = '';
    $postdata_val = json_encode($userdata);
    $request = new WP_Http;
    $result = $request->request($url, array('method' => 'POST', 'body' => $postdata_val));
    $decode_data_val = json_decode($result['body']);
    $current_user = wp_get_current_user();
    global $wpdb;
    $success = $decode_data_val->success;
    if ($decode_data_val->success == "true") {
        ?>
        <div class="updated notice">
            <p><?php _e('Your account has been successfully created'); ?></p>
        </div>
        <?php
        rls_restolabs_complete_user_login();
    }
}

//FETCH RESTO USER DETAILS

function rls_restolabs_httpPostData($url, $userdata) {
    $postData = '';
    $postdata_val = json_encode($userdata);
    $request = new WP_Http;
    $result = $request->request($url, array('method' => 'POST', 'body' => $postdata_val));

    if ($result->errors) {
        ?>
        <div class="error notice">
            <p><?php _e('The username or password you entered is incorrect.'); ?></p>
        </div>
        <?php
        wp_die();
    }

    $decode_data_val = json_decode($result['body']);
    $current_user = wp_get_current_user();
    global $wpdb;
    $success = $decode_data_val->success;
    if ($decode_data_val->success == "true") {
        $user_id = $decode_data_val->response->user_id;
        $current_user = $current_user->ID;
        $profile_id = $decode_data_val->response->profile_id;
        $profile_name = $decode_data_val->response->profile_name;
        $location_id = $decode_data_val->response->location_id;
        $menu_url = $decode_data_val->response->menu_url;
        $brand_info_id = $decode_data_val->response->brand_info->id;
        $brand_info_title = $decode_data_val->response->brand_info->title;
        $brand_info_website = $decode_data_val->response->brand_info->website;
        $brand_info_power_by_logo = $decode_data_val->response->brand_info->power_by_logo;
        $brand_info_logo = $decode_data_val->response->brand_info->logo;
        $brand_info_login_url = $decode_data_val->response->brand_info->login_url;
        $brand_info_restaurant_name = $decode_data_val->response->brand_info->restaurant_name;
        $wpdb->insert($wpdb->prefix . 'resto_plugin_user', array(
            'user_id' => $user_id,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'location_id' => $location_id,
            'menu_url' => $menu_url,
            'brand_info_id' => $brand_info_id,
            'brand_info_title' => $brand_info_title,
            'brand_info_website' => $brand_info_website,
            'brand_info_power_by_logo' => $brand_info_power_by_logo,
            'brand_info_logo' => $brand_info_logo,
            'brand_info_login_url' => $brand_info_login_url,
            'brand_info_restaurant_name' => $brand_info_restaurant_name,
            'current_user' => $current_user,
        ));
        $_SESSION['user_id'] = $user_id;
    }
}

function rls_restolabs_online_order_popup() {
    global $wpdb;
    $current_user = wp_get_current_user();
    $current_user = $current_user->ID;
    $sql = "SELECT * FROM " . $wpdb->prefix . "resto_plugin_user";
    $result = $wpdb->get_row($sql);
    return '<div class="resto-plugin"><label class="restolabs-ordering-btn" for="resto-modal-1">Visit Here</label>
                <input class="resto-modal-state" id="resto-modal-1" type="checkbox" />
                <div class="resto-modal">
                  <label class="resto_modal__bg" for="resto-modal-1"></label>
                  <div class="resto_modal__inner">
                    <label class="resto_modal__close" for="resto-modal-1"></label>
                    <iframe src="' . esc_url($result->menu_url) . '" style="border: 0; width: 100%; height: 100%"></iframe></div></div></div>';
}

function rls_restolabs_online_order_link() {
    global $wpdb;
    $current_user = wp_get_current_user();
    $current_user = $current_user->ID;
    $sql = "SELECT * FROM " . $wpdb->prefix . "resto_plugin_user";
    $result = $wpdb->get_row($sql);
    return '<div class="resto-plugin"><a href="' . esc_url($result->menu_url) . '" class="restolabs-ordering-btn" target="_blank">Visit here</a></div>';
}
