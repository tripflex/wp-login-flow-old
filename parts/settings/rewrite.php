<?php
/*
Title: <h2>Custom Rewrite Settings</h2>
Setting: uabr_options
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
	'description' => 'Default: <code>' . home_url() . '/wp-login.php</code>',
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
	'description' => 'Default: <code>' . home_url() . '/wp-login.php?action=lostpassword</code>',
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
	'label'       => 'Register',
	'description' => 'Default: <code>' . home_url() . '/wp-login.php</code>',
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

// Register Fields
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_activate',
	'help'    => 'Enable this setting to rewrite the default activate URL',
	'description' => 'Default: <code>' . home_url() . '/wp-login.php?action=rp&key=sampleactivationcode&login=users@email.com</code>.  ',
	'label'   => 'Activate',
	'choices' => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=activate_rewrite]<code>/users@email.com/sampleactivationcode</code>'
	),
	'fields'  => array(
		array(
			'type'  => 'text',
			'field' => 'activate_rewrite',
			'value' => 'activate',
			'embed' => false
		)
	)
) );