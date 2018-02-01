<?php
if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

add_action('login_form', 'moatall_wsl_login_form_add');
function moatall_wsl_login_form_add() {
	$setting_data=get_option('moatall_wsl_plugin_setting_data');
	if (empty($setting_data)) {
		return;
	}
	extract($setting_data['facebook_details']);
	extract($setting_data['google_plus_details']);
	extract($setting_data['change_text_details']);
	extract($setting_data['yahoojapan_details']);
	extract($setting_data['twitter_details']);
?>
<style>
	.login-hook {
	}
	.login-hook > p {
	}
</style>
<?php 
	if($enable_facebook=='on'|| $enable_google_plus=='on' || $enable_yahoojapan=='on' || $enable_twitter=='on' ) {
?>
<div class='login-hook'>
	<?php if($enable_facebook=='on'){ ?>
	<p class="facebook-social-button social-login-button">
		<a href="<?php echo moatall_wsl_facebook_login_url() ?>">Sign in with Facebook</a>
	</p>
	
	<?php } if($enable_google_plus=='on'){ ?>
	<div class="google-social-button">
		<div class="g-signin2" data-onsuccess="onSignInGoogle" data-height="31" data-width="165"></div>
	</div>
	
	<?php } if($enable_yahoojapan=='on'){ ?>
	<div class="yahoo-social-button">
		<span class="yconnectLogin"></span>
	</div>
	
	<?php } if($enable_twitter=='on'){ ?>
	<p class="twitter-social-button social-login-button">
		<a href="<?php echo moatall_wsl_twitter_login_url() ?>">Sign in with Twitter</a>
	</p>
	<?php } ?>
</div>
<?php
	}
}
?>
