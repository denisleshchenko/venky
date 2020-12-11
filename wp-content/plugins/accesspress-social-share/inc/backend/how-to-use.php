<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<p><?php _e( 'Plugin configuration video', 'accesspress-social-share' ); ?> </p>
<iframe width="560" height="315" src="https://www.youtube.com/embed/tAfiyOnoEZs" frameborder="0" allowfullscreen></iframe>
<br />
<?php _e( 'You can get the details instruction for creating facebook app', 'accesspress-social-share' ); ?> <a href='http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-share/?p=89' target="_blank">here</a>.
<br />
<p><?php _e( 'Basically there are four main settings tabs that will help you to setup the plugin to work properly.', 'accesspress-social-share' ); ?></p>
<dl>
    <dt><strong><?php _e( 'Social Networks', 'accesspress-social-share' ); ?></strong></dt>
    <dd><p><?php _e( 'In this tab you can choose which social media icons you want to display in the frontend. Here you can order the apperance of the social icons simply by drag and drop of each social icons.', 'accesspress-social-share' ); ?></p></dd>

    <dt><strong><?php _e( 'Share options', 'accesspress-social-share' ); ?></strong></dt>
    <dd><p><?php _e( 'In this tab you can select the options where you want to display social media share icons.', 'accesspress-social-share' ); ?></p></dd>

    <dt><strong><?php _e( 'Display settings', 'accesspress-social-share' ); ?></strong></dt>
    <dd><p><?php _e( 'In this tab you can customize the apperance of the social media share icons. You can choose the options to display the share icons below the contennt, above the content or you can choose an options to display in both below and above content. Also you can choose the theme from the pre available 5 themes.', 'accesspress-social-share' ); ?></p></dd>

    <dt><strong><?php _e( 'Miscellaneous settings', 'accesspress-social-share' ); ?></strong></dt>
    <dd><p><?php _e( 'In this tab you can do the additional settings for the plugin.', 'accesspress-social-share' ); ?>
        <ul class="how-list">
            <li><i class="fa fa-check"></i><?php _e( 'You can setup the twitter username.', 'accesspress-social-share' ); ?></li>
            <li><i class="fa fa-check"></i><?php _e( 'You can enable/disable the social counter.', 'accesspress-social-share' ); ?></li>
            <li><i class="fa fa-check"></i><?php _e( 'Share link - You can enable the share options either in new tab/window or in same widow.', 'accesspress-social-share' ); ?></li>
            <li><i class="fa fa-check"></i><?php _e( 'Cache settings - Here you can set the cache settings of the social share counter in hour format.','accesspress-social-share'); ?></li>
            <li><i class="fa fa-check"></i><?php _e( 'Email settings - If you have enabled the email settings you can setup the email header and body part.','accesspress-social-share'); ?></li>
        </ul>
        </p></dd>
    <dt><strong>Shortcode</strong></dt>
    <dd><p><?php _e( 'You can use shortcode for the display of the social share in the contents. Optionally You can enter the name of the networks you want to display. The networks will be displayed in the order of entered networks.', 'accesspress-social-share' ); ?>
        <ul class="how-list">
            <li><i class="fa fa-check"></i>Example 1: <code>[apss_share]</code></li>
            <li><?php _e( 'Available shortcode parameters', 'accesspress-social-share' ); ?></li>
            <ul>
                <li><i class="fa fa-check"></i><?php _e( "networks : You can define which social medias to show in the shortcode. You need to enter the networks name in string in comma separated values. If you don't want to choose which social medias to appear in shortcode, you can discard this option.", "accesspress-social-share" ); ?> </li>
                <li><?php _e( 'Available network parameters are: facebook, twitter, pinterest, linkedin, digg, email, print', 'accesspress-social-share' ); ?></li>
                <li><i class="fa fa-check"></i><?php _e( "share_text: You can add the share text. To use share text use share_text='text to be shared'. If you don't use this parameter the share text will not appear in shortcode.", "accesspress-social-share" ); ?></li>
                <li><i class="fa fa-check"></i><?php _e( "counter : You can enable or disable the share counter. To enable the share count use counter='1' and to disable it simply don't use counter parameter or use parameter counter = '0'.", "accesspress-social-share" ); ?></li>
                <li><i class="fa fa-check"></i><?php _e( "total_counter : You can enable or disable the total share counter. To enable the total share count use total_counter='1' and to disable it simply don't use total_counter parameter or use parameter total_counter = '0'.", "accesspress-social-share" ); ?></li>
                <li><i class = "fa fa-check"></i><?php _e( "custom_share_link : You can enter the custom share link in case if the link provided by shortcode is not as per your need. To enable the custom share link use custom_share_link = 'custom link as per your need.'", "accesspress-social-share" ); ?></li>
                <li><i class = 'fa fa-check'></i><?php _e( "http_count: You can set this option if you have moved your site from HTTP to HTTPS to show the share counts from your old http site as well. Please note that the share count will fetch if your site has been moved from HTTP to HTTPS eg if your old site was http://example.com and you have moved your site to https://example.com. But please not that if you have changed other url parameters then the count will not work for example if your old page slug was http://example.com/sample-page and you have changed it to https://example.com/sample-page-1 then the share counts will not work for old url.", "accesspress-social-share" ); ?></li>
            </ul>
            <li><i class = "fa fa-check"></i>Example 1.1: <code>[apss_share networks = 'facebook, twitter, pinterest' share_text = 'Share it' counter = '1' total_counter = '1' http_count = '1' ]</code></li>
        </ul>
        </p></dd>
    <dd>
        <p><?php _e( "You can use the below shortcode to display the share counts number only in the content using shortcode. You can wrap that number in your reqired html tags and use it as per your need. The share count displayed will be the sum of entered network attributes.", "accesspress-social-share" ); ?>
        <ul class = "how-list">
            <li><li><i class = "fa fa-check"></i>Example 2: <code>[apss_count]</code></li></li>
            <li><?php _e( 'Available Parameters', 'accesspress-social-share' ); ?>
                <ul>
                    <li><i class = "fa fa-check"></i><?php _e( "network : You can define which social medias to show total share counts. You need to enter the networks name in string in comma separated values. You need to enter at least one network attribute.", "accesspress-social-share" ); ?></li>
                    <li><?php _e( "Available network parameters are: facebook, twitter, pinterest, linkedin", "accesspress-social-share" ); ?></li>
                    <li><?php _e( "Example 2.1:", "accesspress-social-share" ); ?> <code>[apss_count network = 'facebook, pinterest' ]</code></li>
                    <li><?php _e( "This will show the sum of share counts from facebook and pinterest.", "accesspress-social-shares" ); ?></li>
                </ul>
                <ul>
                    <li><i class = "fa fa-check"></i><?php _e( 'custom_url_link', 'accesspress-social-share' ); ?></li>
                    <li><?php _e( 'Now you can use attribute "custom_url_link" to fetch the share counts for custom url as well.', 'accesspress-social-share' ); ?></li>
                    <li><?php _e( "Example 2.2:", "accesspress-social-share" ); ?> <code>[apss_count network = 'facebook, pinterest' custom_url_link = '<?php echo esc_url(site_url( 'sample-page' )); ?>' ]</code></li>
                    <li><?php _e( "This attribute is useful in case if the shortcode is not fetching the share counts for shortcode placed url and force the shortcode to use the url from the attribute defined in custom_url_link.", "accesspress-social-share" ); ?></li>
                </ul>
            </li>
        </ul>
        </p>
    </dd>
    <dd>
        <p><b><?php _e( "For now you can use shortcode", "accesspress-social-share" ); ?> [apss-share] and [apss-count]<?php _e( "for the display of the social shares and counts only as well but in upcoming plugin updates this shortcode will be removed and use the new one. So we suggest to use only the new shortcodes.", "accesspress-social-share" ); ?></b></p>
    </dd>
    <dd>
        <p><?php _e( 'For the complete documentation please visit:', 'accesspress-social-share' ); ?><br /> <a href = 'https://accesspressthemes.com/documentation/documentation-plugin-instruction-accesspress-social-share/' target = "_blank">https://accesspressthemes.com/documentation/documentation-plugin-instruction-accesspress-social-share/</a> </p>
    </dd>

</dl>