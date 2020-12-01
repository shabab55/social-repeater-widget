<?php

// Exit if Accessed Directly
if(!defined('ABSPATH')){
	exit;
}
// ---------------------------------------------------------
// Register Plugin Options Via Settins API
// Class Name: SocialRepeaterSettings
// ---------------------------------------------------------
class SocialRepeaterSettings{
    public function __construct()
    {
        add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( $this, 'srw_social_repeater_widget_settings_link' ) );
        add_action( 'admin_menu', array( $this, 'srw_social_repeater_widget_setup_menu' ));
        add_action( 'admin_init',array( $this, 'srw_social_repeater_widget_settings' ));
    }

    public function srw_social_repeater_widget_settings_link($links) {
	    $newlink = sprintf("<a href='%s'>%s</a>",'options-general.php?page=srw-social-repeater-widget-settings',__('Settings','srw-widget'));
	    $links[] = $newlink;
	    return $links;
	}

    public function srw_social_repeater_widget_setup_menu(){
        $page_title = __( 'SRW Widget Settings', 'srw-widget' );
		$menu_title = __( 'SRW Widget Settings', 'srw-widget' );
		$capability = 'manage_options';
		$slug       = 'srw-social-repeater-widget-settings';
		$callback   = array( $this, 'srw_widget_settings_content' );
		add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
    }

// ----------------------------------
// Add Plugin Settings Fields Form
// ----------------------------------
    function srw_widget_settings_content(){
        ?>
        <h1><?php echo _e( 'Social Repeater Widget','srw-widget'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'srw-social-repeater-widget-settings' ); ?>
            <?php do_settings_sections( 'srw-social-repeater-widget-settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo _e( 'Maximum entries allowed', 'srw-widget'); ?>:</th>
                    <td><select name="max_entries">
                        <?php $maxvalues = array('5','10');
                        foreach($maxvalues as $value){
                            echo '<option value="'.$value.'"'.selected( $value, get_option( 'max_entries' ) ).'>'.$value.'</option>';
                        }?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo _e('Choose Style','srw-widget'); ?>:</th>
                    <td><select name="srw_style">
                        <?php $styleList = array('circle','olive','lemon','fold','spin');
                        foreach($styleList as $style){
                            echo '<option value="'.$style.'"'.selected( $style, get_option( 'srw_style' ) ).'>'.$style.'</option>';
                        }?>
                        </select>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><?php echo _e( 'Open Link on click', 'srw-widget' ); ?>Target Window:</th>
                    <td>
                        <select name = "srw_link_target">
                            <option value="_blank" <?php echo (get_option( 'srw_link_target' ) == '_blank') ? 'selected': '';?>><?php echo _e( 'New Tab', 'srw-widget' ); ?></option>
                            <option value="_window" <?php echo (get_option( 'srw_link_target' ) == '_window') ? 'selected': '';?>>
                                <?php echo _e( 'New Window', 'srw-widget' ); ?>
                            </option>
                        </select>
                    </td>
                </tr>

            </table>
            <?php submit_button(); ?>
            </div>
        </form>
        <?php
    }

    public function srw_social_repeater_widget_settings(){
        register_setting( 'srw-social-repeater-widget-settings', 'max_entries' );
        register_setting( 'srw-social-repeater-widget-settings', 'srw_style' );
        register_setting( 'srw-social-repeater-widget-settings', 'srw_link_target' );
    }

}

new SocialRepeaterSettings();