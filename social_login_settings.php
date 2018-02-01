<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function social_login_settings() {
	$setting_data = get_option('moatall_wsl_plugin_setting_data');
	if(!empty($setting_data)) {
		extract($setting_data['facebook_details']);
		extract($setting_data['google_plus_details']);
		extract($setting_data['yahoojapan_details']);
		extract($setting_data['twitter_details']);
		extract($setting_data['change_text_details']);
		$login_label = trim($login_label);
		$checkout_label = trim($checkout_label);
	}
?>

<div class="wrap">
	<?php $tab2 = sanitize_text_field( $_GET['tab2'] );	?>
	<h2> <?php _e('WooCommerce Social Login - Plugin Options','moatall_social_login'); ?> </h2>
	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a class="nav-tab <?php if($tab2 == 'general' || $tab2 == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=woo-social-login&amp;tab2=general">
			<?php _e('General','moatall_social_login'); ?>	
		</a>
		<a class="nav-tab <?php if($tab2 == 'reports'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=woo-social-login&amp;tab2=reports">
			<?php _e('Reports','moatall_social_login'); ?>
		</a>
	</h2>
	<div class="icon32" id="icon-users"><br></div>
	<?php if($tab2 == 'general' || $tab2 == '') { ?>
	<form  method="post" action="" id="all_social_setting" enctype="multipart/form-data">
		<?php wp_nonce_field( 'all_social_setting_action', 'social_setting_form_nonce_field' ); ?>
		<table class="form-table"><tbody><tr valign="top" class=""></tr></tbody></table>
		<hr />
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wsl_social_label"><?php _e('Label','moatall_social_login'); ?></label>
					</th>
					<td class="forminp forminp-text">
						<input type="text"  value="<?php echo !empty($login_label)?$login_label:''?>" id="wsl_social_label" name="wsl_social_label" size="60" > 
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wsl_social_label_checkout"><?php _e('Description in checkout page','moatall_social_login'); ?></label>
					</th>
					<td class="forminp forminp-text">
						<input type="text" value="<?php echo !empty($checkout_label)?$checkout_label:''?>"  id="wsl_social_label_checkout" name="wsl_social_label_checkout" size="60" > 
					</td>
				</tr>
			</tbody>
		</table>
		<hr />
		<h3><?php _e('Facebook settings','moatall_social_login'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top" class="">
					<th class="titledesc" scope="row"><?php _e('Enable Facebook Login','moatall_social_login'); ?></th>
					<td class="forminp forminp-checkbox">
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php _e('Enable Facebook Login','moatall_social_login'); ?></span>
							</legend>
							<label for="wsl_facebook_enable">
								<input type="checkbox" id="wsl_facebook_enable"  name="wsl_facebook_enable" <?php echo (isset($enable_facebook) && $enable_facebook != '')?'checked':'';?>/> 
							</label> 
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wsl_facebook_id"><?php _e('Facebook App Id','moatall_social_login'); ?></label>
					</th>
					<td class="forminp forminp-text">
						<input type="text"  value="<?php echo isset($facebook_id)?$facebook_id:''?>"  id="wsl_facebook_id" name="wsl_facebook_id" size="60" > 						
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="wsl_facebook_secret"><?php _e('Facebook App secret','moatall_social_login'); ?></label>
					</th>
					<td class="forminp forminp-text">
						<input type="text"  value="<?php echo isset($facebook_secret)?$facebook_secret:''?>"  id="wsl_facebook_secret" name="wsl_facebook_secret" size="60" > 						
					</td>
				</tr>
			</tbody>
		</table>
		<hr />
		<h3><?php _e('Google settings','moatall_social_login'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top" class="">
					<th class="titledesc" scope="row"><?php _e('Enable Google Login','moatall_social_login'); ?></th>
					<td class="forminp forminp-checkbox">
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php _e('Enable Google Login','moatall_social_login'); ?></span>
							</legend>
							<label for="wsl_google_enable">
								<input type="checkbox"  id="wsl_google_enable" name="wsl_google_enable" 
									<?php echo (isset($enable_google_plus) && $enable_google_plus != '')?'checked':'';?>/>
							</label>
							</fieldset>								
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="wsl_google_id"><?php _e('Google client id','moatall_social_login'); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text"  value="<?php echo isset($google_id)?$google_id:'';?>" 
								id="wsl_google_id" name="wsl_google_id" size="60" > 						
						</td>
					</tr>
				</tbody>
		</table>

 
		<hr />
		<h3><?php _e('Yahoo! JAPAN settings','moatall_social_login'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top" class="">
					<th class="titledesc" scope="row"><?php _e('Enable Yahoo! JAPAN Login','moatall_social_login'); ?></th>
					<td class="forminp forminp-checkbox">
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php _e('Enable Yahoo! JAPAN Login','moatall_social_login'); ?></span>
							</legend>
							<label for="wsl_yahoojapan_enable">
								<input type="checkbox"  id="wsl_yahoojapan_enable" name="wsl_yahoojapan_enable" 
									<?php echo (isset($enable_yahoojapan) && $enable_yahoojapan != '')?'checked':'';?>/>
							</label>
							</fieldset>								
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="wsl_yahoojapan_id"><?php _e('Yahoo! JAPAN client id','moatall_social_login'); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text"  value="<?php echo isset($yahoojapan_id)?$yahoojapan_id:'';?>" 
								id="wsl_yahoojapan_id" name="wsl_yahoojapan_id" size="60" > 						
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="wsl_yahoojapan_secret"><?php _e('Yahoo! JAPAN client secret','moatall_social_login'); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text"  value="<?php echo isset($yahoojapan_secret)?$yahoojapan_secret:'';?>" 
								id="wsl_yahoojapan_secret" name="wsl_yahoojapan_secret" size="60" >
						</td>
					</tr>
				</tbody>
		</table>

		<hr />
		<h3><?php _e('Twitter settings','moatall_social_login'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top" class="">
					<th class="titledesc" scope="row"><?php _e('Enable Twitter Login','moatall_social_login'); ?></th>
					<td class="forminp forminp-checkbox">
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php _e('Enable Twitter Login','moatall_social_login'); ?></span>
							</legend>
							<label for="wsl_twitter_enable">
								<input type="checkbox"  id="wsl_twitter_enable" name="wsl_twitter_enable" 
									<?php echo (isset($enable_twitter) && $enable_twitter != '')?'checked':'';?>/>
							</label>
							</fieldset>								
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="wsl_twitter_id"><?php _e('Twitter client id','moatall_social_login'); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text"  value="<?php echo isset($twitter_id)?$twitter_id:'';?>" 
								id="wsl_twitter_id" name="wsl_twitter_id" size="60" > 						
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="wsl_twitter_secret"><?php _e('Twitter client secret','moatall_social_login'); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text"  value="<?php echo isset($twitter_secret)?$twitter_secret:'';?>" 
								id="wsl_twitter_secret" name="wsl_twitter_secret" size="60" >
						</td>
					</tr>
				</tbody>
		</table>


		<input type="submit" value="Save Changes" name="save_data" class="button button-primary" 
			style="float: left; margin-right: 10px;">
<!-- 		<input type="button" id="reset_default_all" value="Reset Default" name="save_data" class="button button-info" 
			style="float: left; margin-right: 10px;"> -->
	</form>
	
	<?php } else if($tab2 == 'reports') {  ?>
<?php
$facebook_count = (get_option('moatall_wsl_facebook_count') != '') ? get_option('moatall_wsl_facebook_count') : 0;
$google_count = (get_option('moatall_wsl_google_count') != '') ? get_option('moatall_wsl_google_count') : 0;
$yahoojapan_count = (get_option('moatall_wsl_yahoojapan_count') != '') ? get_option('moatall_wsl_yahoojapan_count') : 0;
$twitter_count = (get_option('moatall_wsl_twitter_count') != '') ? get_option('moatall_wsl_twitter_count') : 0;
?>
	<html>
		<head>
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day']
						,['Facebook', <?php echo $facebook_count ?>]
						,['Google', <?php echo $google_count ?>]
						,['Yahoo! JAPAN', <?php echo $yahoojapan_count ?>]
						,['Twitter', <?php echo $twitter_count ?>]
					]);
					var options = {
						title: 'Social Login Activities',
						pieHole: 0.4,
					};
					var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
					chart.draw(data, options);
				}
			</script>
		</head>
		<body>
			<div id="donutchart" style="width: 1150px; height: 500px;"></div>
			<div class="socialchart" style="width: 1150px; height: auto;">
				<table class="socialchart-data" width=100%>
					<tr>
						<th><?php _e('Networks','moatall_social_login'); ?></th>
						<th><?php _e('Number of Connections','moatall_social_login'); ?></th>
					</tr>
					<tr>
						<td><?php _e('Facebook','moatall_social_login'); ?></td>
						<td><?php echo $facebook_count ?></td>
					</tr>
					<tr>
						<td><?php _e('Google','moatall_social_login'); ?></td>
						<td><?php echo $google_count ?></td>
					</tr>
 					<tr>
						<td><?php _e('Yahoo! JAPAN','moatall_social_login'); ?></td>
						<td><?php echo $yahoojapan_count ?></td>
					</tr>
 					<tr>
						<td><?php _e('Twitter','moatall_social_login'); ?></td>
						<td><?php echo $twitter_count ?></td>
					</tr>
				</table>
			</div>
		</body>
	</html>
	<?php } ?>
</div>

<style>
.socialchart {
	background: #fff none repeat scroll 0 0;
	margin-top: 20px;
	padding: 14px;
}
.socialchart-data{border:1px solid #ccc;}
.socialchart-data th {
	padding-bottom: 10px;
	text-align: left;
}
.form-table th{ padding: 20px 10px 20px 20px;}
.form-table {background: #fff none repeat scroll 0 0;}
.form-table td {  padding: 15px 100px;}
.button-primary{margin-top: 15px !important;}
.button-info{margin-top: 15px !important;}
</style>

<?php
	if(!empty($_POST) && isset($_POST['save_data'])) {
		if ( check_admin_referer( 'all_social_setting_action', 'social_setting_form_nonce_field' ) ) {
			$social_setting=array(
				'facebook_details'=>array(
					'enable_facebook'=> (isset($_POST['wsl_facebook_enable']) && $_POST['wsl_facebook_enable'] != '')? filter_var($_POST['wsl_facebook_enable'], FILTER_SANITIZE_STRING):'', 
					'facebook_id'=>sanitize_text_field($_POST['wsl_facebook_id']),
					'facebook_secret'=>sanitize_text_field($_POST['wsl_facebook_secret']),
					'fb_icon_url'=>esc_url($_POST['ad_image'])
				),
				'google_plus_details'=>array(
					'enable_google_plus'=> (isset($_POST['wsl_google_enable']) && $_POST['wsl_google_enable'] != '')? filter_var($_POST['wsl_google_enable'], FILTER_SANITIZE_STRING):'', 
					'google_id'=>sanitize_text_field($_POST['wsl_google_id']),
					'google_icon_url'=>esc_url($_POST['ad_image1'])
				),
				'yahoojapan_details'=>array(
					'enable_yahoojapan'=> (isset($_POST['wsl_yahoojapan_enable']) && $_POST['wsl_yahoojapan_enable'] != '')? filter_var($_POST['wsl_yahoojapan_enable'], FILTER_SANITIZE_STRING):'', 
					'yahoojapan_id'=>sanitize_text_field($_POST['wsl_yahoojapan_id']),
					'yahoojapan_secret'=>sanitize_text_field($_POST['wsl_yahoojapan_secret']),
					'yahoojapan_icon_url'=>esc_url($_POST['ad_image2'])
				),
				'twitter_details'=>array(
					'enable_twitter'=> (isset($_POST['wsl_twitter_enable']) && $_POST['wsl_twitter_enable'] != '')? filter_var($_POST['wsl_twitter_enable'], FILTER_SANITIZE_STRING):'', 
					'twitter_id'=>sanitize_text_field($_POST['wsl_twitter_id']),
					'twitter_secret'=>sanitize_text_field($_POST['wsl_twitter_secret']),
					'twitter_icon_url'=>esc_url($_POST['ad_image3'])
				),
				'change_text_details'=>array(
					'sign_in'=>sanitize_text_field($_POST['wsl_change_sign_in']),
					'sign_up'=>sanitize_text_field($_POST['wsl_change_sign_up'])
				),
				'change_text_details'=>array(
					'login_label'=>sanitize_text_field($_POST['wsl_social_label']),
					'checkout_label'=>sanitize_text_field($_POST['wsl_social_label_checkout'])
				)
			);
			update_option('moatall_wsl_plugin_setting_data', $social_setting);
			wp_redirect("admin.php?page=woo-social-login");
		}
	}
}
?>
