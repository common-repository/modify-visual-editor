<?php
/**
Plugin Name: Modify Visual Editor
Plugin URI: http://themes.tradesouthwest.com/wordpress/plugins/Modify-Visual-Editor/
Description: This plugin modifies the visual editor for selected users and adds some dialogue to the  editor to deter code deletion or tag stripping. Also includes a function to remove-admin-bar for all users except administrators.
Author: Larry Judd Oliver @tradesouthwest
Version: 0.2.2
Author URI: http://
Text Domain: mved
Domain Path: /languages
This plugin is released under the GPL version 3 or later available at
http://www.gnu.org/licenses/gpl.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! is_admin() ) return; // Only load plugin when user is in admin

//wp_cache_delete ( 'alloptions', 'options' );
//Predefined Constants

define( 'MVED_VERSION', '0.2.2' );
//define( 'MVED_URI', '' );

//activate/deactivate hooks
function modify_visual_editor_activation()
{
    global $wp_version;
    $wp = '3.8';

    if ( version_compare( $wp_version, $wp, '<' ) ) {

	   deactivate_plugins( basename( __FILE__ ) );
        wp_die(		'<p>' .
			sprintf(
__( 'This plugin can not be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard &#9656; Updates to the latest version of WordPress .', 'mved' ),
			$php
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'mved' ) . '</a>'
		);
        }
}


//house keeeping fallback
function modify_visual_editor_deactivation()
{
    //clean up database and option cache
    //remove hook in profile_update

    //turn back on 'rich_editing' =true
   	global $wpdb;
	$wpdb->query(" UPDATE `" . $wpdb->prefix .
       "usermeta`  SET `meta_value` = 'true'
                   WHERE `meta_key` = 'rich_editing' ");
            return false;


        remove_action( 'profile_update',
                       'modify_visual_editor_removeUserStatus', 24 );
        remove_action( 'personal_options_update',
                       'modify_visual_editor_removeUserStatus', 25 );
}

function modify_visual_editor_uninstall()
{
    //clean up database and option cache
    //remove hook in profile_update

    //turn back on 'rich_editing' =true
   	global $wpdb;
	$wpdb->query(" UPDATE `" . $wpdb->prefix .
       "usermeta`  SET `meta_value` = 'true'
                   WHERE `meta_key` = 'rich_editing' ");
            return false;


        remove_action( 'profile_update',
                       'modify_visual_editor_removeUserStatus', 24 );
        remove_action( 'personal_options_update',
                       'modify_visual_editor_removeUserStatus', 25 );

        delete_option( 'mved_pluginPage' );
        delete_option( 'mvedASelect' );
        delete_option( 'mvedCSelect' );
}


/** enqueue scripts
 */
function modify_visual_editor_enqueue_admin_scripts()
{
    wp_enqueue_style( 'mved-admin-style', plugins_url( basename( __DIR__ )) .
                    '/assets/mved-admin-style.css', array(), MVED_VERSION );

}
add_action( 'admin_enqueue_scripts', 'modify_visual_editor_enqueue_admin_scripts' );


/**enqueue text-domain
 * example usage: German MO and PO files should be named
 * mved-domain-de_DE.mo and mved-domain-de_DE.po.
 */
function modify_visual_editor_load_plugin_textdomain()
{
    load_plugin_textdomain( 'mved-domain', FALSE, plugins_url( basename( __DIR__ )) . '/languages/' );
}
add_action( 'init', 'modify_visual_editor_load_plugin_textdomain' );


register_activation_hook(__FILE__, 'modify_visual_editor_activation');
register_deactivation_hook(__FILE__, 'modify_visual_editor_deactivation');
register_uninstall_hook(__FILE__, 'modify_visual_editor_uninstall');

require_once( 'mved-admin.php' );
