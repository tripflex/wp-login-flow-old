<?php
/*
Title: <hr/><h2>Customize Login Page</h2>
Setting: uabr_options
Tab: Style
*/

$options = get_option( 'uabr_options' );

// Background Color
piklist( 'field', array(
	'type'        => 'colorpicker',
	'field'       => 'login_bg_color',
	'label'       => __( 'Background Color' ),
	'help' => __( 'Set the default WordPress login background color.  To remove click the color picker, click Clear, and then save.' )
) );

// Header Logo URL Link Title
piklist( 'field', array(
	'type'       => 'text',
	'field'      => 'login_logo_url_title',
	'label'      => __( 'Logo URL Title' ),
	'help'       => __( 'Default the login logo link title is Powered by Wordpress, enter something here if you want to customize it.' ),
	'attributes' => array(
		'class' => 'regular-text'
	)
) );

// Header Logo URL Link
piklist( 'field', array(
	'type'        => 'text',
	'field'       => 'login_logo_url',
	'label'       => __( 'Logo URL' ),
	'help' => __( 'Default the login logo links to Wordpress.org, enter a URL here if you want to use a custom one.' ),
	'value'       => home_url(),
	'attributes'  => array(
		'class' => 'regular-text'
	)
) );

// Header Logo
$logo_args = array(
	'type'        => 'file',
	'field'       => 'login_logo',
	'label'       => 'Custom Logo',
	'help'        => 'You can add as many logos on this page as you would like, but only the logo farthest to the left will be used.  You can drag and drop to rearrange them.',
	'options'     => array(
		'title'  => 'Upload a custom Logo',
		'button' => 'Add a Logo Image'
	)
);

if($options['login_logo'][0]) $logo_args['description'] = 'Active Logo:';
piklist( 'field', $logo_args );

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