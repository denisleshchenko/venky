<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); 
?>
<div class="apss-wrapper-block">
    <div class="apss-setting-header clearfix">
        <div class="apss-headerlogo">
            <img src="<?php echo esc_attr(APSS_IMAGE_DIR); ?>/logo-old.png" alt="<?php esc_attr_e( 'AccessPress Social Share', 'accesspress-social-share' ); ?>" />
        </div>
        <div class="apss-header-icons">
            <p><?php _e( 'Follow us for new updates' ); ?></p>
            <div class="apss-social-bttns">
                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowtransparency="true"></iframe>
                &nbsp;&nbsp;
                <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="//platform.twitter.com/widgets/follow_button.5f46501ecfda1c3e1c05dd3e24875611.en.html#_=1421918256492&amp;dnt=true&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" title="Twitter Follow Button" data-twttr-rendered="true" style="width: 126px; height: 20px;"></iframe>
                <script>!function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", "twitter-wjs");</script>
            </div>
        </div>
        <div class="apss-header-title">
            <?php _e( 'AccessPress Social Share', 'accesspress-social-share' ); ?>
        </div>
    </div>
    <?php
    $options = get_option( APSS_SETTING_NAME );
    if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '1' ) {
        ?>
        <div class="apss-message notice notice-success is-dismissible">
            <p><strong><?php _e( 'Settings Saved Successfully.', 'accesspress-social-share' ); ?></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'accesspress-social-share' ); ?></span>
            </button>
        </div>
    <?php } else if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '3' ) {
        ?>
        <div class="apss-message notice notice-success is-dismissible">
            <p><strong><?php _e( 'Settings restored successfully.', 'accesspress-social-share' ); ?></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'accesspress-social-share' ); ?></span>
            </button>
        </div>
    <?php } else if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '4' ) {
        ?>
        <div class="apss-message notice notice-success is-dismissible">
            <p><strong><?php _e( 'Cache cleared Successfully', 'accesspress-social-share' ); ?></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'accesspress-social-share' ); ?></span>
            </button>
        </div>
        <?php
    } else if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] != '3' && $_GET[ 'message' ] != '1' ) {
        ?>
        <div class="apss-message notice notice-error is-dismissible">
            <p><?php _e( 'There has been an error.', 'accesspress-social-share' ); ?></p>
        </div>
    <?php }
    ?>
