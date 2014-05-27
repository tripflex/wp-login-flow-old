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

		require_once( 'piklist/piklist.php' );

		// Flush rewrite rules each time the options page is loaded
		add_action( 'init', function () {
			global $wp_rewrite;
			add_rewrite_rule( '^login/?', 'wp-login.php', 'top' );
			add_rewrite_rule( '^activate/([^/]*)/([^/]*)/', 'wp-login.php?action=rp&key=$2&login=$1', 'top' );
			//	$wp_rewrite->flush_rules(false);
		} );

		add_filter( 'piklist_admin_pages', array($this, 'settings_page') );
	}

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function settings_page( $pages ){

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
}