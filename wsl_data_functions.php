<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

add_action('wp_head', 'wsl_data_send_script');
add_action('admin_head', 'wsl_data_send_script');
add_action('login_head', 'wsl_data_send_script');
function wsl_data_send_script() {
?>
<script>
	function wsl_data_send(profile_name, profile_email, social_type) {
		var str =  profile_name;
		str += "*" + profile_email;
		str += "*" + social_type;
		jQuery.post(
			'<?php echo site_url(); ?>/wp-admin/admin-ajax.php',
			{
				'action': 'wsl_data_revieve',
				'data': btoa(str) // We pass php values differently!
			},
			function ( response, status ) {
				if(response=='success') {
					<?php if( is_checkout() ): ?>
					window.location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					<?php else: ?>
					window.location.href = "<?php echo site_url();?>/my-account";
					<?php endif; ?>
				}
			}
		);
	}
</script>
<?php
}

function moatall_wsl_data_recieve( $profile_name, $profile_email, $social_type ) {
	if( !email_exists( $profile_email ) && username_exists( $profile_name ) ){
		$profile_id   = rand(1000, 9999);
		$profile_name = $profile_name.$profile_id;
	}
	$user_id = username_exists( $profile_name );
	if ( !$user_id and email_exists($profile_email) == false ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		$user_idd = wp_create_user( $profile_name, $random_password, $profile_email );
	}
	if( isset($user_idd) ) {
		$user1 = get_user_by('id',$user_idd);
		wp_set_current_user( $user1->ID, $user1->user_login );
		wp_set_auth_cookie( $user1->ID );
		$user_info = get_userdata( $user1->ID );
		do_action( 'wp_login', $user1->user_login, $user_info );
		$to = $profile_email;
		$sub = get_bloginfo( 'name' )." Details";
		$msg = make_mail_message_body( $profile_name, $random_password );
		$header = "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		wp_mail($to, $sub, $msg, $header);
		increment_counter( $social_type );
		return true;
	} else {
		$user1 = get_user_by( 'email', $profile_email);
		wp_set_current_user( $user1->ID, $user1->user_login );
		wp_set_auth_cookie( $user1->ID );
		$user_info = get_userdata( $user1->ID );
		do_action( 'wp_login', $user1->user_login, $user_info );
		increment_counter( $social_type );
		return true;												 
	}
	return false;
}

function increment_counter($social_type) {
	if ( 'google' == $social_type || 'facebook' == $social_type || 'yahoojapan' == $social_type ) {
		$counter_option_key = "moatall_wsl_" . $social_type . "_count";
		$counter = get_option( $counter_option_key );
		if ( $counter == '' ) {
			$counter = 0;
		}
		$counter = $counter + 1;
		update_option( $counter_option_key, $counter );
	}
}

function make_mail_message_body($profile_name, $random_password) {
	return '
	<div style="background-color:#f5f5f5;width:100%;margin:0;padding:70px 0 70px 0">
		<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td valign="top" align="center">
						<table width="600" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
							<tbody>
								<tr>
									<td valign="top" align="center">
										<table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#557da1" style="background-color:#557da1;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
											<tbody>
												<tr>
													<td>
													   <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:left;line-height:150%">'.get_bloginfo( 'name' ).' Login Details</h1>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td valign="top" align="center">
										<table width="600" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
														<table width="100%" cellspacing="0" cellpadding="20" border="0">
															<tbody>
																<tr>
																	<td valign="top">
																		<div style="color:#737373;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
																			<p>Your '. get_bloginfo( 'name' ).' Login Details Are:</p>
																			<p>Username: '.$profile_name.'</p>
																			<p>Password: '.$random_password.'<br/></p>
																		 </div>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td valign="top" align="center">
										<table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
											<tbody>
												<tr>
													<td valign="top">
														<table width="100%" cellspacing="0" cellpadding="10" border="0">
															<tbody>
																<tr>
																	<td valign="middle" style="border:0;color:#99b1c7;font-family:Arial;font-size:12px;line-height:125%;text-align:center" colspan="2">
																		<p>'.get_bloginfo( 'name' ).'</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>';
}
?>
