<?php
/*
Title: <h1>Email Customization</h1>
Setting: uabr_options
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
	'help'       => 'If you want to use something other than wordpress@' . User_Activate_by_Reset::getDomain() . ', enable this field.',
	'label'      => 'From Email',
	'choices'    => array(
		'enable' => 'Enable<br> [field=custom_email]'
	),
	'fields'     => array(
		array(
			'type'  => 'text',
			'field' => 'custom_email',
			'value' => 'wordpress@' . User_Activate_by_Reset::getDomain(),
			'embed' => true,
			'attributes' => array(
				'class' => 'regular-text'
			),
		)
	)
) );