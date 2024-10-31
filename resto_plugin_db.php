<?php

if (!defined('ABSPATH'))
    exit;

function resto_plugin_create_db() {
    global $wpdb;
    $version = get_option('resto_plugin_version', '1.0');
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'resto_plugin_user';
    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    `current_user` varchar(250) NOT NULL,
    `user_id` int(250) NOT NULL,
    `profile_name` varchar(250) NOT NULL,
    `profile_id` int(250) NOT NULL,
    `location_id` int(250) NOT NULL,
    `menu_url` varchar(250) NOT NULL,
    `brand_info_id` int(250) NOT NULL,
    `brand_info_title` varchar(250) NOT NULL,
    `brand_info_website` varchar(250) NOT NULL,
    `brand_info_power_by_logo` varchar(250) NOT NULL,
    `brand_info_logo` varchar(250) NOT NULL,
    `brand_info_login_url` varchar(250) NOT NULL,
    `brand_info_restaurant_name` varchar(250) NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    `widget_option` varchar(250) NOT NULL DEFAULT 'iframe',
    UNIQUE KEY id (id)
  ) $charset_collate;";
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
    if (version_compare($version, '2.0') < 0) {
        $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      `current_user` int(250) NOT NULL,
      `user_id` int(250) NOT NULL,
      `profile_name` varchar(250) NOT NULL,
      `profile_id` int(250) NOT NULL,
      `location_id` int(250) NOT NULL,
      `menu_url` varchar(250) NOT NULL,
      `brand_info_id` int(250) NOT NULL,
      `brand_info_title` varchar(250) NOT NULL,
      `brand_info_website` varchar(250) NOT NULL,
      `brand_info_power_by_logo` varchar(250) NOT NULL,
      `brand_info_logo` varchar(250) NOT NULL,
      `brand_info_login_url` varchar(250) NOT NULL,
      `brand_info_restaurant_name` varchar(250) NOT NULL,
      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      `widget_option` varchar(250) NOT NULL,
      UNIQUE KEY id (id)
    ) $charset_collate;";
        dbDelta($sql);
        update_option('my_plugin_version', '2.0');
    }
}
