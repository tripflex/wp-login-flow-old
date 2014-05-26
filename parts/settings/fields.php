<?php
/*
Title: Custom Rewrite Settings
Setting: uabr_rewrite_settings
Order: 10
Tab: Rewrite
*/


piklist( 'field', array( 'type'  => 'html',
                         'label' => '',
                         'value' => '<p>Below you will be able to configure custom rewrite rules for the default <code>wp-login.php</code> page.</p>'
) );

// Login Fields
piklist( 'field', array( 'type'    => 'checkbox',
                         'field'   => 'enable_login_rewrite',
                         'help'    => 'Enable custom rewrite rule for ' . home_url( 'wp-login.php' ),
                         'label'   => 'Login Rewrite',
                         'choices' => array(
	                         'enable' => 'Enable [field=login_rewrite_as]'
                         ),
                         'fields' => array(array('type' => 'text', 'field' => 'login_rewrite_as', 'value' => 'loginz', 'embed' => true))
) );

piklist( 'field', array( 'type'        => 'text',
                         'field'       => 'login_rewrite_slug',
                         'label'       => 'Rewrite As',
                         'value'       => 'login',
                         'help'        => 'Enter the custom value you would like to use in this field.  Only use the value you want to use, ie login.',
                         'description' => 'Rewrite ' . home_url( 'wp-login.php' ) . ' as ' . home_url( 'login' ),
                         'conditions'  => array(
	                         array( 'field' => 'enable_login_rewrite_0',
                                     'value' => ':any',
	                                 'update' => 'enable',
	                                 'type' => 'update'
                                   ),
	                         array( 'field' => 'enable_login_rewrite_0',
	                                'value' => '',
	                                'update' => null,
	                                'type' => 'update'
	                         )
                         )
) );

// Forgot Password Fields
piklist( 'field', array( 'type'    => 'checkbox',
                         'field'   => 'enable_lostpw_rewrite',
                         'help'    => 'Enable custom rewrite rule for default WordPress lost password URL',
                         'label'   => 'Lost Password Rewrite',
                         'choices' => array( 'enable' => 'Enable'
                         )
) );

piklist( 'field', array( 'type'        => 'text',
                         'field'       => 'lostpw_rewrite_slug',
                         'label'       => 'Rewrite As',
                         'value'       => 'lost-password',
                         'help'        => 'If you use the default value, lost-password, this would rewrite yourdomain.com/wp-login.php?action=lostpassword to yourdomain.com/lost-password',
                         'description' => 'Custom rewrite rule for yourdomain.com/wp-login.php?action=lostpassword',
                         'conditions'  => array( array( 'field' => 'enable_lostpw_rewrite',
                                                        'value' => 'enable'
                                                 )
                         )
) );