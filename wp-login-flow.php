<?php
/**
 * Plugin Name: WP Login Flow
 * Plugin URI:  https://github.com/tripflex/wp-login-flow
 * Description: Complete WP Login (wp-login.php) flow control.  Customize wp-login.php, require users to activate email, custom email templates, custom rewrite (login, register, activate), and more!
 * Author:      Myles McNamara
 * Contributors: Myles McNamara
 * Author URI:  http://smyl.es
 * Version:     1.0.0
 * Plugin Type: Piklist
 * Text Domain: wp_login_flow
 * GitHub Plugin URI: tripflex/wp-login-flow
 * GitHub Branch:   master
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Set the version of this plugin
if ( ! defined( 'WP_LOGIN_FLOW' ) ) {
	define( 'WP_LOGIN_FLOW', '1.0.0' );
}

// Define plugin URL and path
define( 'WP_LOGIN_FLOW_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WP_LOGIN_FLOW_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

require_once( WP_LOGIN_FLOW_PLUGIN_DIR . '/options.php' );

/**
 * Class WP_Login_Flow
 * @version 1.0.0
 */
class WP_Login_Flow {

	const version           = '1.0.0';
	const activation_status = 'wplf_activated';
	const plugin_page       = 'wplf-settings';
	const plugin_slug       = 'wp-login-flow';
	public static  $wp_login    = 'wp-login.php';
	public static  $plugin_slug = 'wp-login-flow';
	public static  $locale_password_set;
	public static  $locale_pending_activation_notice;
	public static  $locale_thankyou_for_reg;
	public static  $mail_from;
	public static  $mail_from_name;
	private static $instance;
	private static $domain;

