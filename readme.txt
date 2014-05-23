=== User Activate by Reset ===
Contributors: tripflex
Donate link: https://www.gittip.com/tripflex
Tags: user, register, activate, reset, password, login, force, email, id, notification, registration
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 1.2.0
License: GPLv3

Require users to activate via email using the default WordPress password reset.

== Description ==

Use this plugin to require users to activate their email address, and then set their password once email has been activated.  This will replace the default wp_new_user_notification which normally emails the user with their password.

Once you activate this plugin it will instead send them an email similar to the password reset email, with a link to the default password reset page to set their password.

= Features =

* Require Email Activation
* Link in email standard WP password reset page to set users password
* Send new activation/reset email by using standard password reset form
* Display "pending activation" error if pending account attempts to login (authenticate hook)

= Planned Features =

* Customize default WordPress login (color, logo, etc)
* Custom activation email
* Custom pending activation notice
* Custom wp-login.php permalinks
* Admin notification options

[Read more about User Activate by Reset](https://github.com/tripflex/user-activate-by-reset).

= Documentation =

Documentation will be maintained on the [GitHub Wiki here](https://github.com/tripflex/user-activate-by-reset/wiki).

= Contributing and reporting bugs =

You can contribute code and localizations to this plugin via GitHub: [https://github.com/tripflex/user-activate-by-reset](https://github.com/tripflex/user-activate-by-reset)

= Support =

If you spot a bug, you can of course log it on [Github](https://github.com/tripflex/user-activate-by-reset)

Or contact me at myles@smyl.es

== Installation ==

= Automatic installation =

Install through Wordpress, select activate.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application.

* Download the plugin file to your computer and unzip it
* Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
* Activate the plugin from the Plugins menu within the WordPress admin.

== Screenshots ==

1. Password Reset Page
2. Email Preview

== Changelog ==

= 1.0.0 =
( March 17, 2014 )
* Initial Creation
