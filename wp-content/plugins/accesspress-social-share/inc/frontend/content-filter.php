<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );
include( APSS_PATH.'inc/frontend/font-awesome-version.php' );
$apss_link_open_option = ($options['dialog_box_options'] == '1') ? "_blank" : "";
$apss_link_open_option_value = intval($options['dialog_box_options']);
$twitter_user = $options['twitter_username'];
$counter_enable_options = $options['counter_enable_options'];
if(isset($options['twitter_counter_api'])){
	$twitter_api_use = ($options['twitter_counter_api'] != '1') ? $options['twitter_counter_api'] : '1'; 
}else{
	$twitter_api_use = '1';
}
$icon_set_value = $options['social_icon_set'];
$url = get_permalink(); 
$cache_period = ($options['cache_period'] != '') ? $options['cache_period'] * 60 * 60 : 24 * 60 * 60;

if ( isset( $options['total_counter_enable_options'] ) ) {
	if ( $options['total_counter_enable_options'] == '1' ) {
		$enable_counter = 1;
	}
} else {
	$enable_counter = 0;
}

if(isset($options['enable_http_count'])){
	if ( $options['enable_http_count'] == '1' ) {
		$http_url_checked = 1;
	}else{
		$http_url_checked = 0;
	}
}else {
	$http_url_checked = 0;
}
?>

<?php if ( isset( $options['share_text'] ) && $options['share_text'] != '' ) { ?> <div class='apss-share-text'><?php echo esc_attr($options['share_text']); ?></div> <?php } ?>
<?php
$total_count = 0;

