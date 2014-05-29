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

require_once( 'piklist/piklist.php' );

class User_Activate_by_Reset_Options extends User_Activate_by_Reset {

	private static $debug = true;
	private static $instance;
	private static $options;

	function __construct () {

		self::set_options();

		add_filter( 'piklist_admin_pages', array( $this, 'settings_page' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'login_css' ) );
		add_filter( 'register_form_fields', array( $this, 'remove_pw_field' ) );
		add_filter( 'login_headerurl', array( $this, 'change_logo_url' ) );
		add_filter( 'login_headerurl', array( $this, 'change_logo_url_title' ) );
		add_action( 'update_option_uabr_options', array( $this, 'options_updated' ), 30, 2 );

		add_action( 'admin_init', array( $this, 'preserve_rewrite_rules' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'death_to_heartbeat' ), 1 );
		// Remove Piklist from WP menu
		add_action( 'admin_menu', array($this, 'remove_menus') );

	}

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function change_logo_url ($default_url){
		$custom_url = $this->get_option('login_logo_url');
		if($custom_url) $default_url = $custom_url;
		return $default_url;
	}

	public function change_logo_url_title ($default_url_title){
		$custom_url_title = $this->get_option('login_logo_url_title');
		if($custom_url_title) $default_url_title = $custom_url_title;
		return $default_url_title;
	}

	public function get_option($option){
		$option_data = self::get_options();

		return $option_data[$option];
	}

	public function remove_menus(){
		remove_menu_page( 'piklist' );
	}

	public function options_updated ( $old_value, $new_value ) {

		$this->log( 'Options Updated!', 'Options', false );
		$this->parse_options_updated( $new_value );
	}

	public function log ( $message, $title = NULL, $spacer = true ) {

		global $pagenow;
		if(!$pagenow){
			$filename = $_SERVER['SCRIPT_FILENAME'];
		} else {
			$filename = $pagenow;
		}

		if ( self::$debug && WP_DEBUG === true) {
			$query    = $_SERVER['QUERY_STRING'];
			$date_ident = date( 's' );
			$date_title = $date_ident . ' - ';
			$plugin_title = '[' . User_Activate_by_Reset::plugin_page . ']:' . $date_title;

			if ( $title ) $title = '{' . strtolower($title) . '}> ';
			if ( $spacer ) error_log( $plugin_title . '   ---   ' . $date_ident . '   ---   ' );
			if ( $filename ) error_log( $plugin_title . ':: {file/page}> '  . $filename );
			if ( $query ) error_log( $plugin_title . ':: {args}> ' . $query );

			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( $plugin_title . $title . 'Array/Object:' );
				error_log( print_r( $message, true ) );
			} else {
				error_log( $plugin_title . $title . strtolower($message) );
			}
		}
	}

	public function parse_options_updated ( $options ) {

		$this->log( 'Parsing Options' );
		$this->set_rewrite_rules( $options );
		flush_rewrite_rules();
	}

	public function set_rewrite_rules ( $options = NULL ) {

		$this->log( 'Setting rewrite rules!', 'Settings', false );

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
	}

	/**
	 * @return mixed
	 */
	public static function get_options () {

		if ( ! self::$options ) self::$options = self::set_options();

		return self::$options;
	}

	/**
	 * @param mixed $options
	 */
	public static function set_options ( $options = NULL ) {

		if ( ! $options ) $options = get_option( 'uabr_options' );
		self::$options = $options;

		return self::$options;
	}

	public function death_to_heartbeat () {

		global $pagenow;
		//		$this->log('Death to Heartbeat @ ' . $pagenow);
		if ( $pagenow == 'users.php' && $_GET['page'] == parent::plugin_page ) wp_deregister_script( 'heartbeat' );
	}

	public function preserve_rewrite_rules () {

		global $pagenow;
		$this->log(__FUNCTION__, 'function');

		if ( ! ( $_GET['page'] == parent::plugin_page && $_GET['tab'] == 'rewrite' ) && ! ( $pagenow == 'options.php' ) && ! ( $pagenow == 'users.php' ) ) {
			$this->set_rewrite_rules();
		}
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
			'menu_slug'   => parent::plugin_page,
			'setting'     => 'uabr_options',
			'single_line' => false,
			'default_tab' => 'General',
			'save_text'   => 'Save Activation Settings'
		);

		return $pages;

	}

	public function login_css () {

		$options = self::get_options();

		?>

		<style type="text/css">

			<?php if ( $options['responsive_width'][0] == 'enable' ): ?>
			        @media (max-width: 1200px) { #login { width: 90% !important; } }
			        @media (min-width: 1200px) { #login { width: 50% !important; } }
			<?php endif; ?>

			<?php if ( $options['login_bg_color'] ): ?>
					body { background-color: <?php echo $options['login_bg_color']; ?> !important; }
			<?php endif; ?>

			<?php
				$login_form_css = '#login form {';
				if ( $options['login_box_bg_color'] ){
					$login_form_css .= 'background-color: ' . $options['login_box_bg_color'] . ' !important;';
				}
				if ( $options['login_box_radius'] ){
					$login_form_css .= 'border-radius: ' . $options['login_box_radius'] . $options['login_box_radius_type'] .' !important;';
				}
				$login_form_css .= '}';
				echo sanitize_text_field($login_form_css);
			?>

			<?php if($options['login_custom_css']) echo sanitize_text_field($options['login_custom_css']);

			?>
			<?php
				if ( $options['login_logo'] ):
					if(is_array($options['login_logo'])):
						$logo_id = $options['login_logo'][0];
					else:
						$logo_id = $options['login_logo'];
					endif;
					$logo_url = wp_get_attachment_image_src($logo_id, 'medium');
			?>
					body.login div#login h1 a {
						background-image: url('<?php echo $logo_url[0]; ?>');
						width: <?php echo $logo_url[1]; ?>px;
						height: <?php echo $logo_url[2]; ?>px;
						background-size: auto;
					}

			<?php endif; ?>
		</style>
<?php

	} // End login_css

} // End User_Activate_by_Reset_Options