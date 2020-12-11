<?php 
wp_nonce_field( 'apss_nonce_save_settings', 'apss_add_nonce_save_settings' );
wp_nonce_field( 'apss_settings_action', 'apss_settings_action' ); ?>
<input type="submit" class="submit_settings button primary-button" value="<?php _e( 'Save settings', 'accesspress-social-share' ); ?>" name="apss_submit_settings" id="apss_submit_settings"/>
<?php $nonce = wp_create_nonce( 'apss-restore-default-settings-nonce' ); ?>
<?php $nonce_clear = wp_create_nonce( 'apss-clear-cache-nonce' ); ?>
<a href="<?php echo admin_url() . 'admin-post.php?action=apss_restore_default_settings&_wpnonce=' . $nonce; ?>" onclick="return confirm('<?php _e( 'Are you sure you want to restore default settings?', 'accesspress-social-share' ); ?>')"><input type="button" value="Restore Default Settings" class="apss-reset-button button primary-button"/></a>
<a href="<?php echo admin_url() . 'admin-post.php?action=apss_clear_cache&_wpnonce=' . $nonce_clear; ?>" onclick="return confirm('<?php _e( 'Are you sure you want to clear cache share counter?', 'accesspress-social-share' ); ?>')"><input type="button" value="Clear Cache" class="apss-reset-button button primary-button"/></a>