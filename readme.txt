** NOTICE ** this version will not function correctly yet, if you need a functional version please use the `piklist` branch.  This notice will be removed when the master branch is functional.

=== WP Login Flow ===
Contributors: tripflex
Donate link: https://www.gittip.com/tripflex
Tags: permalink, css, image, rewrite, wp-login, wp-login.php, custom, password, lost, forgot, customize, default, clean, form, redirect, template, logo, user, register, activate, reset, password, login, force, email, id, notification, registration, jobify, wp-job-manager, wp job manager, htaccess
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv3

Complete WP Login (wp-login.php) flow control.  Customize wp-login.php, require users to activate email, custom email templates, custom rewrite (login, register, activate), and more!

== Description ==

WP Login Flow is only plugin that will solve all of your wp-login needs.  Complete customization of the default wp-login.php page, with many other advanced features such as requiring users to activate their account via email, custom rewrite permalinks for all wp-login links, ability to force using custom permalinks instead of wp-login.php links (ie /register instead of wp-login.php?action=register), responsify the wp-login.php page, custom logo, link, background, login box, and more!

This plugin was created to keep things as clean, simple, and useful as possible.  The main bread and butter is the user email activation, which is integrated with the default password reset page.  What this means is users are required to "Activate" their account by using a link in the email to set their password.  Everyone knows email is insecure, so why would you ever email a user their password?  This plugin solves that problem, and helps you keep both you and your users safe.

= Features =

* Customized responsive wp-login.php page
* Require Email Activation (link in email standard WP password reset page to set users password)
* Users can request new activation/reset email by using standard password reset form
* Display "pending activation" error if pending account attempts to login (authenticate hook)
* Custom logo on wp-login page
* Custom link URL for logo on wp-login page
* Custom background on wp-login page
* Custom login box on wp-login page
* Custom user defined CSS on wp-login page
* Custom email templates (register, activate, lost/forgot password, etc)
* Custom Name and Email Address for all Wordpress emails sent
* Remove password field from register forms (Jobify, and others)
* Custom rewrite for login, register, activate, and lost/forgot password
* Force redirect from wp-login.php to custom permalinks
* Custom login redirect based on user role

[Read more about WP Login Flow](https://github.com/tripflex/wp-login-flow).

= Documentation =

Documentation will be maintained on the [GitHub Wiki here](https://github.com/tripflex/wp-login-flow/wiki).

= Contributing and reporting bugs =

You can contribute code and localizations to this plugin via GitHub: [https://github.com/tripflex/wp-login-flow](https://github.com/tripflex/wp-login-flow)

= Support =

If you spot a bug, you can of course log it on [Github](https://github.com/tripflex/wp-login-flow)

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

1. Tabbed configuration screen
2. Password Reset Page
3. Email Preview
4. Customized wp-login example

== Changelog ==

= 1.0.0 =
* - June 6, 2014
* Changed name to wp-login-flow
* Numerous bug fixes, and additional features added
* -
* - March 17, 2014
* Initial Creation
