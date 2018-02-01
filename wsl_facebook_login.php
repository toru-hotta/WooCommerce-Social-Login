<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

define('FACEBOOK_OAUTH_CALLBACK', site_url() . "/moatall-wsl-callback/?social_type=facebook");

add_action('wp_head', 'moatall_wsl_facebook_login');
add_action('admin_head', 'moatall_wsl_facebook_login');
add_action('login_head', 'moatall_wsl_facebook_login');
function moatall_wsl_facebook_login() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['facebook_details']) ) {
		return;
	}
	extract($setting_data['facebook_details']);
?>
<script>
	logInWithFacebook = function() {
		FB.login(function(response) {
			if (response.authResponse) {
				alert('You are logged in & cookie set!');
				FB.api('/me?fields=email,name', function(response) {
					alert(response.name + ", " + response.email);
				});
				location.href = "<?php echo FACEBOOK_OAUTH_CALLBACK; ?>";
			} else {
				alert('User cancelled login or did not fully authorize.');
			}
		}, {scope: 'email,public_profile'});
		return false;
	};

	window.fbAsyncInit = function() {
		FB.init({
			appId: '<?php echo $facebook_id; ?>',
			cookie: true,
			version: 'v2.11'
		});
	};

	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/ja_JP/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<?php
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

	$helper = $fb->getJavaScriptHelper();
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
		header('HTTP/1.0 401 Unauthorized');
		echo "Can't get access token.";
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
		echo "Fatal error: This application can't get email address from facebook API.";
		echo "Tell this error to the site owner.";
		exit;
	}
}
?>
