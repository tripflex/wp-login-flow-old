<?php
/*
Title: <h1>Email Customization</h1>
Setting: wplf_options
Tab: Email
Order: 0
*/

// From Name
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_name',
	'help'    => 'If you want to use something other than WordPress, enable this field.',
	'label'   => 'From Name',
	'choices' => array(
		'enable' => 'Enable<br> [field=custom_name]'
	),
	'fields'  => array(
		array(
			'type'       => 'text',
			'field'      => 'custom_name',
			'value'      => 'WordPress',
			'embed'      => true,
			'attributes' => array(
				'class' => 'regular-text'
			),
		)
	)
) );

// From Email
piklist( 'field', array(
	'type'       => 'checkbox',
	'field'      => 'enable_email',
	'help'       => 'If you want to use something other than wordpress@' . WP_Login_Flow::get_domain() . ', enable this field.',
	'label'      => 'From Email',
	'choices'    => array(
		'enable' => 'Enable<br> [field=custom_email]'
	),
	'fields'     => array(
		array(
			'type'  => 'text',
			'field' => 'custom_email',
			'value' => 'wordpress@' . WP_Login_Flow::get_domain(),
			'embed' => true,
			'attributes' => array(
				'class' => 'regular-text'
			),
		)
	)
) );