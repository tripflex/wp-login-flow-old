<?php
/*
Title: Custom Rewrite Settings
Setting: uabr_rewrite_settings
Order: 10
Tab: Rewrite
*/

$current_settings = get_option('uabr_rewrite_settings');
$lostpw_rewrite_as = $current_settings['lostpw_rewrite_as'];

piklist( 'field', array(
	'type'  => 'html',
	'label' => '',
	'value' => "
		<p>Below you will be able to configure custom rewrite rules for the default <code>wp-login.php</code> page.</p>
		<script>
			jQuery(function($){
				var home_url = $('.uabr_rewrite_settings_login_preview').val();
				var login_url_orig = $('#uabr_rewrite_settings_login_rewrite').val();
				var lostpw_url_orig = $('#uabr_rewrite_settings_lostpw_rewrite').val();

				function updateLoginRewritePreview(new_url, which){
					var login_url = home_url + new_url;
					$('.uabr_rewrite_settings_' + which + '_preview').val(login_url);
				}

				updateLoginRewritePreview(login_url_orig, 'login');
				updateLoginRewritePreview(lostpw_url_orig, 'lostpw');

				$('.uabr_rewrite_preview').attr('disabled', 'disabled');

				$('#uabr_rewrite_settings_login_rewrite').on('input', function(){
					updateLoginRewritePreview(this.value, 'login');
				});

				$('#uabr_rewrite_settings_lostpw_rewrite').on('input', function(){
					updateLoginRewritePreview(this.value, 'lostpw');
				});

			});
		</script>
	"
) );

// Login Rewrite
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_login',
	'help'    => 'Enable this setting to rewrite the default login URL',
	'label'   => 'Login',
	'attributes' => array(
		'class' => 'uabr_rewrite_input'
	),
	'choices' => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=login_rewrite]'
	),
	'fields'  => array(
		array(
			'type'  => 'text',
			'field' => 'login_rewrite',
			'value' => 'login',
			'embed' => true
		)
	)
) );

piklist( 'field', array(
	'type'        => 'text',
	'field'       => 'login_preview',
	'value'       => home_url('/'),
	'label'       => 'Login Preview',
    'attributes' => array(
	    'class' => 'large-text uabr_rewrite_preview'
        )
	)
);

// Login Fields
piklist( 'field', array(
	'type'    => 'checkbox',
	'field'   => 'enable_lostpw',
	'help'    => 'Enable this setting to rewrite the default lost password URL',
	'label'   => 'Lost Password',
	'choices' => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=lostpw_rewrite]'
	),
	'fields'  => array(
		array(
			'type'  => 'text',
			'field' => 'lostpw_rewrite',
			'value' => 'lost-password',
			'embed' => true
		)
	)
) );

piklist( 'field', array(
	'type'       => 'text',
	'field'      => 'lostpw_preview',
	'value'      => home_url( '/' ),
	'label'      => 'Lost Password Preview',
	'attributes' => array(
		'class' => 'large-text uabr_rewrite_preview'
	)
) );

// Register Fields
piklist( 'field', array(
	'type'        => 'checkbox',
	'field'       => 'enable_register',
	'help'        => 'Enable this setting to rewrite the default register URL',
	'label'       => 'Lost Password',
	'choices'     => array(
		'enable' => 'Enable<br><code>' . home_url() . '/</code>[field=register_rewrite]'
	),
	'fields'      => array(
		array(
			'type'  => 'text',
			'field' => 'register_rewrite',
			'value' => 'register',
			'embed' => false
		)
	)
) );

piklist( 'field', array(
	'type'       => 'text',
	'field'      => 'register_preview',
	'value'      => home_url( '/' ),
	'label'      => 'Lost Password Preview',
	'attributes' => array(
		'class' => 'large-text uabr_rewrite_preview'
	)
) );