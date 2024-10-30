<?php
//Modify Visual Editor 2017
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


//add a new menu for the admin
// $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
function mved_add_admin_menu()
{
	add_menu_page(
         __( 'Modify Visual Editor', 'mved' ),
         __( 'Modify Visual Ed', 'mved' ),
        'manage_options',
        'modify-visual-editor',
        'mved_options_page'
        );
}
add_action( 'admin_menu', 'mved_add_admin_menu' );


//settings
//generate wp-admin settings pages by registering your settings
function mved_settings_init()
{
	register_setting(
        'mved_pluginPage',
        'mved_settings'
    );
	register_setting(
        'mved_pluginPage',
        'mvedASelect',
        'sanitize_text_field'
    );
	register_setting(
        'mved_pluginPage',
        'mvedCSelect',
        'sanitize_text_field'
    );
	add_settings_section(
		'mved_pluginPage_section',
		__( 'Turns On or Off the Visual Editor to disallow using Visual Editor tab.', 'mved' ),
		'mved_settings_section_callback',
		'mved_pluginPage'
	);

    //settings field at top of sections (not form fields)
	add_settings_field(
		'mved_setting_id',
		'',
		'mved_selection_field_render',
		'mved_pluginPage',
		'mved_pluginPage_section'
	);

    //options
    add_option( 'mvedASelect', 'no', '', 'yes'
    );
    add_option( 'mvedCSelect', '1', '', 'yes'
    );
}
add_action( 'admin_init', 'mved_settings_init' );


/**
 * Making the return valuable filterable,
 * defaults can be easily overridden by a Child Theme or Plugin.
*/
function mved_get_option_defaults() {
	$defaults = array(
		'mvedASelect' => 'no',
		'mvedCSelect' => '1'   //assuming admin is id= 1
	);
	return apply_filters( 'mved_option_defaults', $defaults );
}


