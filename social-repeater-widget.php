<?php
/*
Plugin Name: Social Repeater Widget
Plugin URI:https://github.com/shabab55/social-repeater-widget
Description:A simple and nice plugin to show social platform link on Frontend with social icon.
Version: 1.0.0
Author:Shabab Ahmed
Author URI:http://devshabab.xyz/
License: GPLv2 or later
Text Domain:srw-widget
*/

// Exit if Accessed Directly
if(!defined('ABSPATH')){
	exit;
}

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------

define( "SRW_PLUGIN_ROOT", plugin_dir_url( __FILE__ ) );
define( "SRW_ASSETS_DIR", plugin_dir_url( __FILE__ ) . "assets/" );
define( "SRW_WIDGET_DIR", plugin_dir_path( __FILE__ ) . "widget/" );
define( "SRW_WIDGET_INC", plugin_dir_path( __FILE__ ) . "inc/" );
define( "SRW_ASSETS_PUBLIC_DIR", plugin_dir_url( __FILE__ ) . "assets/public" );
define( "SRW_ASSETS_ADMIN_DIR", plugin_dir_url( __FILE__ ) . "assets/admin" );

// ---------------------------------------------------------
// Call Required Plugin Files
// ---------------------------------------------------------
require_once(SRW_WIDGET_DIR. '/SocialRepeaterWidget.php');
require_once(SRW_WIDGET_INC. '/SocialRepeaterSettings.php');

class SRWNewsletter{
    public function __construct()
    {
        add_action('wp_enqueue_scripts',array($this,'SRW_front_scripts'));
        add_action( 'admin_enqueue_scripts', array( $this, 'SRW_admin_assets' ) );
        add_action('widgets_init',array($this,'SRW_register_widget'));
        add_action('plugins_loaded',array($this,'SRW_load_textdomain'));
    }

    public function SRW_front_scripts(){
        wp_register_style('SRW-fontawesome-css','//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('SRW-front-style',SRW_ASSETS_PUBLIC_DIR . "/css/style.css",null);
        wp_enqueue_script('SRW-front-script',SRW_ASSETS_PUBLIC_DIR . "/js/script.js",array('jquery'),time(),true);
    }

    public function SRW_admin_assets($screen){
        if ( 'widgets.php' ==$screen) {
			wp_enqueue_script( 'SRW-admin-script', SRW_ASSETS_ADMIN_DIR . "/js/admin.js", array( 'jquery' ),time(), true );
		}
    }


    public function SRW_register_widget(){
        register_widget('SRW_Social_Widget');
    }

    public function SRW_load_textdomain(){
        load_plugin_textdomain('srw-widget',false,plugin_dir_url(__FILE__)."/languages");
    }

}

new SRWNewsletter();