foreach ( $options['social_networks'] as $key => $value ) {
	if ( intval( $value ) == '1' ) {
		$count = $this->get_count( $key, $url );
		
		if(isset($http_url_checked) && $http_url_checked=='1'){
			$url_check = parse_url($url);
			if($url_check['scheme'] == 'https'){
				$flag = TRUE;
			}else{
				$flag = FALSE;
			}

			if($flag == TRUE){
			    $url1 = APSS_Class:: get_http_url($url);
			    $http_count = APSS_Class:: get_count($key, $url1);
			    if($count != $http_count){
			    	$count += $http_count;
			    }else{
			    	$count = $count;
			    }
			}
		}
		
		$total_count += $count;
		switch ( $key ) {
			
			case 'facebook':
				$link = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
				$count = $this->get_count( $key, $url );
				
				if(isset($http_url_checked) && $http_url_checked=='1'){
					$url_check = parse_url($url);
					if($url_check['scheme'] == 'https'){
						$flag = TRUE;
					}else{
						$flag = FALSE;
					}
					if($flag == TRUE){
					    $url1 = APSS_Class:: get_http_url($url);
					    $http_count = APSS_Class:: get_count($key, $url1);
					    if($count != $http_count){
					    	$count += $http_count;
					    }else{
					    	$count = $count;
					    }
					}
				}
				
				?>
				<div class='apss-facebook apss-single-icon'>
					<a rel='nofollow' <?php if($apss_link_open_option_value == 2){ ?> onclick="apss_open_in_popup_window(event, '<?php echo $link; ?>');" <?php } ?> title="<?php _e( 'Share on Facebook', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo esc_url($link); ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='<?php echo esc_attr($facebook);?>'></i>
							<span class='apss-social-text'><?php _e( 'Share on Facebook', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo esc_url($url); ?>' data-social-network='<?php echo esc_attr($key); ?>' data-social-detail="<?php echo esc_url($url) . '_' . $key; ?>"><?php echo esc_attr($count); ?></div>
						<?php } ?>
					</a>
				</div>
				<?php
				break;
			
			case 'twitter':
				$url_twitter = $url;
//				$url_twitter = urlencode( $url_twitter );
				if ( isset( $twitter_user ) && $twitter_user != '' ) {
					$twitter_user = 'via=' . $twitter_user;
				}
				$link = "https://twitter.com/intent/tweet?text=$title&amp;url=$url_twitter&amp;$twitter_user";
				$count = $this->get_count( $key, $url );
				if(isset($http_url_checked) && $http_url_checked=='1'){
					$url_check = parse_url($url);
					if($url_check['scheme'] == 'https'){
						$flag = TRUE;
					}else{
						$flag = FALSE;
					}

					if($flag == TRUE){
					    $url1 = APSS_Class:: get_http_url($url);
					    $http_count = APSS_Class:: get_count($key, $url1);
					    if($count != $http_count){
					    	$count += $http_count;
					    }else{
					    	$count = $count;
					    }
					}
				}
				?>
				<div class='apss-twitter apss-single-icon'>
					<a rel='nofollow' <?php if($apss_link_open_option_value == 2){ ?> onclick="apss_open_in_popup_window(event, '<?php echo $link; ?>');" href='javascript:void(0);' <?php }else{ ?> href="<?php echo esc_url($link); ?>" <?php } ?> title="<?php _e( 'Share on Twitter', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='<?php echo esc_attr($twitter);?>'></i>
							<span class='apss-social-text'><?php _e( 'Share on Twitter', 'accesspress-social-share' ); ?></span><span class='apss-share'><?php _e( 'Tweet', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' && $twitter_api_use !='1' ) { ?>
							<div class='count apss-count' data-url='<?php echo esc_url($url); ?>' data-social-network='<?php echo esc_attr($key); ?>' data-social-detail="<?php echo esc_url($url) . '_' . $key; ?>"><?php echo esc_attr($count); ?></div>
						<?php } ?>
					</a>
				</div>
				<?php
				break;

			case 'pinterest':
				$count = $this->get_count( $key, $url );
				
				if(isset($http_url_checked) && $http_url_checked == '1'){
					$url_check = parse_url($url);
					if($url_check['scheme'] == 'https'){
						$flag = TRUE;
					}else{
						$flag = FALSE;
					}

					if($flag == TRUE){
					    $url1 = APSS_Class:: get_http_url($url);
					    $http_count = APSS_Class:: get_count($key, $url1);
					    if($count != $http_count){
					    	$count += $http_count;
					    }else{
					    	$count = $count;
					    }
					}
				}
				
				?>

				<div class='apss-pinterest apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Pinterest', 'accesspress-social-share' ); ?>" href='javascript:pinIt();'>
						<div class='apss-icon-block clearfix'>
							<i class='<?php echo esc_attr($pinterest);?>'></i>
							<span class='apss-social-text'><?php _e( 'Share on Pinterest', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo esc_url($url); ?>' data-social-network='<?php echo esc_attr($key); ?>' data-social-detail="<?php echo esc_url($url) . '_' . $key; ?>"><?php echo esc_attr($count); ?></div>
						<?php } ?>

					</a>
				</div>
				<?php
				break;

			case 'linkedin':
                                $link= "http://www.linkedin.com/sharing/share-offsite/?url=" . $url;
				//$link = "http://www.linkedin.com/shareArticle?mini=true&amp;title=" . $title . "&amp;url=" . $url . "&amp;summary=" . $excerpt;
				$count = $this->get_count( $key, $url );
				
				if(isset($http_url_checked) && $http_url_checked=='1'){
					$url_check = parse_url($url);
					if($url_check['scheme'] == 'https'){
						$flag=TRUE;
					}else{
						$flag=FALSE;
					}

					if($flag == TRUE){
					    $url1 = APSS_Class:: get_http_url($url);
					    $http_count = APSS_Class:: get_count($key, $url1);
					    if($count != $http_count){
					    	$count += $http_count;
					    }else{
					    	$count = $count;
					    }
					}
				}
				?>
				<div class='apss-linkedin apss-single-icon'>
					<a rel='nofollow' <?php if($apss_link_open_option_value == 2){ ?> onclick="apss_open_in_popup_window(event, '<?php echo $link; ?>');" <?php } ?> title="<?php _e( 'Share on LinkedIn', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo esc_url($link); ?>'>
						<div class='apss-icon-block clearfix'><i class='<?php echo esc_attr($linkedin);?>'></i>
							<span class='apss-social-text'><?php _e( 'Share on LinkedIn', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>
				<?php
				break;

			//there is no counter available for digg
			case 'digg':
				$link = "http://digg.com/submit?phase=2%20&amp;url=" . $url . "&amp;title=" . $title;
				?>
				<div class='apss-digg apss-single-icon'>
					<a rel='nofollow' <?php if($apss_link_open_option_value == 2){ ?> onclick="apss_open_in_popup_window(event, '<?php echo $link; ?>');" <?php } ?> title="<?php _e( 'Share on Digg', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo esc_url($link); ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='<?php echo esc_attr($digg);?>'></i>
							<span class='apss-social-text'><?php _e( 'Share on Digg', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>

				<?php
				break;

			case 'email':
				if ( strpos( $options['apss_email_body'], '%%' ) || strpos( $options['apss_email_subject'], '%%' ) ) {
					$link = 'mailto:?subject=' . $options['apss_email_subject'] . '&amp;body=' . $options['apss_email_body'];
					$link = preg_replace( array( '#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%url%%#' ), array( $title, get_site_url(), get_permalink(), $url ), $link );
				} else {
					$link = 'mailto:?subject=' . $options['apss_email_subject'] . '&amp;body=' . $options['apss_email_body'] . ": " . $url;
				}
				?>
				<div class='apss-email apss-single-icon'>
					<a rel='nofollow' class='share-email-popup' title="<?php _e( 'Share it on Email', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo esc_url($link); ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='<?php echo esc_attr($mail);?>'></i>
							<span class='apss-social-text'><?php _e( 'Send email', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Mail', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>

				<?php
				break;

			case 'print':
				?>
				<div class='apss-print apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Print', 'accesspress-social-share' ); ?>" href='javascript:void(0);' onclick='window.print(); return false;'>
						<div class='apss-icon-block clearfix'><i class='<?php echo esc_attr($print);?>'></i>
							<span class='apss-social-text'><?php _e( 'Print', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Print', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>
				<?php
				break;
		}
	}
}

do_action('apss_more_networks');

if ( isset( $enable_counter ) && $enable_counter == '1' ) {
	?>
	<div class='apss-total-share-count'>
		<span class='apss-count-number'><?php echo esc_attr($total_count); ?></span>
		<div class="apss-total-shares"><span class='apss-total-text'><?php echo _e( ' Total', 'accesspress-social-share' ); ?></span>
			<span class='apss-shares-text'><?php echo _e( ' Shares', 'accesspress-social-share' ); ?></span></div>
	</div>
<?php
}