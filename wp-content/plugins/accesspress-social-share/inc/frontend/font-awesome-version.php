<?php
$apss_share_settings = get_option('apss_share_settings');
if( isset( $options[ 'disable_frontend_assets' ] ) && $options[ 'disable_frontend_assets' ] == '0' ){
	if ( isset( $apss_share_settings['font_awesome'] ) && $apss_share_settings['font_awesome'] == 'apss_font_awesome_four' ) {
		$version=array(
			$facebook='fa fa-facebook',
			$twitter='fa fa-twitter',
			$pinterest='fa fa-pinterest',
			$linkedin='fa fa-linkedin',
			$digg='fa fa-digg',
			$mail='fa fa-envelope',
			$print='fa fa-print'
		);
	}
	else{
		$version=array(
			$facebook='fab fa-facebook-f',
			$twitter='fab fa-twitter',
			$pinterest='fab fa-pinterest',
			$linkedin='fab fa-linkedin',
			$digg='fab fa-digg',
			$mail='fas fa-envelope',
			$print='fas fa-print'
		);
	}
}	
else{
	$version=array(
		$facebook='fab fa-facebook-f',
		$twitter='fab fa-twitter',
		$pinterest='fab fa-pinterest',
		$linkedin='fab fa-linkedin',
		$digg='fab fa-digg',
		$mail='fas fa-envelope',
		$print='fas fa-print'
	);
}?>