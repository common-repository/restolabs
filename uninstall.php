<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
    if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS  ".$wpdb->prefix."resto_plugin_user" );
    delete_option("resto_plugin_version");
?>