<div class= "apps-settings-container">
    <div class="apps-wrap">
        <form method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
            <input type="hidden" name="action" value="apss_save_options"/>
            <ul class="apss-setting-tabs clearfix">
                <li><a href="javascript:void(0)" id="apss-social-networks" class="apss-tabs-trigger apss-active-tab	"><?php _e( 'Social Networks', 'accesspress-social-share' ); ?></a></li>
                <li><a href="javascript:void(0)" id="apss-share-options" class="apss-tabs-trigger "><?php _e( 'Share Options', 'accesspress-social-share' ) ?></a></li>
                <li><a href="javascript:void(0)" id="apss-display-settings" class="apss-tabs-trigger"><?php _e( 'Display Settings', 'accesspress-social-share' ); ?></a></li>
                <li><a href="javascript:void(0)" id="apss-miscellaneous" class="apss-tabs-trigger"><?php _e( 'Miscellaneous', 'accesspress-social-share' ); ?></a></li>
                <li><a href="javascript:void(0)" id="apss-how-to-use" class="apss-tabs-trigger"><?php _e( 'How To Use', 'accesspress-social-share' ); ?></a></li>
                <li><a href="javascript:void(0)" id="apss-about" class="apss-tabs-trigger"><?php _e( 'About', 'accesspress-social-share' ); ?></a></li>
            </ul>
            <div class='apss-tab-contents-wrapper'>
                <div class="apss-wrapper">
                    <div class="apss-tab-contents apss-social-networks" id="tab-apss-social-networks" style='display:block'>
                        <h2><?php _e( 'Social Media chooser:', 'accesspress-social-share' ); ?> </h2>
                        <span class="social-text"><?php _e( 'Please choose the social media you want to display. Also you can order these social media\'s by drag and drop:', 'accesspress-social-share' ); ?></span>
                        <div class="apps-opt-wrap clearfix">
                            <?php
                                $label_array = array(
                                    'facebook' => ' <span class="media-icon"><i class="fa fa-facebook"></i></span> Facebook',
                                    'twitter' => ' <span class="media-icon"><i class="fa fa-twitter"></i></span> Twitter',
                                    'pinterest' => '<span class="media-icon"> <i class="fa fa-pinterest"></i> </span>Pinterest',
                                    'linkedin' => '<span class="media-icon"><i class="fa fa-linkedin"></i></span> Linkedin',
                                    'digg' => '<span class="media-icon"><i class="fa fa-digg"></i></span> Digg',
                                    'email' => '<span class="media-icon"><i class="fa  fa-envelope"></i></span> Email',
                                    'print' => '<span class="media-icon"><i class="fa fa-print"></i> </span>Print',
                                );
                            ?>
                            <?php
                            foreach ( $options[ 'social_networks' ] as $key => $val ) {
                               if($key != 'google-plus'){ ?>

                                <div class="apss-option-wrapper">
                                    <div class="apss-option-field">
                                        <label class="clearfix"><span class="left-icon"><i class="fa fa-arrows"></i></span><span class="social-name"><?php echo $label_array[ $key ]; ?></span><input type="checkbox" data-key='<?php echo $key; ?>' name="social_networks[<?php echo $key; ?>]" value="1" <?php
                                        if ( $val == '1' ) {
                                            echo "checked='checked'";
                                        }
                                        ?> /></label>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                        </div>
                        <input type="hidden" name="apss_social_newtwork_order" id='apss_social_newtwork_order' value="<?php echo implode( ',', array_keys( $options[ 'social_networks' ] ) ); ?>"/>
                        <?php  include( APSS_PATH . '/inc/backend/save-button.php') ?>
                    </div>

                    <div class="apss-tab-contents apss-share-options" id="tab-apss-share-options" style='display:none'>
                        <h2><?php _e( 'Share options:', 'accesspress-social-share' ); ?> </h2>
                        <span class="social-text"><?php _e( 'Please choose the options where you want to display social share icons:', 'accesspress-social-share' ); ?></span>
                        <p><input type="checkbox" id="apss_posts" value="post" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "post", $options[ 'share_options' ] ) || in_array( "posts", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_posts"><?php _e( 'Posts', 'accesspress-social-share' ); ?> </label></p>
                        <p><input type="checkbox" id="apss_pages" value="page" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "page", $options[ 'share_options' ] ) || in_array( "pages", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_pages"><?php _e( 'Pages', 'accesspress-social-share' ); ?> </label></p>

                        <p><input type="checkbox" id="apss_front_page" value="front_page" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "front_page", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_front_page"><?php _e( 'Front Page', 'accesspress-social-share' ); ?></label></p>
                        <p><input type="checkbox" id="apss_archives" value="archives" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "archives", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_archives"><?php _e( 'Archives', 'accesspress-social-share' ); ?></label></p>

                        <p><input type="checkbox" id="apss_attachement" value="attachment" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "attachment", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_attachment"><?php _e( 'Attachment pages', 'accesspress-social-share' ); ?></label></p>

                        <p><input type="checkbox" id="apss_categories" value="categories" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "categories", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_categories"><?php _e( 'Categories', 'accesspress-social-share' ); ?></label></p>
                        <p><input type="checkbox" id="apss_all" value="all" name="apss_share_settings[share_options][]" <?php
                        if ( in_array( "all", $options[ 'share_options' ] ) ) {
                            echo "checked='checked'";
                        }
                        ?> ><label for="apss_all"><?php _e( 'Other (search results, etc)', 'accesspress-social-share' ); ?></label></p>

                        <?php  include( APSS_PATH . '/inc/backend/save-button.php') ?>
                    </div>

                    <div class="apss-tab-contents apss-display-settings" id="tab-apss-display-settings" style='display:none'>
                        <div class=' apss-display-positions'>
                            <h2><?php _e( 'Display position:', 'accesspress-social-share' ); ?></h2>
                            <span class='social-text'><?php _e( 'Please choose the option where you want to display the social share:', 'accesspress-social-share' ); ?></span>
                            <p>
                                <input type="radio" id="apss_below_content" name="apss_share_settings[social_share_position_options]" value="below_content" <?php
                                if ( $options[ 'share_positions' ] == 'below_content' ) {
                                    echo "checked='checked'";
                                }
                                ?> /><label for='apss_below_content'><?php _e( 'Below content', 'accesspress-social-share' ); ?></label>
                            </p>
                            <p>
                                <input type="radio" id="apss_above_content" name="apss_share_settings[social_share_position_options]"/ value="above_content" <?php
                                if ( $options[ 'share_positions' ] == 'above_content' ) {
                                    echo "checked='checked'";
                                }
                                ?> /><label for='apss_above_content'><?php _e( 'Above content', 'accesspress-social-share' ); ?></label>
                            </p>
                            <p>
                                <input type="radio" id="apss_below_above_content" id="below_above_content" name="apss_share_settings[social_share_position_options]" value="on_both" <?php
                                if ( $options[ 'share_positions' ] == 'on_both' ) {
                                    echo "checked='checked'";
                                }
                                ?> /><label for='apss_below_above_content'><?php _e( 'Both(Below content and Above content)', 'accesspress-social-share' ); ?></label>
                            </p>
                        </div>
                        <div class="apss-font-awesome-select">
                            <h2><?php _e( 'Font Awesome Icon:', 'accesspress-social-share' ); ?></h2>
                            <span class='social-text'><?php _e( 'Please choose the font awesome version you would like to display:', 'accesspress-social-share' ); ?></span>
                            <p>
                                <input type="radio" id="apss_font_awesome_four" name="apss_share_settings[font_awesome_options]" value="apss_font_awesome_four" <?php
                                if(isset( $options[ 'font_awesome' ]) && $options['font_awesome'] == 'apss_font_awesome_four' ) {
                                    echo "checked='checked'";
                                }
                                ?> />
                                <label for='apss_font_awesome_four'><?php _e( 'Font Awesome V.4', 'accesspress-social-share' ); ?></label>
                            </p>
                            <p>
                                <input type="radio" id="apss_font_awesome_five" name="apss_share_settings[font_awesome_options]" value="apss_font_awesome_five" <?php
                                if(isset( $options[ 'font_awesome' ]) && $options['font_awesome'] == 'apss_font_awesome_five' ) {
                                    echo "checked='checked'";
                                }
                                ?> />
                                <label for='apss_font_awesome_five'><?php _e( 'Font Awesome V.5', 'accesspress-social-share' ); ?></label>
                            </p>
                        </div>
                        <div class=" apss-icon-sets">
                            <h2><?php _e( 'Social icons sets: ', 'accesspress-social-share' ); ?> </h2>
                            <?php _e( 'Please choose any one out of available icon themes:', 'accesspress-social-share' ); ?>
                            <?php for ( $i = 1; $i <= 6; $i ++ ) { ?>
                                <p><input id="apss_icon_set_<?php echo $i; ?>" value="<?php echo $i; ?>" name="apss_share_settings[social_icon_set]" type="radio" <?php
                                if ( $options[ 'social_icon_set' ] == $i ) {
                                    echo "checked='checked'";
                                }
                                ?> ><label for="apss_icon_set_<?php echo $i; ?>"><span class="apss_demo_icon apss_demo_icons_<?php echo $i; ?>"></span><?php _e( "Theme $i", 'accesspress-social-share' ); ?><div class="apss-theme-image"><img src='<?php echo esc_attr(APSS_IMAGE_DIR) . "/theme/theme$i.jpg"; ?>'/></div></label></p>
                            <?php } ?>
                        </div>
                        <?php  include( APSS_PATH . '/inc/backend/save-button.php') ?>
                    </div>

                    <div class="apss-tab-contents apss-miscellaneous" id="tab-apss-miscellaneous" style='display:none'>
                        <h2><?php _e( 'Miscellaneous settings: ', 'accesspress-social-share' ); ?> </h2>
                        <h4><?php _e( 'Please setup these additional settings:', 'accesspress-social-share' ); ?></h4>

                        <div class="apss-share-text-settings clearfix">
                            <h4><?php _e( 'Disable the plugins frontend assets?', 'accesspress-social-share' ); ?> </h4>
                            <div class="misc-opt"><input type="radio" id='disable_frontend_assets_n' name="apss_share_settings[disable_frontend_assets]" value="0" <?php
                            if ( isset( $options[ 'disable_frontend_assets' ] ) && $options[ 'disable_frontend_assets' ] == '0' ) {
                                echo "checked='checked'";
                            }
                            ?> /><label for="disable_frontend_assets_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
                            <div class="misc-opt"><input type="radio" id='disable_frontend_assets_y' name="apss_share_settings[disable_frontend_assets]" value="1" <?php
                            if ( isset( $options[ 'disable_frontend_assets' ] ) && $options[ 'disable_frontend_assets' ] == '1' ) {
                                echo "checked='checked'";
                            }
                            ?> /><label for="disable_frontend_assets_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
                            <br />
                            <div class="apss_notes_cache_settings">
                                <?php _e( 'Please set this value if you don\'t want to use plguins frontend assets(js and css files).', 'accesspress-social-share' ); ?>
                            </div>
                        </div>
                        <br />
                        <div class="apss_input_wrapper clearfix">
                            <label><?php _e( 'Share text:', 'accesspress-social-share' ); ?></label>
                            <input type="text" name="apss_share_settings[share_text]"  value="<?php
                            if ( isset( $options[ 'share_text' ] ) ) {
                                echo esc_attr($options[ 'share_text' ]);
                            }
                            ?>" />
                            <div class="apss_notes_cache_settings">
                                <?php _e( 'Please enter the share text to make it appear above social share icons. Leave blank if you don\'t want to use share text.', 'accesspress-social-share' ); ?>
                            </div>
                        </div>
                        <div class="apss_input_wrapper apss-twitter-settings clearfix">
                            <label><?php _e( 'Twitter username:', 'accesspress-social-share' ); ?></label>
                            <input type="text" name="apss_share_settings[twitter_username]"  value="<?php echo esc_attr($options[ 'twitter_username' ]); ?>" />
                        </div>

                        <div class="apss-counter-settings clearfix">
                            <h4><?php _e( 'Social share counter enable?', 'accesspress-social-share' ); ?> </h4>
                            <div class="misc-opt"><input type="radio" id='counter_enable_options_n' name="apss_share_settings[counter_enable_options]" value="0" <?php
                            if ( $options[ 'counter_enable_options' ] == '0' ) {
                                echo "checked='checked'";
                            }
                            ?> /><label for="counter_enable_options_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
                            <div class="misc-opt"><input type="radio" id='counter_enable_options_y' name="apss_share_settings[counter_enable_options]" value="1" <?php
                            if ( $options[ 'counter_enable_options' ] == '1' ) {
                                echo "checked='checked'";
                            }
                            ?> /><label for="counter_enable_options_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
                        </div>

                        <div class="apss-counter-api-options apss-counter-settings clearfix" style="<?php
                        if ( isset( $options[ 'counter_enable_options' ] ) && $options[ 'counter_enable_options' ] == '1' ) {
                            echo 'display:block';
                        } else {
                            echo 'display:none';
                        }
                        ?>">
                        <div class='apss-counter-api'>
                            <input type="radio" id='apss_twitter_counter_option' name="apss_share_settings[twitter_counter_api]" value="1" <?php
                            if ( isset( $options[ 'twitter_counter_api' ] ) ) {
                                if ( $options[ 'twitter_counter_api' ] == '1' ) {
                                    echo "checked='checked'";
                                }
                            }
                            ?> />
                            <label for="apss_twitter_counter_option"><?php _e( "Don't show Twitter share counts", 'accesspress-social-share' ); ?></label>
                            <div class="apss_notes_cache_settings"> Please select this option if you don't want to show twitter share counts.</div>
                        </div>

                        <div class='apss-counter-api'>
                            <input type="radio" id='apss_twitter_counter_option_1' name="apss_share_settings[twitter_counter_api]" value="2" <?php
                            if ( isset( $options[ 'twitter_counter_api' ] ) ) {
                                if ( $options[ 'twitter_counter_api' ] == '2' ) {
                                    echo "checked='checked'";
                                }
                            }
                            ?> />
                            <label for="apss_twitter_counter_option_1"><?php _e( 'Use', 'accesspress-social-share' ); ?> <a href='http://newsharecounts.com' target='_blank'>NewShareCounts</a><?php _e( ' to show Twitter share counts', 'accesspress-social-share' ); ?></label>
                            <div class="apss_notes_cache_settings"> To use newsharecount public API, you have to enter your website url <?php echo esc_url(site_url()); ?> and sign in using Twitter at their <a href='http://newsharecounts.com/' target='_blank'>website</a>.</div>
                        </div>

                        <div class='apss-counter-api'>
                            <input type="radio" id='apss_twitter_counter_option_2' name="apss_share_settings[twitter_counter_api]" value="3" <?php
                            if ( isset( $options[ 'twitter_counter_api' ] ) ) {
                                if ( $options[ 'twitter_counter_api' ] == '3' ) {
                                    echo "checked='checked'";
                                }
                            }
                            ?> /><label for="apss_twitter_counter_option_2"><?php _e( 'Use', 'accesspress-social-share' ); ?> <a href='	http://opensharecount.com/' target='_blank'>OpenShareCount</a><?php _e( ' to show Twitter share counts', 'accesspress-social-share' ); ?></label>
                            <div class="apss_notes_cache_settings"> To use opensharecount public API, you have to sign up and register your website url <?php echo esc_url(site_url()); ?> at their <a href='http://opensharecount.com/' target='_blank'>website</a>. </div>
                        </div>
                        <div class="apss_notes_cache_settings"> Note: If you switch the API please don't forget to clear cache for fetching new share counts.</div>

                        <div class="apss_counter-api">
                            <h4>If facebook counter is not working. Please setup the facebook APP and enter required details below.</h4>
                            <div class='apss_input_wrapper'>
                                <label for=apss_facebook_app_id"">APP ID: </label><input type='text' id="apss_facebook_app_id" name='apss_share_settings[api_configuration][facebook][app_id]' value="<?php
                                if ( isset( $options[ 'api_configuration' ][ 'facebook' ][ 'app_id' ] ) ) {
                                    echo esc_attr($options[ 'api_configuration' ][ 'facebook' ][ 'app_id' ]);
                                }
                                ?>" />
                                <div class="apss_notes_cache_settings">Please go to <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a> and create an app and get the App ID.</div>
                            </div>
                            <div class='apss_input_wrapper'>
                                <label for=apss_facebook_app_secret"">APP Secret: </label><input type='text' id="apss_facebook_app_secret" name='apss_share_settings[api_configuration][facebook][app_secret]' value="<?php
                                if ( isset( $options[ 'api_configuration' ][ 'facebook' ][ 'app_secret' ] ) ) {
                                    echo esc_attr($options[ 'api_configuration' ][ 'facebook' ][ 'app_secret' ]);
                                }
                                ?>" style="width:285px;" />
                                <div class="apss_notes_cache_settings">Please go to <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a> and create an app and get the App Secret.</div>
                            </div>
                            <div class="apss_notes_cache_settings">
                                <b>Please note that you should make your APP live.</b>
                                You can get the details instruction for creating facebook app <a href='http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-share/?p=89' target="_blank">here</a>.
                            </div>
                        </div>
                    </div>

                    <div class="apss-total-counter-settings clearfix">
                        <h4><?php _e( 'Social share total counter enable?', 'accesspress-social-share' ); ?> </h4>
                        <div class="misc-opt"><input type="radio" id='total_counter_enable_options_n' name="apss_share_settings[total_counter_enable_options]" value="0" <?php
                        if ( isset( $options[ 'total_counter_enable_options' ] ) && $options[ 'total_counter_enable_options' ] == '0' ) {
                            echo "checked='checked'";
                        }
                        ?> /><label for="total_counter_enable_options_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
                        <div class="misc-opt"><input type="radio" id='total_counter_enable_options_y' name="apss_share_settings[total_counter_enable_options]" value="1" <?php
                        if ( isset( $options[ 'total_counter_enable_options' ] ) && $options[ 'total_counter_enable_options' ] == '1' ) {
                            echo "checked='checked'";
                        }
                        ?> /><label for="total_counter_enable_options_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
                    </div>

                    <div class='apss_cache_enable_opt'>
                        <h4><?php _e( 'Fetch the share counts from HTTP url as well? ', 'accesspress-social-share' ); ?> </h4>
                        <div class='misc-opt'>
                            <input type="radio" id='enable_http_count_yes' name="apss_share_settings[enable_http_count]" value="1" <?php
                            if ( isset( $options[ 'enable_http_count' ] ) && $options[ 'enable_http_count' ] == '1' ) {
                                echo "checked='checked'";
                            }
                            ?> />
                            <label for='enable_http_count_yes'><?php _e( 'Yes', 'accesspress-social-share' ); ?></label>
                        </div>
                        <div class='misc-opt'>
                            <input type="radio" id='enable_http_count_no' name="apss_share_settings[enable_http_count]" value="0" <?php
                            if ( isset( $options[ 'enable_http_count' ] ) && $options[ 'enable_http_count' ] == '0' ) {
                                echo "checked='checked'";
                            }
                            ?> />
                            <label for='enable_http_count_no'><?php _e( 'No', 'accesspress-social-share' ); ?></label>
                        </div>
                        <br />
                        <div class="apss_notes_cache_settings">
                            <?php _e( "<b>Note:</b> Please select this option if you have moved your site from HTTP to HTTPS. For Facebook, The crawler still needs to be able to access the old page, so exempt the crawler's user agent from the redirect and only send an HTTP redirect to non-Facebook crawler clients. If you have done 301 redirect then the old url share counts will be lost.", 'accesspress-social-share' ); ?>
                        </div>
                    </div>
                    <br />

                    <div class="apss-dialog-boxs clearfix">
                        <h4><?php _e( 'Social share link options:', 'accesspress-social-share' ); ?> </h4>
                        <div class="misc-opt"><input type="radio" id='dialog_box_options_1' name="apss_share_settings[dialog_box_options]" value="0" <?php
                        if ( $options[ 'dialog_box_options' ] == '0' ) {
                            echo "checked='checked'";
                        }
                        ?> /><label for="dialog_box_options_1"><?php _e( 'Open in same window', 'accesspress-social-share' ); ?></label></div>
                        <div class="misc-opt"><input type="radio" id='dialog_box_options_2' name="apss_share_settings[dialog_box_options]" value="1" <?php
                        if ( $options[ 'dialog_box_options' ] == '1' ) {
                            echo "checked='checked'";
                        }
                        ?> /><label for="dialog_box_options_2"><?php _e( 'Open in new window/Tab', 'accesspress-social-share' ); ?></label></div>

                        <div class="misc-opt"><input type="radio" id='dialog_box_options_3' name="apss_share_settings[dialog_box_options]" value="2" <?php
                        if ( $options[ 'dialog_box_options' ] == '2' ) {
                            echo "checked='checked'";
                        }
                        ?> /><label for="dialog_box_options_3"><?php _e( 'Open in popup window', 'accesspress-social-share' ); ?></label></div>
                    </div>
                    <div class='apss_cache_enable_opt'>
                        <h4><?php _e( 'Enable cache? ', 'accesspress-social-share' ); ?> </h4>
                        <div class='misc-opt'>
                            <input type="radio" id='enable_cache_yes' name="apss_share_settings[enable_cache]" value="1" <?php
                            if ( isset( $options[ 'enable_cache' ] ) && $options[ 'enable_cache' ] == '1' ) {
                                echo "checked='checked'";
                            }
                            ?> />
                            <label for='enable_cache_yes'><?php _e( 'Yes', 'accesspress-social-share' ); ?></label>
                        </div>
                        <div class='misc-opt'>
                            <input type="radio" id='enable_cache_no' name="apss_share_settings[enable_cache]" value="0" <?php
                            if ( isset( $options[ 'enable_cache' ] ) && $options[ 'enable_cache' ] == '0' ) {
                                echo "checked='checked'";
                            }
                            ?> />
                            <label for='enable_cache_no'><?php _e( 'No', 'accesspress-social-share' ); ?></label>
                        </div>
                    </div>
                    <br />
                    <div class='cache-settings'>
                        <h4><?php _e( 'Cache Settings: ', 'accesspress-social-share' ); ?> </h4>
                        <div class="apss_input_wrapper">
                            <label for="apss_cache_settings"><?php _e( 'Cache Period:', 'accesspress-social-share' ); ?></label>
                            <input type='text' id="apss_cache_period" name='apss_share_settings[cache_settings]' value="<?php
                            if ( isset( $options[ 'cache_period' ] ) ) {
                                echo esc_attr($options[ 'cache_period' ]);
                            }
                            ?>" onkeyup="removeMe('invalid_cache_period');" style='width:50px;'/>
                            <span class="error invalid_cache_period"></span>
                            <div class="apss_notes_cache_settings"><?php _e( 'Please enter the time in hours in which the social share counter should be updated from social networks. Default is 24 hours.', 'accesspress-social-share' ); ?></div>

                        </div>
                    </div>
                    <div class="apss-email-settings">
                        <h4><?php _e( 'Email Settings:', 'accesspress-social-share' ); ?></h4>
                        <div class="app-email-sub email-setg">
                            <label for='apss-email-subject'><?php _e( 'Email subject:', 'accesspress-social-share' ); ?></label>
                            <input type='text' name="apss_share_settings[apss_email_subject]" value="<?php echo esc_attr($options[ 'apss_email_subject' ]); ?>" style='width:325px;'/>
                        </div>
                        <div class="app-email-body email-setg">
                            <label for='apss-email-body'><?php _e( 'Email body:', 'accesspress-social-share' ); ?></label>
                            <textarea rows='30' cols='30' name="apss_share_settings[apss_email_body]"><?php echo esc_attr($options[ 'apss_email_body' ]); ?></textarea>
                        </div>
                        <div class="apss_notes_cache_settings">
                            Available parameters: <br />
                            %%url%% = current page/post url(custom url if you have used "custom_share_link" attribute in the shortcode ) <br />
                            %%title%% = current page/post's title <br />
                            %%permalink%% = current page/post url <br />
                            %%siteurl%% = Site url <br />
                        </div>
                    </div>
                    <?php  include( APSS_PATH . '/inc/backend/save-button.php') ?>
                </div>

                <div class="apss-tab-contents apss-how-to-use" id="tab-apss-how-to-use" style='display:none' ><?php include_once('how-to-use.php'); ?></div>
                <div class="apss-tab-contents apss-about" id="tab-apss-about" style='display:none' ><?php include('about-apss.php'); ?></div>
                </div>
            </div>
        </form>
    </div>
    <div class="apss-upgrade-wrapper">
        <a href="<?php echo esc_attr(APSS_PRO_LINK); ?>" target="_blank">
            <img src="<?php echo esc_attr(APSS_IMAGE_DIR) . '/upgrade-to-pro/upgrade-to-pro.png' ?>" style="width:100%;">
        </a>

        <div class="apss-upgrade-button-wrap-backend">

            <a href="<?php echo esc_attr(APSS_PRO_DEMO); ?>" class="smls-demo-btn" target="_blank">Demo</a>

            <a href="<?php echo esc_attr(APSS_PRO_LINK); ?>" target="_blank" class="smls-upgrade-btn">Upgrade</a>

            <a href="<?php echo esc_attr(APSS_PRO_DETAIL); ?>" target="_blank" class="smls-upgrade-btn">Plugin Information</a>

        </div>

        <a href="<?php echo esc_attr(APSS_PRO_LINK); ?>" target="_blank">
            <img src="<?php echo esc_attr(APSS_IMAGE_DIR); ?>/upgrade-to-pro/upgrade-to-pro-feature.png" alt="<?php _e( 'AccessPress Social Pro', 'accesspress-social-share' ); ?>" style="width:100%;">
        </a>
    </div>
</div>
</div>