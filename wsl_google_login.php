<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

add_action('wp_head', 'moatall_wsl_google_login');
add_action('admin_head', 'moatall_wsl_google_login');
add_action('login_head', 'moatall_wsl_google_login');
function moatall_wsl_google_login() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['google_plus_details']) ) {
		return;
	}
	extract($setting_data['google_plus_details']);
?>

<meta name="google-signin-scope" content="profile email">
<meta name="google-signin-client_id" content="<?php echo $google_id;?>">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<script>
function signOutGoogle(redirect_url) {
	gapi.auth2.getAuthInstance().signOut().then(function() {
		window.location.href = redirect_url;
	});
};

function onSignInGoogle(googleUser) {
	var id_token = googleUser.getAuthResponse().id_token;
	jQuery.post(
		'<?php echo site_url(); ?>/moatall-wsl-callback?social_type=google',
		{'idtoken': id_token},
		function ( response, status ) {
			if(response == 'success') {
				var redirect_url = "<?php echo site_url();?>/my-account";
				<?php if( is_checkout() ): ?>
					redirect_url = "<?php echo $_SERVER['REQUEST_URI']; ?>";
				<?php endif; ?>
				signOutGoogle(redirect_url);
			} else {
				alert("Error at google sign-in.: " + response);
			}
		}
	);
};
</script>

<?php
}

function wsl_google_callback() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['google_plus_details']) ) {
		return;
	}
	extract($setting_data['google_plus_details']);
	
	$id_token = $_POST["idtoken"];
	$client = new Google_Client(['client_id' => $google_id]);
	$payload = $client->verifyIdToken($id_token);
	if ($payload) {
		$userid = $payload['sub'];
		moatall_wsl_data_recieve($payload['email'], $payload['email'], "google");
		echo "success";
	} else {
		echo "invalid id token";
	}
}
?>
