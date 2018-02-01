<?php
use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

function get_random_state() {
    return "state5a728889bd8a9";
}

function get_random_nonce() {
    return "nonce5a728889bd91d";
}

add_action('wp_head', 'moatall_wsl_yahoojapan_login');
add_action('admin_head', 'moatall_wsl_yahoojapan_login');
add_action('login_head', 'moatall_wsl_yahoojapan_login');
function moatall_wsl_yahoojapan_login() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if ( empty($setting_data) || empty($setting_data['yahoojapan_details']) ) {
		return;
	}
	extract($setting_data['yahoojapan_details']);

    // set random state & random nonce.
    $random_state = get_random_state();
    $random_nonce = get_random_nonce();
?>

<script type="text/javascript">
window.yconnectInit = function() {
    YAHOO.JP.yconnect.Authorization.init({
        button: {
            format: "image",
            type: "a",
            textType:"a",
            width: 166,
            height: 30,
            className: "yconnectLogin"
        },
        authorization: {
            clientId: "<?php echo $yahoojapan_id; ?>",
            redirectUri: "<?php echo site_url(); ?>/moatall-wsl-callback",
            scope: "openid email",
            windowWidth: "500",
            windowHeight: "400",
            state: "<?php echo $random_state; ?>",
            nonce: "<?php echo $random_nonce; ?>"
        },
        autofill: {
            // name: "name",
            // email: "email",
            // address: "address"
        },
        onError: function(res) {
            alert("Error on login");
        },
        onCancel: function(res) {
            alert("Canceled");
        }
    });
};
(function(){
    var fs = document.getElementsByTagName("script")[0], s = document.createElement("script");
    s.setAttribute("src", "https://s.yimg.jp/images/login/yconnect/auth/1.0.3/auth-min.js");
    fs.parentNode.insertBefore(s, fs);
})();
</script>

<?php
}

function wsl_yahoojapan_callback() {
    // get random state and random nonce
    $random_state = get_random_state();
    $random_nonce = get_random_nonce();

    $setting_data = get_option('moatall_wsl_plugin_setting_data');
    if ( empty($setting_data) || empty($setting_data['yahoojapan_details']) ) {
        echo "Error at get_option";
        exit;
    }
    extract($setting_data['yahoojapan_details']);
    $redirect_uri  = site_url() . "/moatall-wsl-callback";

    $cred = new ClientCredential( $yahoojapan_id, $yahoojapan_secret );
    $client = new YConnectClient( $cred );
    try {
        $code_result = $client->getAuthorizationCode( $random_state );
        $client->requestAccessToken( $redirect_uri, $code_result );
        $access_token  = $client->getAccessToken();
        $refresh_token = $client->getRefreshToken();
    } catch ( TokenException $e ) {
        echo 'Error TokenException: ' . $e->getMessage();
        exit;
    }

    try {
        $verify_result = $client->verifyIdToken( $random_nonce );
        if ( $verify_result ) {
            // Success
            $id_token = $client->getIdToken();
        } else {
            // Failed
            echo "Failed on id token verification.";
            exit;
        }
    } catch ( Exception $e ) {
        echo "Caught Exception: " . $e->getMessage();
        exit;
    }

    try {
        $client->requestUserInfo( $access_token );
        $email = $client->getUserInfo()["email"];
        $is_logged_in = moatall_wsl_login_by_email($email, "yahoojapan");
        if ( $is_logged_in ) {
            $url = site_url() . "/my-account/";
            header("Location: {$url}");
            exit;
        } else {
            echo "Fail to login.";
        }
    } catch ( ApiException $e ) {
        echo "Caught ApiException: " . $e->getMessage();
        exit;
    }
}
?>
