<?php

/**
 * @title               User Activation by Reset Options Page
 *
 * @author              Myles McNamara (get@smyl.es)
 * @copyright           Copyright (c) Myles McNamara 2013-2014
 * @Date                :               5/24/14
 * @Last                Modified by:   Myles McNamara
 * @Last                Modified time: 24 18 10
 */
class User_Activate_by_Reset_Options extends User_Activate_by_Reset {

	private static $instance;

	function __construct () {

		// Flush rewrite rules each time the options page is loaded
		add_action( 'init', function () {

			global $wp_rewrite;
			add_rewrite_rule( '^login/?', 'wp-login.php', 'top' );
			add_rewrite_rule( '^activate/([^/]*)/([^/]*)/', 'wp-login.php?action=rp&key=$2&login=$1', 'top' );
			//	$wp_rewrite->flush_rules(false);
		} );
		flush_rewrite_rules();
	}

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function submenu () {

		add_submenu_page( 'users.php', __( 'Manage Activation Options', User_Activate_by_Reset::plugin_slug ), __( 'Activation', User_Activate_by_Reset::plugin_slug ), 'manage_options', 'uabr_options', array( 'User_Activate_by_Reset_Options', 'get_instance' ) );
	}

	public function output_html () {

		?>

		<div class="wrap">
			<h2>Activation Settings</h2>

			<form method="post" action="users.php?page=uabr_options">
			<input type="hidden" name="option_page" value="general">
			<input type="hidden" name="action" value="update">
			<input type="hidden" id="_wpnonce" name="_wpnonce" value="76f8ce9457">
			<input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
			<table class="form-table">
			<tbody>


			<tr>
			<th scope="row"><label for="home">Site Address (URL)</label></th>
			<td><input name="home" type="text" id="home" value="http://travelhealthcare.com" class="regular-text code">
			<p class="description">Enter the address here if you want your site homepage <a href="http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory">to be different from the directory</a> you installed WordPress.</p></td>
			</tr>

			<tr>
			<th scope="row">Membership</th>
			<td> <fieldset><legend class="screen-reader-text"><span>Membership</span></legend><label for="users_can_register">
			<input name="users_can_register" type="checkbox" id="users_can_register" value="1">
			Anyone can register</label>
			</fieldset></td>
			</tr>
			<tr>
			<th scope="row"><label for="default_role">New User Default Role</label></th>
			<td>
			<select name="default_role" id="default_role">
				<option selected="selected" value="candidate">Candidate</option>
				<option value="thcadmin">THC Admin</option>
				<option value="employer">Employer</option>
				<option value="shop_manager">Shop Manager</option>
				<option value="customer">Customer</option>
				<option value="administrator">Administrator</option></select>
			</td>
			</tr>

			<tr>
			<th scope="row">Date Format</th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Date Format</span></legend>
				<label title="F j, Y"><input type="radio" name="date_format" value="F j, Y" checked="checked"> <span>May 25, 2014</span></label><br>
				<label title="Y/m/d"><input type="radio" name="date_format" value="Y/m/d"> <span>2014/05/25</span></label><br>
				<label title="m/d/Y"><input type="radio" name="date_format" value="m/d/Y"> <span>05/25/2014</span></label><br>
				<label title="d/m/Y"><input type="radio" name="date_format" value="d/m/Y"> <span>25/05/2014</span></label><br>
				<label><input type="radio" name="date_format" id="date_format_custom_radio" value="\c\u\s\t\o\m"> Custom: </label><input type="text" name="date_format_custom" value="F j, Y" class="small-text"> <span class="example"> May 25, 2014</span> <span class="spinner"></span>
				<p><a href="http://codex.wordpress.org/Formatting_Date_and_Time">Documentation on date and time formatting</a>.</p>
				</fieldset>
			</td>
			</tr>


			</tbody></table>


			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>

			</div>

	<?php
	}

}

class MySettingsPage {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct () {

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page () {

		// This page will be under "Settings"
		add_options_page( 'Activation Settings', 'Activation Settings', 'manage_options', 'uabr-setting-admin', array( $this, 'create_admin_page' ) );
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page () {

		// Set class property
		$this->options = get_option( 'uabr_settings' );
		?>
		<div class="wrap">
            <?php screen_icon(); ?>
			<h2>User Activation Settings</h2>
			<hr/>
			<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br></div>
				<div class="inside">
		            <form method="post" action="options.php">
		            <?php
		            // This prints out all hidden setting fields
		            settings_fields( 'uabr_rewrite_group' );
		            do_settings_sections( 'uabr-setting-admin' );
		            submit_button();
		            ?>
		            </form>
				</div>
			</div>
        </div>
	<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init () {

		register_setting( 'uabr_rewrite_group', // Option group
		                  'uabr_settings', // Option name
		                  array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section( 'uabr_rewrite_section', // ID
		                      'Rewrite Options', // Title
		                      array( $this, 'print_section_info' ), // Callback
		                      'uabr-setting-admin' // Page
		);

		add_settings_field( 'enable_login_rewrite', // ID
		                    'Enable Login Rewrite', // Title
		                    array( $this, 'enable_login_rewrite_callback' ), // Callback
		                    'uabr-setting-admin', // Page
		                    'uabr_rewrite_section' // Section
		);

		add_settings_field( 'title', 'Login Rewrite', array( $this, 'title_callback' ), 'uabr-setting-admin', 'uabr_rewrite_section' );
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize ( $input ) {

		$new_input = array();
		if ( isset( $input['enable_login_rewrite'] ) ) $new_input['enable_login_rewrite'] = $input['enable_login_rewrite'];

		if ( isset( $input['title'] ) ) $new_input['title'] = sanitize_text_field( $input['title'] );

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info () {

		print 'Enable any of the settings below to use a customized URL. This is specifically for wp-login.php URLs.';
	}

	function enable_login_rewrite_callback () {

		echo '<input name="uabr_settings[enable_login_rewrite]" id="enable_login_rewrite" type="checkbox" value="1" class="code" ' . checked( 1, $this->options['enable_login_rewrite'], false ) . ' />';
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function title_callback () {

		printf( '<input type="text" id="title" name="uabr_settings[title]" value="%s" /><p class="description">This will change http://yourdomain.com/wp-login.php to http://yourdomain.com/login</p>', isset( $this->options['title'] ) ? esc_attr( $this->options['title'] ) : 'login' );
	}
}

