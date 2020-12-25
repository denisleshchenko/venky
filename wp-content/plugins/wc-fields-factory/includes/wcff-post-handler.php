<?php 
/**
 * @author 		  : Saravana Kumar K
 * @copyright	  : sarkware.com
 * @todo		      : One of the core class which generates all WCFF related meta boxs in Admin Screen
 *
 */
if (!defined( 'ABSPATH' )) { exit; }

class Wcff_PostForm {
    
    function __construct() {
        add_action( 'admin_head-post.php', array( $this, 'wcff_post_single_view' ) );
        add_action( 'admin_head-post-new.php',  array( $this, 'wcff_post_single_view' ) );
        add_action( 'wcff_admin_head', array( $this, 'wcff_admin_head' ) );
        add_filter( 'manage_edit-wccpf_columns', array( $this, 'wcff_columns' ) ) ;
        add_action( 'manage_wccpf_posts_custom_column', array( $this, 'wcff_post_listing' ), 10, 2 );
        add_filter( 'manage_edit-wccaf_columns', array( $this, 'wcff_columns' ) ) ;
        add_action( 'manage_wccaf_posts_custom_column', array( $this, 'wcff_post_listing' ), 10, 2 );
        add_filter( 'manage_edit-wccsf_columns', array( $this, 'wcff_columns' ) ) ;
        add_action( 'manage_wccsf_posts_custom_column', array( $this, 'wcff_post_listing' ), 10, 2 );
        add_filter( 'manage_edit-wccrf_columns', array( $this, 'wcff_columns' ) ) ;
        add_action( 'manage_wccrf_posts_custom_column', array( $this, 'wcff_post_listing' ), 10, 2 );
        add_action( 'admin_head-edit.php', array( $this, 'wcff_post_admin_listing' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'wcff_admin_enqueue_scripts' ) );
    }
    
    function wcff_post_single_view() {
        if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccsf" ) || $this->wcff_check_screen( "wccrf" )|| $this->wcff_check_screen( "wcccf" ) ) {
            
            $fields_meta_title = "Product Fields";
            if ( $this->wcff_check_screen( "wccaf" ) ) {
                $fields_meta_title = "Admin Fields";
            } else if ( $this->wcff_check_screen( "wccsf" ) ) {
                $fields_meta_title = "Sub Fields";
            } else if ( $this->wcff_check_screen( "wccrf" ) ) {
                $fields_meta_title = "Repeater Fields";
            }
            
            add_meta_box( 'wcff_fields', $fields_meta_title, array($this, 'inject_fields_meta_box'), get_current_screen() -> id, 'normal', 'high');
            
            if( !$this->wcff_check_screen( "wccrf" ) ) {
                add_meta_box( 'wcff_factory', "Fields Factory", array($this, 'inject_factory_meta_box'), get_current_screen() -> id, 'normal', 'high');
            } else {
                add_meta_box( 'wcff_repeater_factory', "Repeater Factory", array($this, 'inject_reapeater_meta_box'), get_current_screen() -> id, 'normal', 'high');
            }
            
            if( !$this->wcff_check_screen( "wccsf" ) && !$this->wcff_check_screen( "wcccf" ) ) {
                add_meta_box( 'wcff_conditions', "Conditions", array($this, 'inject_logics_meta_box'), get_current_screen() -> id, 'normal', 'high');
            }
            
            if( $this->wcff_check_screen( "wccaf" ) ) {
                add_meta_box( 'wcff_locations', "Locations", array($this, 'inject_locations_meta_box'), get_current_screen() -> id, 'normal', 'high');
            }
            
            if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf"  ) || $this->wcff_check_screen( "wcccf" ) ) {
            	add_meta_box( 'wcff_fields_selector', "Fields Type", array($this, 'inject_field_selector_meta_box'), get_current_screen() -> id, 'side', 'high');
            }
            