//render first field section
function mved_selection_field_render()
{
    /**
     * SectionA form submit
     *
     * Processing select option to turn on or off Visual Editor
     * @values yes or no
     * $mvedASelect= input
     * $userID is null since all metakeys are updated
     *
     * @column meta_key rich_editing
     * @table wp_usermeta table
     * *******************************
     */
    if (! empty ( $_POST['mvedA-submission'] )  &&
          '1' === $_POST['mvedA-submission'] )
    {

        //form select element= visual editor option
        if ( ! empty( $_POST['mvedASelect'] ) )
        {

            delete_option( 'mvedASelect' );
            $mvedASelect       = sanitize_text_field( $_POST['mvedASelect'] );
            update_option('mvedASelect', $_POST['mvedASelect']);

            //assign option values to strings
            //$mvedASelect_key   = 'rich_edting';
            $mvedASelect_value = get_option('mvedASelect', 'yes');

            //update user meta field `rich_editing`

            //no= change 'rich_editing' to `false`
            if( $mvedASelect_value == 'no' )
            {

            /**
             * Add an action to catch profile updates.
             * not @param $state_data= false
             * @param $user_ids= all users
             * This will change (add/remove) each time input changes
             */
            modify_visual_editor_removeUserStatus( );

            echo '<div class="updated fade"><p><strong>'
                 . __( 'Editor is OFF', 'mved' ) .  '<br/> ';
            echo '</strong></p></div>';
            }

                //yes= rich_editing `true`
                elseif( $mvedASelect_value == 'yes' )
                {

                    /**
                     * Add and remove action to catch profile updates.
                     * input= yes, so remove old action,
                     * replace w/ new (in function)
                     */
                    remove_action( 'profile_update',
                                   'modify_visual_editor_removeUserStatus', 24 );
                    remove_action( 'personal_options_update',
                                   'modify_visual_editor_removeUserStatus', 25 );
                    //turn on 'rich_editing' =true
                    modify_visual_editor_updateUserStatus( '', 'true' );

                    echo '<div class="updated fade"><p><strong>'
                         . __( 'Visual Editor is ON', 'mved' ) . '<br/> ';
                    echo '</strong></p></div>';
                }
                    else{
                        echo $mvedASelect_value . ' no_go';
                        }
        }
    }


   /**
    * SectionC form submit
    *
    * Processing select option to allow Visual Editor
    * @values $user_id
    * $mvedCSelect= select
    * $userID is @value of select
    *
    * @column meta_key rich_editing
    * @table wp_usermeta table
    * *******************************
    */
    if (! empty ( $_POST['mvedC-submission'] )  &&
          '2' === $_POST['mvedC-submission'] )
    {
        //form select element posted
        if ( ! empty( $_POST['mvedCSelect'] ) )
        {
            $mvedUser_Id  = sanitize_text_field( $_POST['mvedCSelect'] );
            modify_visual_editor_UpdateAdminStatus( $mvedUser_Id, 'true' );

            echo '<div class="updated fade"><p><strong>'
                 . __( 'Visual Editor is ON - Admin', 'mved' ) . '<br/> ';
            echo '</strong></p></div>';
        }
    }

    ?>

    <table class="plugin-options-table condensed"><tbody>
        <tr><td><?php _e('System Settings', 'mved'); ?></td>
            <td><?php echo php_uname(); ?></td></tr>
        <tr><td><?php _e('PHP Version', 'mved'); ?></td>
            <td><?php echo phpversion(); ?>
                <?php
                    if (version_compare('5.2', phpversion()) > 0) {
                    echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                    _e('(WARNING: This plugin may not work properly with versions earlier than PHP 5.2)', 'mved');
                    echo '</span>';
                }
                ?></td>
            </tr>
            <tr><td><?php _e('MySQL Version', 'mved'); ?></td>
                <td><?php echo getMySqlVersion(); ?>
                    <?php
                    echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                    if (version_compare('5.0', getMySqlVersion()) > 0) {
                        _e('(WARNING: This plugin may not work properly with versions earlier than MySQL 5.0)', 'mved');
                    }
                    echo '</span>';
                    ?></td>
            </tr></tbody></table>

        <table class="plugin-options-table"><tbody>
            <tr valign="top">
            <td>

            <form name="mved_usermeta_form" method="POST" action="">

                <label for="mvedASelect">
                    <?php _e( 'Turns On or Off the Visual Editor', 'mved' ); ?>
                    </label>
            </td>
            <td><p><select name="mvedASelect" id="mvedASelect">
                <?php
                $mvedASelect_value = get_option('mvedASelect', 'yes');
                $isSelected = ' selected="selected"';
                ?>
                    <option value="yes" <?php if( $mvedASelect_value == 'yes' )
                        echo $isSelected; ?>>ON</option>
                    <option value="no" <?php if( $mvedASelect_value == 'no' )
                        echo $isSelected; ?>>OFF</option>
                </select>
                </p>
            </td>
            <td><?php
                // create a nonce field
                wp_nonce_field( 'new_mvedASelectMVED_nonce',
                                'mvedASelectMVED_nonce' );
                echo '<input type="hidden" name="mvedA-submission" value="1" />';
                echo '<input class="button button-primary" type="submit"
                                name="mvedAsubmit" value="Save Setting">';
             ?></form></td>
            </tr>
            <tr valign="top">
            <td><form name="mved_adminmeta_form" method="POST" action="">
                <label for="mvedALevel">
                    <?php _e( 'Adminstrator who CAN use the Visual Editor',
                              'mved' ); ?>
                </label>
            </td>
            <td>
                <p><select name="mvedCSelect" id="mvedCLevel">
                <?php

                /** @option mvedCSelect does not have to be stored in wpdb
                 *  Using option setting only to register nonce
                 */
                $mvedCSelect_value = get_option('mvedCSelect', '1');
                $isSelected = ' selected="selected"';

                $args = array(
                        'role' => 'administrator',
                        'sort_by' => 'id'
                        );

                //start query here to loop all admins
                $user_query = new WP_User_Query( $args );
                ?>
                   <option><?php _e( 'Admin Name',  'mved' ); ?> </option>
               <?php
                    if ( ! empty( $user_query->results ) ) {
                        foreach ( $user_query->results as $user ) {

                        echo '<option value="' . esc_html( $user->ID ) . '"'; ?>
                        <?php if( $mvedCSelect_value == $user->ID )
                        echo $isSelected; ?> >
                        <?php printf( $user->display_name ); ?></option>
                        <?php }
                    } else { _e( 'Just not possible.', 'mved' ); }
                ?>
                </select></p>
            </td>
            <td><?php
                // create a nonce field
                wp_nonce_field( 'new_mvedCSelectMVED_nonce',
                                'mvedCSelectMVED_nonce' );
                echo '<input type="hidden" name="mvedC-submission" value="2" />';
                echo '<input class="button button-primary" type="submit"
                                name="mvedCsubmit" value="Save Setting">';
                echo '

    </td></tr></tbody></table><br>';


} //ends mved_selection_field_render()
?>
<?php

