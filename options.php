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

if ( ! function_exists( '_log' ) ) {
	function _log ( $message ) {

		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}

class User_Activate_by_Reset_Options extends User_Activate_by_Reset {

	private static $instance;
	private static $options;
	public         $test = 0;

	function __construct () {

		require_once( 'piklist/piklist.php' );

		self::set_options();

		add_filter( 'piklist_admin_pages', array( $this, 'settings_page' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'login_css' ) );
		add_filter( 'register_form_fields', array( $this, 'remove_pw_field' ) );
		add_action( 'update_option_uabr_options', array( $this, 'options_updated' ), 30, 2 );

		add_action( 'admin_init', array( $this, 'set_rewrite_rules' ) );

	}

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function log($some_data){
		_log( $_SERVER['SCRIPT_FILENAME'] );
		_log( $_SERVER['QUERY_STRING'] );
		_log( $some_data );
	}

	public function options_updated ( $old_value, $new_value ) {
		_log($new_value);
		$this->set_rewrite_rules( $new_value, true );
		flush_rewrite_rules();
	}

	public function set_rewrite_rules ( $options = NULL, $force = false ) {

		if(!($_GET['page'] == 'uabr-settings' && $_GET['tab'] == 'rewrite') || $force = true) {

			$this->log('Setting rewrite rules! Was I forced?:' . $force);

			if ( ! $options ) $options = self::get_options();

			if ( $options['enable_login'][0] && $options['login_rewrite'] ) {
				add_rewrite_rule( $options['login_rewrite'] . '/?', 'wp-login.php', 'top' );
			}
			if ( $options['enable_lostpw'][0] && $options['lostpw_rewrite'] ) {
				add_rewrite_rule( $options['lostpw_rewrite'] . '/?', 'wp-login.php?action=lostpassword', 'top' );
			}
			if ( $options['enable_activate'][0] && $options['activate_rewrite'] ) {
				add_rewrite_rule( $options['activate_rewrite'] . '/([^/]*)/([^/]*)/', 'wp-login.php?action=rp&key=$2&login=$1', 'top' );
			}
			if ( $options['enable_register'][0] && $options['register_rewrite'] ) {
				add_rewrite_rule( $options['register_rewrite'] . '/?', 'wp-login.php?action=register', 'top' );
			}
		} else {
			$this->log('uh oh! im it!');
		}
	}

	/**
	 * @return mixed
	 */
	public static function get_options () {

		if ( ! self::$options ) self::$options = self::get_options();

		return self::$options;
	}

	/**
	 * @param mixed $options
	 */
	public static function set_options ( $options = NULL ) {

		if ( ! $options ) $options = get_option( 'uabr_options' );
		self::$options = $options;
	}

	public function remove_pw_field ( $fields ) {

		$options = self::get_options();

		if ( $options['remove_jobify_pw'][0] == 'enable' ) unset( $fields['creds']['password'] );

		return $fields;
	}

	public function settings_page ( $pages ) {

		$pages[] = array(
			'page_title'  => __( 'Activation Settings' ),
			'menu_title'  => __( 'Activation Settings' ),
			'capability'  => 'manage_options',
			'sub_menu'    => 'users.php',
			'menu_slug'   => 'uabr-settings',
			'setting'     => 'uabr_options',
			'single_line' => false,
			'default_tab' => 'General',
			'save_text'   => 'Save Activation Settings'
		);

		return $pages;

	}

	public function login_css () {

		$options = self::get_options();
		if ( $options['responsive_width'][0] == 'enable' ) {
			?>
			<style type="text/css">
		        @media (max-width: 1200px) {
			        #login { width: 90% !important; }
			        }

		        @media (min-width: 1200px) {
			        #login { width: 50% !important; }
			        }
		    </style>
		<?php
		}
	}

}