            if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf"  ) ) {
                add_meta_box( 'wcff_product_field_location', "Field Location", array( $this, 'inject_wcff_product_field_location'), get_current_screen() -> id, 'normal', 'low');
            }
            
            do_action( 'wcff_admin_head' );
        }
    }
    
    function wcff_columns( $columns ) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title' ),
            'fields' => __( 'Fields' )
        );
        return $columns;
    }
    
    function wcff_post_listing( $column, $post_id ) {
        global $post;
        
        switch( $column ) {
            case 'fields' :
                $count =0;
                $keys = get_post_custom_keys( $post_id );
                
                if($keys) {
                    foreach($keys as $key) {
                        if( ( strpos($key, 'wccpf_') !== false || strpos($key, 'wccaf_') !== false || strpos($key, 'wccsf_') !== false ) && ( strpos($key, 'group_rules') === false && strpos($key, 'condition_rules') === false && strpos($key, 'location_rules') === false && strpos($key, 'field_location_on_product') === false && strpos($key, 'field_location_on_archive') === false ) ) {
                            $count++;
                        }
                    }
                }
                echo $count;
                break;
        }
    }
    
    function inject_fields_meta_box() {
        if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccsf" ) || $this->wcff_check_screen( "wccrf" ) || $this->wcff_check_screen( "wcccf" ) ) {
            include( wcff()->info['path'] . 'views/meta_box_fields.php' );
        }
    }
    
    function inject_factory_meta_box() {
        if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccsf" ) || $this->wcff_check_screen( "wcccf" ) ) {
            include( wcff()->info['path'] . 'views/meta_box_factory.php' );
        }
    }
    
    function inject_reapeater_meta_box() {
        if( $this->wcff_check_screen( "wccrf" ) ) {
            include( wcff()->info['path'] . 'views/meta_box_repeater.php' );
        }
    }
    
    function inject_logics_meta_box() {
        if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf" )  || $this->wcff_check_screen( "wccrf" )) {
            include( wcff()->info['path'] . 'views/meta_box_conditions.php' );
        }
    }
    
    function inject_locations_meta_box() {
        if( $this->wcff_check_screen( "wccaf" ) ) {
            include( wcff()->info['path'] . 'views/meta_box_locations.php' );
        }
    }
    
    function inject_field_selector_meta_box() {
        if( $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wcccf" )  ) {
    		include( wcff()->info['path'] . 'views/meta_box_fields_selector.php' );
    	}
    }
    
    function inject_wcff_product_field_location(){
        if( $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccpf" ) ) {
            include( wcff()->info['path'] . 'views/meta_box_field_location.php' );
        }
    }
   
    function wcff_admin_enqueue_scripts() {
        if( $this->wcff_check_screen( "wccpf" ) || $this->wcff_check_screen( "wccaf" ) || $this->wcff_check_screen( "wccsf" ) || $this->wcff_check_screen( "wccrf" ) || $this->wcff_check_screen( "wcccf" ) ) {
        	wp_enqueue_script('jquery-ui-core');
        	wp_enqueue_script('jquery-ui-tabs');
        	wp_enqueue_script('jquery-ui-sortable');
        	wp_enqueue_script('wp-color-picker');
        	wp_enqueue_script('wcff-script');        	
            wp_enqueue_style(array(
                'thickbox',
                'wp-color-picker',
                'wcff-style'
            ));
            wp_enqueue_media();
        }
    }
    
    function wcff_check_screen( $scr_id ) {
        if( $scr_id == "wccpf-options" ) {
            return ( ( get_current_screen() -> id == "wccpf" ) || ( get_current_screen() -> id == "wccaf" ) || ( get_current_screen() -> id == "wccsf" ) || ( get_current_screen() -> id == "wcccf" ) || ( get_current_screen() -> id == "wccrf" ) || get_current_screen() -> id == "wccpf-options" );
        }
        return get_current_screen() -> id == $scr_id;
    }
    
    function wcff_admin_head() {
		global $post; 
		$wccpf_options = wcff()->option->get_options();
		$supported_locale = isset( $wccpf_options["supported_lang"] ) ? $wccpf_options["supported_lang"] : array();	?>
<script type="text/javascript">
var wcff_var = {
	post_id : <?php echo $post->ID; ?>,
	post_type : "<?php echo $post->post_type; ?>",
	nonce  : "<?php echo wp_create_nonce( get_current_screen() -> id .'_nonce' ); ?>",
	admin_url : "<?php echo admin_url(); ?>",
	ajaxurl : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
	version : "<?php echo wcff()->info["version"]; ?>",	
	locales: <?php echo json_encode($supported_locale); ?>,
	plugin_dir: "<?php echo plugins_url("", __dir__); ?>" 
};		
</script>
<?php
	}
	
	function wcff_post_admin_listing( $hook_suffix ) {
	    include( wcff()->info["path"]. '/views/meta_box_sarkware.php' );
	}
    
}

new Wcff_PostForm();

?>