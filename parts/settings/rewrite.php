<?php
/*
Title: Custom Rewrite Settings
Setting: uabr_options
Order: 10
Tab: Rewrite
*/

piklist( 'field', array(
	'type'  => 'html',
	'label' => '',
	'value' => '<p>Below you will be able to configure custom rewrite rules for the default <code>wp-login.php</code> page.</p>'
) );

// Login Rewrite
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_login',
	'help'    => 'Enable this setting to rewrite the default login URL',
	'label'   => 'Login',
	'attributes' => array(
		'class' => 'uabr_rewrite_input'
	),
	'choices' => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=login_rewrite]'
	),
	'fields'  => array(
		array(
			'type'  => 'text',
			'field' => 'login_rewrite',
			'value' => 'login',
			'embed' => true
		)
	)
) );

// Login Fields
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_lostpw',
	'help'    => 'Enable this setting to rewrite the default lost password URL',
	'label'   => 'Lost Password',
	'choices' => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=lostpw_rewrite]'
	),
	'fields'  => array(
		array(
			'type'  => 'text',
			'field' => 'lostpw_rewrite',
			'value' => 'lost-password',
			'embed' => true
		)
	)
) );

// Register Fields
piklist( 'field', array(
	'type'        => 'checkbox',
	'field'       => 'enable_register',
	'help'        => 'Enable this setting to rewrite the default register URL',
	'label'       => 'Lost Password',
	'choices'     => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=register_rewrite]'
	),
	'fields'      => array(
		array(
			'type'  => 'text',
			'field' => 'register_rewrite',
			'value' => 'register',
			'embed' => false
		)
	)
) );