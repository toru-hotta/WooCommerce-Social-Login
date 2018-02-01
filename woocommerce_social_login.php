<?php
/**
* Plugin Name: WooCommerce Social Login - Moatall
* Plugin URI: https://github.com/toru1055
* Description: Social Login plugin for WooCommerce.
* Version: 1.2.0
* Author: toru1055
* Text Domain: moatall_social_login
* Domain Path: /languages
* Author URI: https://github.com/toru1055
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/

ob_start();
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	session_start();
	// Put your plugin code here
	require_once('vendor/autoload.php');
	require_once('social_login_settings.php');
	require_once('my_account_login_hook.php');
	require_once('my_account_register_hook.php');
	require_once('login_page_hook.php');
	include_once('checkout_page_hook.php');

	add_action('admin_enqueue_scripts', 'updimg_scripts');
	function updimg_scripts() {
		if (isset($_GET['page']) && !empty($_GET['page'])) {
			if(sanitize_text_field($_GET['page'] == 'woo-social-login')) {
				wp_enqueue_media();
			}
		}
	}

	register_activation_hook(__FILE__, 'moatall_wsl_plugin_activation');
	function moatall_wsl_plugin_activation() {
		update_option( 'moatall_wsl_enable_plugin', 1 );
		$setting_data = get_option('moatall_wsl_plugin_setting_data');
		if (empty($setting_data)) {
			$social_setting = array(
				'facebook_details' => array(
					'enable_facebook' => '', 'facebook_id'=> '', 'fb_icon_url'=> '', 'facebook_secret'=> ''
				),
				'google_plus_details' => array(
					'enable_google_plus'=> '', 'google_id'=> '', 'google_icon_url'=> ''
				),
				'yahoojapan_details' => array(
					'enable_yahoojapan'=> '', 'yahoojapan_id'=> '', 'yahoojapan_icon_url'=> '', 'yahoojapan_secret'=> ''
				),
				'twitter_details' => array(
					'enable_twitter'=> '', 'twitter_id'=> '', 'twitter_icon_url'=> '', 'twitter_secret'=> ''
				),
				'change_text_details' => array('sign_in'=> '', 'sign_up'=> ''),
				'change_text_details' => array('login_label'=> '', 'checkout_label'=> '')
			);
			update_option('moatall_wsl_social_plugin', $social_setting);
		}
		add_rewrite_endpoint('moatall-wsl-callback', EP_ROOT);
		flush_rewrite_rules();
	}

	register_deactivation_hook(__FILE__, 'moatall_wsl_plugin_deactivation');
	function moatall_wsl_plugin_deactivation() {
		update_option( 'moatall_wsl_enable_plugin', 0 );
		flush_rewrite_rules();
	}

	add_action('init', 'moatall_wsl_init');
	function moatall_wsl_init() {
		add_rewrite_endpoint('moatall-wsl-callback', EP_ROOT);
	}

	add_action('template_redirect', 'moatall_wsl_template_redirect');
	function moatall_wsl_template_redirect() {
		global $wp_query;
		if ( isset( $wp_query->query['moatall-wsl-callback'] ) ) {
			if ( ! $wp_query->query['moatall-wsl-callback'] ) {
				include($dir."wsl_callback.php");
				exit;
			} else {
				$wp_query->set_404();
				status_header( 404 );
				return;
			}
		}
	}

	add_action('wp_head', 'moatall_social_login_lite_head');
	add_action('admin_head', 'moatall_social_login_lite_head');
	add_action('login_head', 'moatall_social_login_lite_head');
	function moatall_social_login_lite_head() {
?>
<style>
	.login-txt {
		margin: 4px 0 0 4px;
	}
	#logo-link {
		margin: 8px 3px;
	}
	.form-row.form-row-first.login-checkout {
		float: none;
		text-align: center;
		width: 100%;
	}	
	.social-login-button {
		margin: 1px 3px 5px 3px;
		border-radius: 3px;
		padding: 3px 10px;
		width: 165px;
		white-space: nowrap;
	}
	.social-login-button > a {
		color: #FFF;
	}
	.facebook-social-button {
		background-color: #4267b2;
	}
	.twitter-social-button {
		background-color: #1da1f2;
	}
	.google-social-button {
		margin: 8px 2px;
	}
	.yahoo-social-button {
		margin: 8px 2px 0px 2px;
	}
</style>
<?php
	}

	require_once('wsl_data_functions.php');
	require_once('wsl_facebook_login.php');
	require_once('wsl_google_login.php');
	require_once('wsl_yahoojapan_login.php');
	require_once('wsl_twitter_login.php');

	add_action('admin_menu', 'moatall_wsl_add_admin_menu');
	function moatall_wsl_add_admin_menu(){
		$plugin_dir_url =  plugin_dir_url(__FILE__);
		add_menu_page(
			'Moatall', 'Moatall', 'nosuchcapability', 
			'moatall-001', NULL, 
			$plugin_dir_url.'/images/logo-wp.png', 57
		);
		add_submenu_page(
			'moatall-001', 'Social Login', 'Social Login',
			'manage_options', 'woo-social-login', 'social_login_settings'
		);
	}
} else {
	add_action('admin_notices', 'moatall_social_login_admin_notices');
	function moatall_social_login_admin_notices() {
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			echo "<div class='error'><p>Please active WooCommerce First To Use WooCommerce Social Login</p></div>";
		}
	}
}
ob_clean();
?>
