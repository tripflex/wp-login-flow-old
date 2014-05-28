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
	private static $options;
	public         $test = 0;

	function __construct () {

		require_once( 'piklist/piklist.php' );

		self::set_options();

		add_filter( 'piklist_admin_pages', array( $this, 'settings_page' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'login_css' ) );
		add_filter( 'register_form_fields', array( $this, 'remove_pw_field' ) );
		add_action( 'update_option_uabr_options', array( $this, 'options_updated' ), 30, 2 );

		add_action( 'wp_loaded', array( $this, 'set_rewrite_rules' ) );

	}

	public static function debug ( $type = 'msg', $header = __CLASS__, $message = '', $file = NULL, $line = NULL ) {

		if ( ! isset( $GLOBALS['DebugMyPlugin'] ) ) {
			return;
		}
		switch ( strtolower( $type ) ):
			case 'pr':
				$GLOBALS['DebugMyPlugin']->panels['main']->addPR( $header, $message, $file, $line );
				break;
			default:
				$GLOBALS['DebugMyPlugin']->panels['main']->addMessage( $header, $message, $file, $line );
		endswitch;
	}

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function options_updated ( $old_value, $new_value ) {

		$this->set_rewrite_rules();
		flush_rewrite_rules();
	}

	public function set_rewrite_rules ( $options = NULL ) {

		flush_rewrite_rules(false);

		if ( ! $options ) $options = self::get_options();

		if ( $options['enable_login'][0] == 'enable' && $options['login_rewrite'] ) {
			add_rewrite_rule( $options['login_rewrite'] . '/?', 'wp-login.php', 'top' );
		}
		if ( $options['enable_lostpw'][0] == 'enable' && $options['lostpw_rewrite'] ) {
			add_rewrite_rule( $options['lostpw_rewrite'] . '/?', 'wp-login.php?action=lostpassword', 'top' );
		}
		if ( $options['enable_activate'][0] == 'enable' && $options['activate_rewrite'] ) {
			add_rewrite_rule( $options['activate_rewrite'] . '/([^/]*)/([^/]*)/', 'wp-login.php?action=rp&key=$2&login=$1', 'top' );
		}
		if ( $options['enable_register'][0] == 'enable' && $options['register_rewrite'] ) {
			add_rewrite_rule( $options['register_rewrite'] . '/?', 'wp-login.php?action=register', 'top' );
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