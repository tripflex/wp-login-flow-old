<?php
/*
Title: <hr/><h2>Login Box</h2>
Setting: uabr_options
Tab: Style
Order: 1
*/

// Responsive Width
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'responsive_width',
	'help'    => 'This will enable responsive width on the default login/register/activate, and lost password pages.  Any screen above 1200px will be 50%, whereas below 1200px is 90%.  Default is 50% regardless of screen size.',
	'label'   => 'Responsive Width',
	'description' => 'Screen sizes above 1200px use default 50%, smaller screens use 90% width.',
	'choices' => array(
		'enable' => 'Enable'
	),
) );

// Background Color
piklist( 'field', array(
	'type'        => 'colorpicker',
	'field'       => 'login_box_bg_color',
	'label'       => __( 'Background Color' ),
	'help' => __( 'Set the default WordPress login box background color.  To remove click the color picker, click Clear, and then save.' )
) );

piklist( 'field', array(
	'type'        => 'number',
	'field'       => 'login_box_radius',
	'label'       => __( 'Border Radius' ),
	'help' => __( 'Set a custom border radius on the login box, will only work with modern browsers that support CSS3.' ),
	'description' => ' [field=login_box_radius_type]',
	'value'       => 0,
	'attributes'  => array(
		'class' => 'small-text'
	),
	'fields'      => array(
		array(
			'type'  => 'select',
			'field' => 'login_box_radius_type',
			'value' => '%',
			'embed' => true,
			'choices' => array(
				'%'  => '%',
				'px' => 'px'
			)
		)
	)
) );