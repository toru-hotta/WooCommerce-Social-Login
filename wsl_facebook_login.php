<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

function moatall_wsl_facebook() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['facebook_details']) ) {
		return false;
	}
	extract($setting_data['facebook_details']);
	try {
		return new Facebook\Facebook([
			'app_id'                => $facebook_id,
			'app_secret'            => $facebook_secret,
			'default_graph_version' => 'v2.12',
		]);
	} catch( Facebook\Exceptions\FacebookSDKException $e ) {
		return false;
	}

}

function moatall_wsl_facebook_login_url() {
	$fb = moatall_wsl_facebook();
	if ( !$fb ) {
		return '#Error';
	}
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email'];
	$loginUrl = $helper->getLoginUrl(site_url() . "/moatall-wsl-callback/?social_type=facebook", $permissions);
	return htmlspecialchars($loginUrl);
}

function wsl_facebook_callback() {
	$fb = moatall_wsl_facebook();
	if ( !$fb ) {
		echo "Can't get facebook object.";
		exit;
	}

	$helper = $fb->getRedirectLoginHelper();
	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	if ( !isset($accessToken) ) {
		if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
		} else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		}
		exit;
	}

	try {
		$response = $fb->get('/me?fields=id,email,name', $accessToken);
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$user = $response->getGraphUser();
	if ( $user["email"] ) {
		moatall_wsl_data_recieve($user["email"], $user["email"], "facebook");
		$url = site_url() . "/my-account/";
		header("Location: {$url}");
	} else {
		echo "Can't get user's email.</ br>";
		exit;
	}
}
?>
