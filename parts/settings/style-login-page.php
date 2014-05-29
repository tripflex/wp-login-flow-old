<?php
/*
Title: Login Page
Setting: uabr_options
Tab: Style
*/

// Background Color
piklist( 'field', array(
	'type'        => 'colorpicker',
	'field'       => 'login_bg_color',
	'label'       => __( 'Background Color' ),
	'help' => __( 'Set the default WordPress login background color.  To remove click the color picker, click Clear, and then save.' )
) );

// Header Logo
piklist( 'field', array(
	'type'        => 'file',
	'field'       => 'login_logo',
	'label'       => 'Custom Logo',
	'description' => '',
	'options'     => array(
		'basic'  => true
	)
) );

// Custom
piklist( 'field', array(
	'type'        => 'textarea',
	'field'       => 'login_custom_css',
	'label'       => __( 'Custom CSS' ),
	'help' => __( 'You can add any custom CSS you want for the login page in this box.  Enter the custom CSS just like you would in a CSS file.' ),
	'attributes'  => array(
		'rows'  => 10,
		'cols'  => 40,
		'tabindex' => '4',
		'class' => 'text'
	)
) );