<?php
/*
Title: Custom CSS
Setting: uabr_options
Tab: Style
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