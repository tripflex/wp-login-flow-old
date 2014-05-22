<?php
/**
 * Plugin Name: User Activate by Password Reset
 * Plugin URI:  https://github.com/tripflex/user-activate-by-reset
 * Description: Use the default WordPress password reset as activation for new user
 * Author:      Myles McNamara
 * Contributors: Myles McNamara
 * Author URI:  http://smyl.es
 * Version:     1.1.0
 * Text Domain: user_activate_by_reset
 * GitHub Plugin URI: tripflex/user-activate-by-reset
 * GitHub Branch:   master
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Set the version of this plugin
if ( !defined( 'USER_ACTIVATE_BY_RESET' ) ) {
	define( 'USER_ACTIVATE_BY_RESET', '1.2.0' );
} // end if

class User_Activate_by_Reset {
	/**
	 * @var      string
	 */
	const activation_status = 'uabr_activated';
	public static $plugin_slug = 'user-activate-by-reset';
	private static $instance;

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'plugin_activate' ) );
		add_action( 'set_auth_cookie', array(
				$this,
				'set_auth_cookie'
			), 20, 5 );
		add_action( 'authenticate', array(
				$this,
				'login_check_activation'
			), 30, 3 );
		add_filter( 'plugin_row_meta', array(
			$this,
			'add_plugin_row_meta'
		), 10, 4 );

	}

	public static function setActivated( $user_id, $activated = true ) {
		if ( $activated ) {
			$activated = 'active';
		}
		if ( !$activated ) {
			$activated = 'pending';
		}
		update_user_option( $user_id, self::activation_status, $activated, true );
	}

	// Prevent auto login or other login forms from allowing user to login when pending activation

	public static function instance() {
		if ( !isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function plugin_activate() {
		if ( USER_ACTIVATE_BY_RESET != get_option( 'User_Activate_by_Reset' ) ) {
			update_option( 'User_Activate_by_Reset', USER_ACTIVATE_BY_RESET );
			$html = '<div class="updated">';
			$html .= '<p>';
			$html .= __( 'Activation Notice', self::$plugin_slug );
			$html .= '</p>';
			$html .= '</div>';

			echo $html;
		}
	}

	public static function plugin_deactivate() {
		delete_option( 'User_Activate_by_Reset' );
	}

	public function login_check_activation( $user, $username, $password ) {
		if ( strpos( $username, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $username ) );
		} else {
			$login = trim( $username );
			$user_data = get_user_by( 'login', $login );
		}

		$user_id = $user_data->ID;

		if ( !User_Activate_by_Reset::isActivated( $user_id ) ) {
			$user = new WP_Error();
			$user->add( 'pendingactivation', __( '<strong>ERROR</strong>: Your account is still pending activation, please check your email, or you can request a <a href="' . wp_lostpassword_url() . '">password reset</a> for a new activation code.' ) );
		}

		return $user;
	}

	public static function isActivated( $user_id ) {
		$status = get_user_option( self::activation_status, $user_id );
		if ( $status == 'active' ) {
			return true;
		} else {
			return false;
		}
	}

	public function set_auth_cookie( $auth_cookie, $expire, $expiration, $user_id, $scheme ) {
		// Exit function is user is already activated
		if ( User_Activate_by_Reset::isActivated( $user_id ) === false ) {
			$user = new WP_Error( $user_id );
			$user->add( 'pendingactivation', __( '<strong>ERROR</strong>: Your account is still pending activation, please check your email, or you can request a <a href="' . site_url( 'wp-login.php?action=lostpassword' ) . '">password reset</a> for a new activation code.' ) );
			wp_redirect( home_url( 'wp-login.php?checkemail=registered' ) );
			exit();
		}

	}

	public function add_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( self::$plugin_slug . '/' . self::$plugin_slug . '.php' == $plugin_file ) {
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://github.com/tripflex/' . self::$plugin_slug, self::$plugin_slug ), __( 'GitHub', self::$plugin_slug ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://wordpress.org/plugins/' . self::$plugin_slug, self::$plugin_slug ), __( 'Wordpress', self::$plugin_slug ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'https://www.transifex.com/projects/p/' . self::$plugin_slug . '/resource/' . self::$plugin_slug . '/', self::$plugin_slug ), __( 'Translate', self::$plugin_slug ) );
		}

		return $plugin_meta;
	}

}

// Prevent Wordpress from sending default notification, instead use our custom one
if ( !function_exists( 'wp_new_user_notification' ) ) {

	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
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
		User_Activate_by_Reset::setActivated( $user_id, false );

		$message = __( 'Thank you for registering your account:' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'In order to set your password and access the site, please visit the following address:' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

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

		@wp_mail(
			get_option( 'admin_email' ),
			sprintf( __( '[%s] New User Registration' ), get_option( 'blogname' ) ),
			$admin_message
		);

		$title = sprintf( __( '[%s] Account Activation' ), $blogname );
		if ( !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			return false;
		} else {
			return true;
		}
	}
}

if ( !function_exists( 'wp_password_change_notification' ) ) :
	/**
	 * Notify the blog admin of a user changing password, normally via email.
	 *
	 * @since 2.7.0
	 *
	 * @param object $user User Object
	 */
	function wp_password_change_notification( &$user ) {

		// Check is password reset was triggered by user activating account and setting password
		$is_activated = User_Activate_by_Reset::isActivated( $user->ID );
		// Must use === to differentiate between false and ''
		if ( $is_activated === false ) {
			User_Activate_by_Reset::setActivated( $user->ID );
			// Exit because we don't want to send admin a notification
			exit();
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

register_deactivation_hook( __FILE__, array(
	'User_Activate_by_Reset',
	'plugin_deactivate'
) );

add_action( 'init', array(
	'User_Activate_by_Reset',
	'instance'
) );