	public function __construct () {

		self::set_default_lang();
		add_action( 'admin_notices', array( $this, 'plugin_activate' ) );
		add_action( 'set_auth_cookie', array( $this, 'set_auth_cookie' ), 20, 5 );
		add_action( 'authenticate', array( $this, 'login_check_activation' ), 30, 3 );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 4 );
		add_filter( 'wp_login_errors', array( $this, 'wp_login_errors' ), 10, 2 );
		add_filter( 'wp_mail_from', array( $this, 'mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'mail_from_name' ) );
		WP_Login_Flow_Options::get_instance();

	}

	public static function set_default_lang () {

		self::set_locale_password_set( 'Thank you for activating your account and setting your password!' );
		self::set_locale_pending_activation_notice( '<strong>ERROR</strong>: Your account is still pending activation, please check your email, or you can request a <a href="' . wp_lostpassword_url() . '">password reset</a> for a new activation code.' );
		self::set_locale_thank_you_for_reg( 'Thank you for registering.  Please check your email for your activation link.<br><br>If you do not receive the email please request a <a href="' . wp_lostpassword_url() . '">password reset</a> to have the email sent again.' );
	}

	/**
	 * @return mixed
	 */
	public static function get_domain () {

		if ( ! self::$domain ) {
			$parse        = parse_url( home_url() );
			self::$domain = $parse['host'];
		}

		return self::$domain;
	}

	/**
	 * @return mixed
	 */
	public static function get_mail_from () {

		return self::$mail_from;
	}

	/**
	 * @param mixed $mail_from
	 */
	public static function set_mail_from ( $mail_from ) {

		self::$mail_from = $mail_from;
	}

	/**
	 * @return mixed
	 */
	public static function get_mail_from_name () {

		return self::$mail_from_name;
	}

	/**
	 * @param mixed $mail_from_name
	 */
	public static function set_mail_from_name ( $mail_from_name ) {

		self::$mail_from_name = $mail_from_name;
	}

	/**
	 * @param      $user_id
	 * @param bool $activated
	 */
	public static function set_activated ( $user_id, $activated = true ) {

		if ( $activated ) $activated = 'active';
		if ( ! $activated ) $activated = 'pending';
		update_user_option( $user_id, self::activation_status, $activated, true );
	}

	public static function instance () {

		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 *
	 */
	public static function plugin_activate () {

		if ( WP_LOGIN_FLOW != get_option( 'WP_Login_Flow' ) ) {
			update_option( 'WP_Login_Flow', WP_LOGIN_FLOW );
			$html = '<div class="updated">';
			$html .= '<p>';
			$html .= __( 'You are now requiring users to activate their
			email when registering.', self::$plugin_slug );
			$html .= '</p>';
			$html .= '</div>';

			echo $html;
		}
	}

	/**
	 *
	 */
	public static function plugin_deactivate () {

		delete_option( 'WP_Login_Flow' );
	}

	/**
	 * @return mixed
	 */
	public static function get_locale_password_set () {

		return self::$locale_password_set;
	}

	/**
	 * @param mixed $locale_password_set
	 *
	 * @since 1.0.0
	 */
	public static function set_locale_password_set ( $locale_password_set ) {

		self::$locale_password_set = __( $locale_password_set );
	}

	// Prevent auto login or other login forms from allowing user to login when pending activation

	public function mail_from ( $email ) {

		self::set_mail_from( $email );

		return $email;
	}

	public function mail_from_name ( $name ) {

		self::set_mail_from_name( $name );

		return $name;
	}

	/**
	 * @param $errors
	 * @param $redirect_to
	 *
	 * @return mixed
	 */
	public function wp_login_errors ( $errors, $redirect_to ) {

		if ( ( $_GET['registration'] == 'complete' ) && ( $_GET['activation'] == 'pending' ) ) {
			$errors->add( 'registered_activate', self::get_locale_thank_you_for_reg(), 'message' );
		}

		return $errors;
	}

	/**
	 * @return mixed
	 */
	public static function get_locale_thank_you_for_reg () {

		return self::$locale_thankyou_for_reg;
	}

	/**
	 * @param mixed $locale_thankyou_for_reg
	 */
	public static function set_locale_thank_you_for_reg ( $locale_thankyou_for_reg ) {

		self::$locale_thankyou_for_reg = __( $locale_thankyou_for_reg );
	}

	/**
	 * @param $user
	 * @param $username
	 * @param $password
	 *
	 * @return WP_Error
	 */
	public function login_check_activation ( $user, $username, $password ) {

		if ( strpos( $username, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $username ) );
		} else {
			$login     = trim( $username );
			$user_data = get_user_by( 'login', $login );
		}

		$user_id = $user_data->ID;

		if ( ! WP_Login_Flow::is_activated( $user_id ) ) {
			$user = new WP_Error();
			$user->add( 'pendingactivation', self::get_locale_pending_activation_notice() );
		}

		return $user;
	}

	/**
	 * @param $user_id
	 *
	 * @return bool
	 */
	public static function is_activated ( $user_id ) {

		$status = get_user_option( self::activation_status, $user_id );
		if ( $status == 'pending' ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @return mixed
	 */
	public static function get_locale_pending_activation_notice () {

		return self::$locale_pending_activation_notice;
	}

	/**
	 * @param mixed $locale_pending_activation_notice
	 *
	 */
	public static function set_locale_pending_activation_notice ( $locale_pending_activation_notice ) {

		self::$locale_pending_activation_notice = __( $locale_pending_activation_notice );
	}

	/**
	 * @param $auth_cookie
	 * @param $expire
	 * @param $expiration
	 * @param $user_id
	 * @param $scheme
	 */
	public function set_auth_cookie ( $auth_cookie, $expire, $expiration, $user_id, $scheme ) {

		// Exit function is user is already activated
		if ( ! WP_Login_Flow::is_activated( $user_id ) ) {
			wp_redirect( home_url( self::get_wplogin() . '?registration=complete&activation=pending' ) );
			exit();
		}

	}

	/**
	 * @return string
	 */
	public static function get_wplogin () {

		return self::$wp_login;
	}

	/**
	 * @param string $wp_login
	 */
	public static function set_wp_login ( $wp_login ) {

		self::$wp_login = $wp_login;
	}

	/**
	 * @param $plugin_meta
	 * @param $plugin_file
	 * @param $plugin_data
	 * @param $status
	 *
	 * @return array
	 */
	public function add_plugin_row_meta ( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( self::$plugin_slug . '/' . self::$plugin_slug . '.php' == $plugin_file ) {
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://github.com/tripflex/' . self::$plugin_slug, self::$plugin_slug ), __( 'GitHub', self::$plugin_slug ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://wordpress.org/plugins/' . self::$plugin_slug, self::$plugin_slug ), __( 'Wordpress', self::$plugin_slug ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'https://www.transifex.com/projects/p/' . self::$plugin_slug . '/resource/' . self::$plugin_slug . '/', self::$plugin_slug ), __( 'Translate', self::$plugin_slug ) );
		}

		return $plugin_meta;
	}

} // End WP_Login_Flow Class

// Prevent Wordpress from sending default notification, instead use our custom one
if ( ! function_exists( 'wp_new_user_notification' ) ) {

	/**
	 * @param        $user_id
	 * @param string $plaintext_pass
	 *
	 * @return bool
	 */
	function wp_new_user_notification ( $user_id, $plaintext_pass = '' ) {

		global $wpdb, $wp_hasher;

		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		// Generate something random for a password reset key.
		$key = wp_generate_password( 20, false );

		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}

		$hashed = $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

		// Set option needs to be activated
		WP_Login_Flow::set_activated( $user_id, false );

		$message = __( 'Thank you for registering your account:' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'In order to set your password and access the site, please visit the following address:' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( WP_Login_Flow::get_wplogin() . "?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

		if ( is_multisite() ) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
		{
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		// New User Admin Notification
		$admin_message = sprintf( __( 'New user registration on %s:' ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$admin_message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
		$admin_message .= sprintf( __( 'E-mail: %s' ), $user_email ) . "\r\n";

		@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration' ), get_option( 'blogname' ) ), $admin_message );

		$title = sprintf( __( '[%s] Account Activation' ), $blogname );
		if ( ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			return false;
		} else {
			return true;
		}
	}
}

if ( ! function_exists( 'wp_password_change_notification' ) ) :
	/**
	 * Notify the blog admin of a user changing password, normally via email.
	 *
	 * @since 2.7.0
	 *
	 * @param object $user User Object
	 */
	/**
	 * @param object $user
	 */
	function wp_password_change_notification ( &$user ) {

		// Check is password reset was triggered by user activating account and setting password
		if ( ! WP_Login_Flow::is_activated( $user->ID ) ) {
			WP_Login_Flow::set_activated( $user->ID );
			login_header( __( 'Password Saved' ), '<p class="message reset-pass">' . WP_Login_Flow::get_locale_password_set() . '<br>You can now <a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log in' ) . '</a></p>' );
			login_footer();
			exit;
		}

		// send a copy of password change notification to the admin
		// but check to see if it's the admin whose password we're changing, and skip this
		if ( 0 !== strcasecmp( $user->user_email, get_option( 'admin_email' ) ) ) {
			$message = sprintf( __( 'Password Lost and Changed for user: %s' ), $user->user_login ) . "\r\n";
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] Password Lost/Changed' ), $blogname ), $message );
		}
	}
endif;

register_deactivation_hook( __FILE__, array( 'WP_Login_Flow', 'plugin_deactivate' ) );

add_action( 'init', array( 'WP_Login_Flow', 'instance' ) );