<?php
/*
Title: Email Customization
Setting: uabr_options
Tab: Email
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

piklist( 'field', array(
	'type'           => 'checkbox',
	'field'          => 'product_description',
	'label'          => __( 'Product Description' ),
	'description'    => __( 'Please describe this product' ),
	'help'           => __( 'Some help on this field.' ),
	'scope'          => 'post_meta',
	'field_template' => 'post_meta_custom',
	'value'          => 'my default',
	'capability'     => 'manage_options',
	'add_more'       => false,
	'choices'        => array(
		'first'  => 'First Choice',
		'second' => 'Second Choice',
		'third'  => 'Third Choice'
	),
	'list'           => true,
	'position'       => 'wrap',
	'serialize'      => 'false',
	'attributes'     => array(
		'title'       => 'field-demos',
		'alt'         => 'field-demos',
		'tabindex'    => '1',
		'class'       => 'small-text',
		'placeholder' => 'Placeholder text',
		'cols'        => 6,
		'columns'     => 6,
		'rows'        => 3,
		'size'        => 6,
		'step'        => 15,
		'min'         => 5,
		'onfocus'     => "if(this.value == 'default_value') { this.value = ''; }"
	)
) );