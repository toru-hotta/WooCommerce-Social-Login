<?php
use Abraham\TwitterOAuth\TwitterOAuth;

define('OAUTH_CALLBACK', site_url() . "/moatall-wsl-callback?social_type=twitter");

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

function moatall_wsl_twitter_login_url() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['twitter_details']) ) {
		return false;
	}
	extract($setting_data['twitter_details']);
	try {
		$connection = new TwitterOAuth($twitter_id, $twitter_secret);
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
		return htmlspecialchars($url);
	} catch(Exception $e) {
		return "#Error";
	}
}

function wsl_twitter_callback() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['twitter_details']) ) {
		return false;
	}
	extract($setting_data['twitter_details']);
	echo "Twitter callback is called.</ br>";
	$request_token = [];
	$request_token['oauth_token'] = $_SESSION['oauth_token'];
	$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
	if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
		echo "Abort! Something is wrong.";
		exit;
	}

	$connection = new TwitterOAuth($twitter_id, $twitter_secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
	$access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
	$_SESSION['access_token'] = $access_token;

	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth($twitter_id, $twitter_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$user = $connection->get('account/verify_credentials', 
		['tweet_mode' => 'extended', 'include_entities' => 'false', 'include_email'=> 'true']
	);
	if ( isset( $user->email ) ) {
		moatall_wsl_data_recieve($user->email, $user->email, "twitter");
		$url = site_url() . "/my-account/";
		header("Location: {$url}");
	} else {
		echo "Error: This application can't get email from twitter API.</ br>";
		exit;
	}
}

?>