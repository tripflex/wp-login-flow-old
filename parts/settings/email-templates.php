<?php
/*
Title: <hr/><h2>Email Templates</h2>
Setting: uabr_options
Tab: Email
Order: 1
*/

// Activate Email Template
piklist( 'field', array(
	'type'        => 'editor',
	'field'       => 'activate_email',
	'label'       => __( 'Activation Email' ),
	'description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ),
	'value'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ),
	'options'     => array(
		'wpautop'          => true,
		'media_buttons'    => true,
		'tabindex'         => '',
		'editor_css'       => '',
		'editor_class'     => '',
		'teeny'            => false,
		'dfw'              => false,
		'tinymce'          => true,
		'quicktags'        => true,
		'drag_drop_upload' => true
	)
) );