//display page
function mved_options_page()
{
    echo '<div class="wrap">';
    echo '<h2>Modify Visual Editor</h2>';
    echo '<p id="tswMved"><img src="' . plugins_url( basename( __DIR__ )) .
                            '/assets/tswlogo.png" alt="TSW=|=" height="32"/>
                                 <small>Tradesouthwest =|=</small></p>';

		settings_fields( 'mved_pluginPage' );
		do_settings_sections( 'mved_pluginPage' );

    echo '</div>';
}

//callback to create page section heading
function mved_settings_section_callback( ) {
echo '<hr>';
_e( 'Remember to re-enter your Administrator privileges after turning OFF Visual Editor', 'mved' );
}


//convert user levels to array of user_ids
//currently only using Administrator in callback
function modify_visual_editor_sortUserLevels($myarray)
{
    $array = $myarray;

    $args2 = array(
        'role__in' => $myarray,
        'orderby' => 'user_name',
        'order' => 'ASC'
    );

    $users_role = get_users($args2);
    if (!empty($users_role ))
        {
        foreach ($users_role as $user)
            {
            echo $user->ID . ',' . $user->user_name . ', ';
            }
        }
        return false;
}


/* Update usermeta table
 * @param int $user id
 * @param string $meta_key
 * @param column `meta_value`- value to be updated
 */
function modify_visual_editor_UpdateAdminStatus( $mvedUser_Id, $value )
{
$mvedAdmin = (intval($mvedUser_Id));
    global $wpdb;
	$wpdb->query("UPDATE `" . $wpdb->prefix .
       "usermeta` SET `meta_value` = '". $value ."'
                  WHERE `user_id` = '" . $mvedAdmin . "'
                  AND `meta_key` = 'rich_editing' ");
return false;
}


/* Update usermeta table
 * @param int $user id
 * @param string $meta_key
 * @param column `meta_value`- value to be updated
 */
function modify_visual_editor_removeUserStatus( )
{
    global $wpdb;
	$wpdb->query("UPDATE `" . $wpdb->prefix .
       "usermeta` SET `meta_value` = 'false'
                  WHERE `meta_key` = 'rich_editing' ");

        return add_action( 'profile_update',
                           'modify_visual_editor_removeUserStatus', 24 );
        return add_action( 'personal_options_update',
                           'modify_visual_editor_removeUserStatus', 25 );
return false;
}


/**
 * @rich_editing= true
 * update user metadata syntax
 * update_user_meta($user_id, $meta_key, $meta_value, $prev_value);
 * remove_action('profile_update','mved_update_user_editor_status', 21 );
 * add_action('profile_update','mved_update_user_editor_status', 21 );
*/
function modify_visual_editor_updateUserStatus($user_id, $state_data) {

   	global $wpdb;
	$wpdb->query(" UPDATE `" . $wpdb->prefix .
       "usermeta`  SET `meta_value` = '".$state_data."'
                   WHERE `meta_key` = 'rich_editing' ");
            return false;
}


    /**
     * Query MySQL DB for its version
     * @return string|false
     */
    function getMySqlVersion() {
        global $wpdb;
        $rows = $wpdb->get_results('select version() as mysqlversion');
        if (!empty($rows)) {
             return $rows[0]->mysqlversion;
        }
        return false;
    }

?>