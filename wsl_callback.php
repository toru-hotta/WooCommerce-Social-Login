<?php
if ( "facebook" == $_GET["social_type"] ) {
	wsl_facebook_callback();
} elseif ( "google" == $_GET["social_type"] ) {
	wsl_google_callback();
} elseif ( "twitter" == $_GET["social_type"] ) {
	wsl_twitter_callback();
} else {
	wsl_yahoojapan_callback();
}
?>