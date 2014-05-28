<?php
/*
Title: General
Setting: uabr_options
*/

// Responsive Width
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'remove_jobify_pw',
	'help'    => 'This option will filter out the password field from the registration form when using the Jobify theme.',
	'label'   => 'Jobify Password Field',
	'choices' => array(
		'enable' => 'Remove'
	),
) );