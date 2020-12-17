<?php

if ( storevilla_is_woocommerce_activated() ) {    
    
    /**
     * Adds storevilla_latest_product_cat_widget widget.
    */
    add_action('widgets_init', 'storevilla_latest_product_cat_widget');
    function storevilla_latest_product_cat_widget() {
        register_widget('storevilla_latest_product_cat_widget_area');
    }    
    class storevilla_latest_product_cat_widget_area extends WP_Widget {
    
        /**
         * Register widget with WordPress.
        **/
        public function __construct() {
            parent::__construct(
                'storevilla_latest_product_cat_widget_area', __('SV: Woo Latest Category Product','storevilla'), array(
                'description' => __('A widget that shows WooCommerce category latest product.', 'storevilla')
            ));
        }
        
        private function widget_fields() {
            
    
            $prod_type = array(
                'category' => __('Category', 'storevilla'),
                'latest_product' => __('Latest Product', 'storevilla'),
                'feature_product' => __('Feature Product', 'storevilla'),
            );
            
              $taxonomy     = 'product_cat';
              $empty        = 1;
              $orderby      = 'name';  
              $show_count   = 0;      // 1 for yes, 0 for no
              $pad_counts   = 0;      // 1 for yes, 0 for no
              $hierarchical = 1;      // 1 for yes, 0 for no  
              $title        = '';  
              $empty        = 0;
              $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
              );
    
              $woocommerce_categories = array();
              $woocommerce_categories_obj = get_categories($args);
              $woocommerce_categories[''] = esc_html__('Select Product Category','storevilla');
              foreach ($woocommerce_categories_obj as $category) {
                $woocommerce_categories[$category->term_id] = $category->name;
              }
              
    
            $fields = array( 
                
                'storevilla_top_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_top_product_title',
                    'storevilla_widgets_title' => __('Top Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_main_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_main_product_title',
                    'storevilla_widgets_title' => __('Product Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_product_type' => array(
                    'storevilla_widgets_name' => 'storevilla_product_type',
                    'storevilla_widgets_title' => __('Select Product Type', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_woo_category' => array(
                    'storevilla_widgets_name' => 'storevilla_woo_category',
                    'storevilla_widgets_title' => __('Select Product Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
                'storevilla_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),                                 
            );
    
            return $fields;
        }
    
        public function widget($args, $instance) {
            extract($args);
            extract($instance);            
            /**
             * wp query for first block
            */  
            $top_title        = esc_html( empty( $instance['storevilla_top_product_title'] )  ? '' : $instance['storevilla_top_product_title'] );
            $main_title       = esc_html( empty( $instance['storevilla_main_product_title'] ) ? '' : $instance['storevilla_main_product_title'] );
            $product_type     = esc_attr( empty( $instance['storevilla_product_type'] ) ? '' : $instance['storevilla_product_type'] );
            $product_category = intval( empty( $instance['storevilla_woo_category'] )   ? '' : $instance['storevilla_woo_category'] );
            $product_number   = intval( empty( $instance['storevilla_product_number'] ) ? '' : $instance['storevilla_product_number'] );
            $product_args = storevilla_woocommerce_query($product_type, $product_category, $product_number);
            echo wp_kses_post($before_widget); 
        ?>
        
            <div class="sp-producttype-wrap">
    
                    <div class="store-container">

                        <div id="product-slider" class="product-slide-area">
                            
                            <div class="block-title-wrap clearfix">
                            
                                <div class="block-title">
                                    <?php if( !empty( $top_title ) ) { ?><span><?php echo esc_html( $top_title ); ?></span> <?php } ?>
                                    <?php if( !empty( $main_title ) ) { ?><h2><?php echo esc_html( $main_title ); ?></h2> <?php } ?>
                                </div>

                                <div class="StoreVillaAction">
                                    <div class="villa-lSPrev"></div>
                                    <div class="villa-lSNext"></div>
                                </div>

                            </div>
                            
                            <ul class="latest-product-slider cS-hidden">
                                <?php                         
                                    $query = new WP_Query($product_args);
                                    if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                    
                                <?php } } wp_reset_postdata(); ?>                    
                            </ul>
                          
                        </div>
                    </div>
    
            </div><!-- End Product Slider --> 
    
        <?php         
            echo wp_kses_post($after_widget);
        }
       
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
            }
            return $instance;
        }
    
        public function form($instance) {
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
                storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
            }
        }
    } 
    
    /**
     * Adds storevilla_cat_with_product_widget widget.
    */
    add_action('widgets_init', 'storevilla_cat_with_product_widget');
    function storevilla_cat_with_product_widget() {
        register_widget('storevilla_cat_with_product_widget_area');
    }    
    class storevilla_cat_with_product_widget_area extends WP_Widget {
    
        /**
         * Register widget with WordPress.
        **/
        public function __construct() {
            parent::__construct(
                'storevilla_cat_with_product_widget_area', __('SV: Woo Category With Product','storevilla'), array(
                'description' => __('A widget that shows woocommerce category feature image with selected category products', 'storevilla')
            ));
        }
        
        private function widget_fields() {
            
              $prod_type = array(
                'right_align' => __('Right Align Category Image', 'storevilla'),
                'left_align' => __('Left Align Category Image', 'storevilla'),
              );
    
              $taxonomy     = 'product_cat';
              $empty        = 1;
              $orderby      = 'name';  
              $show_count   = 0;      // 1 for yes, 0 for no
              $pad_counts   = 0;      // 1 for yes, 0 for no
              $hierarchical = 1;      // 1 for yes, 0 for no  
              $title        = '';  
              $empty        = 0;
              $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
              );
    
              $woocommerce_categories = array();
              $woocommerce_categories_obj = get_categories($args);
              $woocommerce_categories[''] = 'Select Product Category';
              foreach ($woocommerce_categories_obj as $category) {
                $woocommerce_categories[$category->term_id] = $category->name;
              }
    
            $fields = array( 
                
                'storevilla_cat_main_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_cat_main_product_title',
                    'storevilla_widgets_title' => __('Product Category Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_cat_image_aligment' => array(
                    'storevilla_widgets_name' => 'storevilla_cat_image_aligment',
                    'storevilla_widgets_title' => __('Select Display Style (Image Alignment)', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_woo_category' => array(
                    'storevilla_widgets_name' => 'storevilla_woo_category',
                    'storevilla_widgets_title' => __('Select Product Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
                'storevilla_cat_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_cat_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),                                 
            );
    
            return $fields;
        }
    
        public function widget($args, $instance) {
            extract($args);
            extract($instance);
            /**
             * wp query for first block
            */
            $main_title       = esc_html( empty( $instance['storevilla_cat_main_product_title'] ) ? '' : $instance['storevilla_cat_main_product_title'] ); 
            $cat_aligment     = esc_attr( empty( $instance['storevilla_cat_image_aligment'] )     ? '' : $instance['storevilla_cat_image_aligment'] );
            $product_category = intval( empty( $instance['storevilla_woo_category'] )             ? '' : $instance['storevilla_woo_category'] );
            $product_number   = intval( empty( $instance['storevilla_cat_product_number'] )       ? '' : $instance['storevilla_cat_product_number'] );
            if(!empty($product_category)){
              $cat_id = get_term($product_category,'product_cat');
              if($cat_id != '') {
                $category_id = $cat_id->term_id;
                $category_link = get_term_link( $category_id,'product_cat' ); 
              } else {
                $category_link = get_permalink( wc_get_page_id( 'shop' ) );
              }
            }
            else{
              $category_link = get_permalink( wc_get_page_id( 'shop' ) );
            }     
            echo wp_kses_post($before_widget);  
        ?>    
            <div class="categor-products">

                <div class="store-container">
                    
                    <div id="category-product-slider" class="product-cat-slide clearfix <?php echo esc_attr($cat_aligment); ?>">
                        
                        <div class="home-block-inner">                                                
                            <?php 
                                $taxonomy = 'product_cat';                                
                                $terms = term_description( $product_category, $taxonomy );
                                $terms_name = get_term( $product_category, $taxonomy );
                            ?>
                                
                            <div class="block-title">
                                <?php if( !empty( $main_title ) ) { ?><span><?php echo esc_html( $main_title ); ?></span> <?php } ?>
                                <?php if(isset($terms_name->name)) : ?>
                                <h2><?php echo esc_html( $terms_name->name); ?></h2>
                                <?php endif; ?>
                            </div>

                            <div class="cat-block-wrap">
                                <?php 
                                    $thumbnail_id = get_term_meta($product_category, 'thumbnail_id', true);
                                    if (!empty($thumbnail_id)) {
                                      $image = wp_get_attachment_image_src($thumbnail_id, 'storevilla-cat-image');
                                      echo '<a href="' .esc_url($category_link). '" class="store-overlay" style="background-image:url('.esc_url($image[0]).');">
                                      </a>';
                                    } ?>                            
                                    
                                <div class="block-title-desc">
                                    <?php echo wp_kses_post($terms); ?>
                                    <a href="<?php echo esc_url($category_link); ?>" class="view-bnt"><?php esc_html_e('View All','storevilla'); ?></a>
                                </div>
                            </div>
                            
                        </div>
                        
                        <ul class="cat-with-product cS-hidden">
                            
                            <?php 
                                $product_args = array(
                                    'post_type' => 'product',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy'  => 'product_cat',
                                            'field'     => 'id', 
                                            'terms'     => $product_category                                                                 
                                        )),
                                    'posts_per_page' => $product_number
                                );
                                $query = new WP_Query($product_args);

                                if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                            ?>
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                                
                            <?php } } wp_reset_query(); ?>  
                            
                        </ul>
                        
                    </div>

                </div>
 
            </div><!-- End Bestsell Slider --> 
    
        <?php         
            echo wp_kses_post($after_widget);
        }
       
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
            }
            return $instance;
        }
    
        public function form($instance) {
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
                storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
            }
        }
    }

    /**
     * Adds storevilla_cat_widget widget.
    */
    add_action('widgets_init', 'storevilla_cat_widget');
    function storevilla_cat_widget() {
        register_widget('storevilla_cat_widget_area');
    }    
    class storevilla_cat_widget_area extends WP_Widget {
    
        /**
         * Register widget with WordPress.
        **/
        public function __construct() {
            parent::__construct(
                'storevilla_cat_widget_area', __('SV: Woo Category Section','storevilla'), array(
                'description' => __('A widget that shows WooCommerce category', 'storevilla')
            ));
        }
        
        private function widget_fields() {
    
              $taxonomy     = 'product_cat';
              $empty        = 1;
              $orderby      = 'name';  
              $show_count   = 0;      // 1 for yes, 0 for no
              $pad_counts   = 0;      // 1 for yes, 0 for no
              $hierarchical = 1;      // 1 for yes, 0 for no  
              $title        = '';  
              $empty        = 0;
              $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
              );
    
              $woocommerce_categories = array();
              $woocommerce_categories_obj = get_categories($args);
              foreach ($woocommerce_categories_obj as $category) {
                $woocommerce_categories[$category->term_id] = $category->name;
              }
    
    
            $fields = array( 
                
                'storevilla_top_cat_bg_image' => array(
                    'storevilla_widgets_name' => 'storevilla_top_cat_bg_image',
                    'storevilla_widgets_title' => __('Category Full Background Image', 'storevilla'),
                    'storevilla_widgets_field_type' => 'upload',
                ),

                'storevilla_top_cat_title' => array(
                    'storevilla_widgets_name' => 'storevilla_top_cat_title',
                    'storevilla_widgets_title' => __('Category Top Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                'storevilla_main_cat_title' => array(
                    'storevilla_widgets_name' => 'storevilla_main_cat_title',
                    'storevilla_widgets_title' => __('Category Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_select_category' => array(
                    'storevilla_widgets_name' => 'storevilla_select_category',
                    'storevilla_mulicheckbox_title' => __('Select Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'multicheckboxes',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
            );
    
            return $fields;
        }
    
        public function widget($args, $instance) {
            extract($args);
            extract($instance);
            
            /**
            ** wp query for first block
            **/  
            $top_title          = esc_html( empty( $instance['storevilla_top_cat_title'] )      ? '' : $instance['storevilla_top_cat_title'] ); 
            $main_title         = esc_textarea( empty( $instance['storevilla_main_cat_title'] ) ? '' : $instance['storevilla_main_cat_title'] );
            $store_villa_cat_id = empty( $instance['storevilla_select_category'] ) ? '' : $instance['storevilla_select_category'];
            $cat_bg_image       = esc_url( empty( $instance['storevilla_top_cat_bg_image'] )    ? '' : $instance['storevilla_top_cat_bg_image'] );
            echo wp_kses_post($before_widget); 
            $cat_bg_class = '';
            $bg_style = '';
            if(!empty( $cat_bg_image )) { 
                $bg_style = 'style="background-image:url('.esc_url( $cat_bg_image ) .'); background-size: cover;"';
                $cat_bg_class = 'no-bg-image';
            }?>
            <div class="category-area <?php echo esc_attr( $cat_bg_class ); ?>" <?php echo $bg_style; ?>>
               
                <div class="store-container">
                   
                    <div class="block-title-wrap clearfix">
                        
                        <div class="block-title">
                            <?php if( !empty( $top_title ) ) { ?><span><?php echo esc_html( $top_title ); ?></span> <?php } ?>
                            <?php if( !empty( $main_title ) ) { ?><h2><?php echo esc_html( $main_title ); ?></h2> <?php } ?>
                        </div>

                        <div class="StoreVillaAction">
                            <div class="villa-lSPrev"></div>
                            <div class="villa-lSNext"></div>
                        </div>

                    </div>
                    
                    <ul class="category-slider cS-hidden">
                        <?php
                            $count = 0; 
                            if(!empty($store_villa_cat_id)){
                                
                                foreach ($store_villa_cat_id as $key => $store_cat_id) {          
                                    $thumbnail_id = get_term_meta( $key, 'thumbnail_id', true );
                                    $images = wp_get_attachment_url( $thumbnail_id );
                                    $image = wp_get_attachment_image_src($thumbnail_id, 'storevilla-cat-image', true);
                                    $term = get_term_by( 'id', $key, 'product_cat');
                                if ( $term && ! is_wp_error( $term ) ) {
                                    $term_link = get_term_link($term);
                                    $term_name = $term->name;
                                    $sub_count =  apply_filters( 'woocommerce_subcategory_count_html', ' ' . $term->count . ' '.__('Products','storevilla').'', $term);
                                }else{
                                    $term_link = '#';
                                    $term_name = __('Category','storevilla');
                                    $sub_count = '0 '.__('Product','storevilla');
                                }
                        ?>
                            <li>
                                <div class="item-img">
                                    <a href="<?php echo esc_url($term_link); ?>">
                                        <?php  
                                            if ( $images ) {
                                              echo '<img class="absolute category-image" src="' . esc_url($image[0]) . '" alt="'. esc_attr($term_name) .'" />';
                                            } 
                                        ?>
                                        <div class="sv_category_count">
                                            <h3 class="sv-header-title"><?php echo esc_html($term_name); ?></h3>
                                            <p class="sv-count"><?php echo esc_html($sub_count);  ?></p>
                                        </div>
                                    </a>            
                                </div>         
                            </li>
                        <?php } }  ?>
                    </ul>
                </div>

            </div>
    
        <?php         
            echo wp_kses_post($after_widget);
        }
       
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
            }
            return $instance;
        }
    
        public function form($instance) {
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
                storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
            }
        }
    }
    
    
    /**
     * Adds storevilla_product_widget widget.
    */
    add_action('widgets_init', 'storevilla_product_widget');
    function storevilla_product_widget() {
        register_widget('storevilla_product_widget_area');
    }    
    class storevilla_product_widget_area extends WP_Widget {
    
        /**
         * Register widget with WordPress.
        **/
        public function __construct() {
            parent::__construct(
                'storevilla_product_widget_area', __('SV: Woo Product Section','storevilla'), array(
                'description' => __('A widget that shows WooCommerce all type product (Latest, Feature, On Sale, Up Sale).', 'storevilla')
            ));
        }
        
        private function widget_fields() {
            
    
            $prod_type = array(
                'latest_product' => __('Latest Product', 'storevilla'),
                'upsell_product' => __('UpSell Product', 'storevilla'),
                'feature_product' => __('Feature Product', 'storevilla'),
                'on_sale' => __('On Sale Product', 'storevilla'),
            );
    
            $fields = array( 
                
                'storevilla_top_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_top_product_title',
                    'storevilla_widgets_title' => __('Top Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_main_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_main_product_title',
                    'storevilla_widgets_title' => __('Product Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_product_type' => array(
                    'storevilla_widgets_name' => 'storevilla_product_type',
                    'storevilla_widgets_title' => __('Select Product Type', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),                                 
            );
    
            return $fields;
        }
    
        public function widget($args, $instance) {
            extract($args);
            extract($instance);
            
            /**
            ** wp query for first block
            **/  
            $top_title      = esc_html( empty( $instance['storevilla_top_product_title'] )  ? '' : $instance['storevilla_top_product_title'] );
            $main_title     = esc_html( empty( $instance['storevilla_main_product_title'] ) ? '' : $instance['storevilla_main_product_title'] );
            $product_type   = esc_attr( empty( $instance['storevilla_product_type'] ) ? '' : $instance['storevilla_product_type'] );
            $product_number = intval( empty( $instance['storevilla_product_number'] ) ? '' : $instance['storevilla_product_number']);
    
            $product_args       =   '';
            
            global $product_label_custom;
            
            if($product_type == 'latest_product'){
                $product_label_custom = __('New', 'storevilla');
                $product_args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $product_number
                );
            }
    
            elseif($product_type == 'upsell_product'){
                $product_args = array(
                    'post_type'         => 'product',
                    'posts_per_page'    => 10,
                    'meta_key'          => 'total_sales',
                    'orderby'           => 'meta_value_num',
                    'posts_per_page'    => $product_number
                );
            }
    
            elseif($product_type == 'feature_product'){
                $product_args = array(
                    'post_type'        => 'product',
                    'posts_per_page'   => $product_number,
                    'tax_query'     => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN'
                        )   
                    ),
                );
            }
    
            elseif($product_type == 'on_sale'){
                $product_args = array(
                'post_type'      => 'product',
                'meta_query'     => array(
                    'relation' => 'OR',
                    array( // Simple products type
                        'key'           => '_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    ),
                    array( // Variable products type
                        'key'           => '_min_variation_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                ));
            }
            
            echo wp_kses_post($before_widget); 
        ?>
        
            <div class="sp-producttype-wrap">
                
                <div class="store-container">
                      
                    <div id="product-slider" class="product-slide-area">
                        
                        <div class="block-title-wrap clearfix">
                            
                            <div class="block-title">
                                <?php if( !empty( $top_title ) ) { ?><span><?php echo esc_html( $top_title ); ?></span> <?php } ?>
                                <?php if( !empty( $main_title ) ) { ?><h2><?php echo esc_html( $main_title ); ?></h2> <?php } ?>
                            </div>

                            <div class="StoreVillaAction">
                                <div class="villa-lSPrev"></div>
                                <div class="villa-lSNext"></div>
                            </div>

                        </div>
                        
                        <ul class="store-product cS-hidden">
                            <?php
                            wp_reset_query();                   
                                $query = new WP_Query($product_args);
                                if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                            ?>
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                                
                            <?php } } wp_reset_query(); ?>                    
                        </ul>
                      
                    </div>

                </div>
    
            </div><!-- End Product Slider --> 
    
        <?php         
            echo wp_kses_post($after_widget);
        }
       
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
            }
            return $instance;
        }
    
        public function form($instance) {
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
                storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
            }
        }
    }       
    
    /**
     * Adds storevilla_column_product_widget widget.
    */
    add_action('widgets_init', 'storevilla_column_product_widget');
    function storevilla_column_product_widget() {
        register_widget('storevilla_column_product_widget_area');
    }    
    class storevilla_column_product_widget_area extends WP_Widget {
    
        /**
         * Register widget with WordPress.
        **/
        public function __construct() {
            parent::__construct(
                'storevilla_column_product_widget_area', __('SV: Woo Product Column','storevilla'), array(
                'description' => __('A widget that shows WooCommerce all type product (Latest, Feature, On Sale, Up Sale) in column view.', 'storevilla')
            ));
        }
        
        private function widget_fields() {
            
    
            $prod_type = array(
                'latest_product' => __('Latest Product', 'storevilla'),
                'category' => __('Category', 'storevilla'),
                'upsell_product' => __('UpSell Product', 'storevilla'),
                'feature_product' => __('Feature Product', 'storevilla'),
                'on_sale' => __('On Sale Product', 'storevilla'),
            );
            
              $taxonomy     = 'product_cat';
              $empty        = 1;
              $orderby      = 'name';  
              $show_count   = 0;      // 1 for yes, 0 for no
              $pad_counts   = 0;      // 1 for yes, 0 for no
              $hierarchical = 1;      // 1 for yes, 0 for no  
              $title        = '';  
              $empty        = 0;
              $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
              );
    
              $woocommerce_categories = array();
              $woocommerce_categories_obj = get_categories($args);
              $woocommerce_categories[''] = 'Select Product Category';
              foreach ($woocommerce_categories_obj as $category) {
                $woocommerce_categories[$category->term_id] = $category->name;
              }
    
            $fields = array(
                
                // Column One Area
                
                'banner_start_group_left_one' => array(
                    'storevilla_widgets_name' => 'banner_start_group_left_one',
                    'storevilla_widgets_title' => __('Product Column One', 'storevilla'),
                    'storevilla_widgets_field_type' => 'group_start',
                ),
                
                'storevilla_column_one_display' => array(
                    'storevilla_widgets_name' => 'storevilla_column_one_display',
                    'storevilla_widgets_title' => __('Checked to Display One Column', 'storevilla'),
                    'storevilla_widgets_field_type' => 'checkbox',
                ),
                
                'storevilla_column_one_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_column_one_product_title',
                    'storevilla_widgets_title' => __('Column One Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_column_one_product_type' => array(
                    'storevilla_widgets_name' => 'storevilla_column_one_product_type',
                    'storevilla_widgets_title' => __('Select Product Type', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_column_one_category' => array(
                    'storevilla_widgets_name' => 'storevilla_column_one_category',
                    'storevilla_widgets_title' => __('Select Product Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
                'storevilla_column_one_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_column_one_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),
                
                'banner_end_group_left_one' => array(
                    'storevilla_widgets_name' => 'banner_end_group_left_one',
                    'storevilla_widgets_field_type' => 'group_end',
                ),
                
                // Column Two Area
                'banner_start_group_left_two' => array(
                    'storevilla_widgets_name' => 'banner_start_group_left_two',
                    'storevilla_widgets_title' => __('Product Column Two', 'storevilla'),
                    'storevilla_widgets_field_type' => 'group_start',
                ),
                
                
                'storevilla_column_two_display' => array(
                    'storevilla_widgets_name' => 'storevilla_column_two_display',
                    'storevilla_widgets_title' => __('Checked to Display Two Column', 'storevilla'),
                    'storevilla_widgets_field_type' => 'checkbox',
                ),
            
                'storevilla_column_two_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_column_two_product_title',
                    'storevilla_widgets_title' => __('Column Two Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_column_two_product_type' => array(
                    'storevilla_widgets_name' => 'storevilla_column_two_product_type',
                    'storevilla_widgets_title' => __('Select Product Type', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_column_two_category' => array(
                    'storevilla_widgets_name' => 'storevilla_column_two_category',
                    'storevilla_widgets_title' => __('Select Product Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
                'storevilla_column_two_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_column_two_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),
                
                'banner_end_group_left_two' => array(
                    'storevilla_widgets_name' => 'banner_end_group_left_two',
                    'storevilla_widgets_field_type' => 'group_end',
                ),
                
                // Column Three Area
                
                'banner_start_group_left_three' => array(
                    'storevilla_widgets_name' => 'banner_start_group_left_three',
                    'storevilla_widgets_title' => __('Product Column Three', 'storevilla'),
                    'storevilla_widgets_field_type' => 'group_start',
                ),
                
                
                'storevilla_column_three_display' => array(
                    'storevilla_widgets_name' => 'storevilla_column_three_display',
                    'storevilla_widgets_title' => __('Checked to Display Three Column', 'storevilla'),
                    'storevilla_widgets_field_type' => 'checkbox',
                ),
            
                'storevilla_column_three_product_title' => array(
                    'storevilla_widgets_name' => 'storevilla_column_three_product_title',
                    'storevilla_widgets_title' => __('Column Three Main Title', 'storevilla'),
                    'storevilla_widgets_field_type' => 'title',
                ),
                
                'storevilla_column_three_product_type' => array(
                    'storevilla_widgets_name' => 'storevilla_column_three_product_type',
                    'storevilla_widgets_title' => __('Select Product Type', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $prod_type
                ),
                
                'storevilla_column_three_category' => array(
                    'storevilla_widgets_name' => 'storevilla_column_three_category',
                    'storevilla_widgets_title' => __('Select Product Category', 'storevilla'),
                    'storevilla_widgets_field_type' => 'select',
                    'storevilla_widgets_field_options' => $woocommerce_categories
                ),
                
                
                'storevilla_column_three_product_number' => array(
                    'storevilla_widgets_name' => 'storevilla_column_three_product_number',
                    'storevilla_widgets_title' => __('Enter Number of Products Show', 'storevilla'),
                    'storevilla_widgets_field_type' => 'number',
                ),
                
                'banner_end_group_left_three' => array(
                    'storevilla_widgets_name' => 'banner_end_group_left_three',
                    'storevilla_widgets_field_type' => 'group_end',
                ),
            );
    
            return $fields;
        }
    
        public function widget($args, $instance) {
            extract($args);
            extract($instance);
            
            /**
             * wp query for first block
            */              
            // Column Area One            
            $col_one_display           = intval( empty( $instance['storevilla_column_one_display'] )  ? '' : $instance['storevilla_column_one_display'] );
            $col_one_title             = esc_html( empty( $instance['storevilla_column_one_product_title'] ) ? '' : $instance['storevilla_column_one_product_title'] );
            $col_one_product_type      = esc_html( empty( $instance['storevilla_column_one_product_type'] )  ? '' : $instance['storevilla_column_one_product_type'] );
            $col_one_product_category  = intval( empty( $instance['storevilla_column_one_category'] ) ? '' : $instance['storevilla_column_one_category'] );
            $col_one_product_number    = intval( empty( $instance['storevilla_column_one_product_number'] ) ? '' : $instance['storevilla_column_one_product_number'] );            
            $product_args_one = storevilla_woocommerce_query($col_one_product_type, $col_one_product_category, $col_one_product_number);
            
            // Column Area Two            
            $col_two_display           = intval( empty( $instance['storevilla_column_two_display'] ) ? '' : $instance['storevilla_column_two_display'] );
            $col_two_title             = esc_html( empty( $instance['storevilla_column_two_product_title'] ) ?  '' : $instance['storevilla_column_two_product_title'] );
            $col_two_product_type      = esc_html( empty( $instance['storevilla_column_two_product_type'] )  ? '' : $instance['storevilla_column_two_product_type'] );
            $col_two_product_category  = intval( empty( $instance['storevilla_column_two_category'] )        ? '' : $instance['storevilla_column_two_category'] );
            $col_two_product_number    = intval( empty( $instance['storevilla_column_two_product_number'] )  ? '' : $instance['storevilla_column_two_product_number'] );            
            $product_args_two = storevilla_woocommerce_query($col_two_product_type, $col_two_product_category, $col_two_product_number);
            
            // Column Area Three            
            $col_three_display           = intval( empty( $instance['storevilla_column_three_display'] ) ? '' : $instance['storevilla_column_three_display'] );
            $col_three_title             = esc_html( empty( $instance['storevilla_column_three_product_title'] ) ? '' : $instance['storevilla_column_three_product_title'] );
            $col_three_product_type      = esc_html( empty( $instance['storevilla_column_three_product_type'] )  ? '' : $instance['storevilla_column_three_product_type'] );
            $col_three_product_category  = intval( empty( $instance['storevilla_column_three_category'] ) ? '' : $instance['storevilla_column_three_category'] );
            $col_three_product_number    = intval( empty( $instance['storevilla_column_three_product_number'] ) ? '' : $instance['storevilla_column_three_product_number'] );            
            $product_args_three = storevilla_woocommerce_query($col_three_product_type, $col_three_product_category, $col_three_product_number);
            
            echo wp_kses_post($before_widget); 
        ?>
        
            <div class="column-wrap clearfix">

                <div class="store-container">
                    <div class="col-wrap clearfix">
                        <?php if(!empty( $col_one_display ) && $col_one_display == 1 ){ ?> 
                            <div id="col-product-area-one" class="col-product-area-one">
            
                                <div class="block-title">
                                    <?php if( !empty( $col_one_title ) ) { ?><h2><?php echo esc_html( $col_one_title ); ?></h2> <?php } ?>
                                </div>
                                
                                <div class="col-slider-items">
                                    <?php                         
                                        $query = new WP_Query($product_args_one);
                                        
                                        if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                    ?>
                                        <?php wc_get_template_part( 'content', 'product' ); ?>
                                        
                                    <?php } } wp_reset_query(); ?>                    
                                </div>
                              
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty( $col_two_display ) && $col_two_display == 1 ){ ?> 
                            <div id="col-product-area-one" class="col-product-area-one">
            
                                <div class="block-title">
                                    <?php if( !empty( $col_two_title ) ) { ?><h2><?php echo esc_html( $col_two_title ); ?></h2> <?php } ?>
                                </div>
                                
                                <div class="col-slider-items">
                                    <?php                         
                                        $query = new WP_Query($product_args_two);
                                        
                                        if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                    ?>
                                        <?php wc_get_template_part( 'content', 'product' ); ?>
                                        
                                    <?php } } wp_reset_query(); ?>                    
                                </div>
                              
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty( $col_three_display ) && $col_three_display == 1 ){ ?> 
                            <div id="col-product-area-one" class="col-product-area-one">
            
                                <div class="block-title">
                                    <?php if( !empty( $col_three_title ) ) { ?><h2><?php echo esc_html( $col_three_title ); ?></h2> <?php } ?>
                                </div>
                                
                                <div class="col-slider-items">
                                    <?php                         
                                        $query = new WP_Query($product_args_three);
                                        
                                        if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                    ?>
                                        <?php wc_get_template_part( 'content', 'product' ); ?>
                                        
                                    <?php } } wp_reset_query(); ?>                    
                                </div>
                              
                            </div>
                        <?php } ?>
                    
                    </div>
                </div>    
    
            </div><!-- End Product Slider --> 
    
        <?php         
            echo wp_kses_post($after_widget);
        }
       
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
            }
            return $instance;
        }
    
        public function form($instance) {
            $widget_fields = $this->widget_fields();
            foreach ($widget_fields as $widget_field) {
                extract($widget_field);
                $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
                storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
            }
        }
    }
    
}

/**
 * Adds storevilla_contact_info widget.
*/
add_action('widgets_init', 'storevilla_contact_info');
function storevilla_contact_info() {
    register_widget('storevilla_contact_info_area');
}
class storevilla_contact_info_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'storevilla_contact_info_area', __('SV: Quick Contact Info','storevilla'), array(
            'description' => __('A widget that shows quick contact information', 'storevilla')
        ));
    }
    
    private function widget_fields() {        
        
        $fields = array( 
            
            'storevilla_quick_contact_title' => array(
                'storevilla_widgets_name' => 'storevilla_quick_contact_title',
                'storevilla_widgets_title' => __('Title', 'storevilla'),
                'storevilla_widgets_field_type' => 'title',
            ),
            'storevilla_quick_address' => array(
                'storevilla_widgets_name' => 'storevilla_quick_address',
                'storevilla_widgets_title' => __('Contact Address', 'storevilla'),
                'storevilla_widgets_field_type' => 'textarea',
                'storevilla_widgets_row' => '3'
            ),
            'storevilla_quick_phone' => array(
                'storevilla_widgets_name' => 'storevilla_quick_phone',
                'storevilla_widgets_title' => __('Contact Number', 'storevilla'),
                'storevilla_widgets_field_type' => 'text',
            ),
            'storevilla_quick_email' => array(
                'storevilla_widgets_name' => 'storevilla_quick_email',
                'storevilla_widgets_title' => __('Contact Email Address', 'storevilla'),
                'storevilla_widgets_field_type' => 'text',
            )                   
        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        
        $title           = empty( $instance['storevilla_quick_contact_title'] ) ? '' : $instance['storevilla_quick_contact_title'];
        $contact_address = empty( $instance['storevilla_quick_address'] ) ? '' : $instance['storevilla_quick_address'];
        $contact_number  = empty( $instance['storevilla_quick_phone'] ) ? '' : $instance['storevilla_quick_phone'];
        $phone_num = preg_replace("/[^0-9]/","",$contact_number);
        $contact_email   = empty( $instance['storevilla_quick_email'] ) ? '' : $instance['storevilla_quick_email'];        
        
        echo wp_kses_post($before_widget); 

        if(!empty($title)) {
          echo '<h4>'.esc_html($title).'</h4>';
        }
    ?>
      <ul class="contacts-info">
        <?php if(!empty( $contact_address )) { ?>
          <li>
          <span><i class="fa fa-map-marker"></i></span> <p><?php echo esc_html( $contact_address ); ?></p>
          </li>
        <?php }  if(!empty( $contact_number )) { ?>
          <li class="phone-footer">
            <span><i class="fa fa-mobile"></i></span> 
            <p>
                <a href="tel:<?php echo esc_attr( $phone_num ); ?>">
                    <?php echo esc_html( $contact_number ); ?>
                </a>
            </p>
          </li>
        <?php }  if(!empty( $contact_email )) { ?>
          <li class="email-footer">
            <span><i class="fa fa-envelope"></i></span> <a href="mailto:<?php echo esc_html( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
          </li>
        <?php } ?>
      </ul>
    <?php         
        echo wp_kses_post($after_widget);
    }
   
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
        }
        return $instance;
    }

    public function form($instance) {
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? esc_attr($instance[$storevilla_widgets_name]) : '';
            storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
        }
    }
}


/**
 ** Adds storevilla_aboutus_info widget.
*/
add_action('widgets_init', 'storevilla_aboutus_info');
function storevilla_aboutus_info() {
    register_widget('storevilla_aboutus_info_area');
}

class storevilla_aboutus_info_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'storevilla_aboutus_info_area', 'SV: About Us Information', array(
            'description' => __('A widget that shows About Us information', 'storevilla')
        ));
    }
    
    private function widget_fields() {        
        
        $fields = array( 
            
            'storevilla_about_logo' => array(
                'storevilla_widgets_name' => 'storevilla_about_logo',
                'storevilla_widgets_title' => __('Upload Image', 'storevilla'),
                'storevilla_widgets_field_type' => 'upload',
            ),
            
            'storevilla_about_short_desc' => array(
                'storevilla_widgets_name' => 'storevilla_about_short_desc',
                'storevilla_widgets_title' => __('Short Description', 'storevilla'),
                'storevilla_widgets_field_type' => 'textarea',
                'storevilla_widgets_row' => '3'
            ),
            
            'storevilla_facebook_url' => array(
                'storevilla_widgets_name' => 'storevilla_facebook_url',
                'storevilla_widgets_title' => __('Facebook Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
            
            'storevilla_twitter_url' => array(
                'storevilla_widgets_name' => 'storevilla_twitter_url',
                'storevilla_widgets_title' => __('Twitter Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
            
            'storevilla_googleplus_url' => array(
                'storevilla_widgets_name' => 'storevilla_googleplus_url',
                'storevilla_widgets_title' => __('Google Plus Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
            
            'storevilla_youtube_url' => array(
                'storevilla_widgets_name' => 'storevilla_youtube_url',
                'storevilla_widgets_title' => __('Youtube Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
            
            'storevilla_linkedin_url' => array(
                'storevilla_widgets_name' => 'storevilla_linkedin_url',
                'storevilla_widgets_title' => __('Linkedin Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
            
            'storevilla_pinterest_url' => array(
                'storevilla_widgets_name' => 'storevilla_pinterest_url',
                'storevilla_widgets_title' => __('Pinterest Url', 'storevilla'),
                'storevilla_widgets_field_type' => 'url',
            ),
                            
        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        
        $logo         = $instance['storevilla_about_logo'];
        $shor_desc    = $instance['storevilla_about_short_desc'];
        $facebook     = $instance['storevilla_facebook_url'];
        $twitter      = $instance['storevilla_twitter_url'];
        $googleplus   = $instance['storevilla_googleplus_url'];
        $youtube      = $instance['storevilla_youtube_url'];
        $linkedin     = $instance['storevilla_linkedin_url'];
        $pinterest    = $instance['storevilla_pinterest_url'];                
       
        echo wp_kses_post($before_widget); 
    ?>
    <div class="store-container">
      <div class="about-info clearfix">
        <?php if(!empty( $logo )) { ?>
          <div class="about-logo">
              <img src="<?php echo esc_url( $logo ); ?>" alt="<?php the_title_attribute() ?>" />
          </div>
        <?php }  if(!empty( $shor_desc )) { ?>
          <div class="about-desc">
            <?php echo esc_textarea( $shor_desc ); ?>
          </div>
        <?php } ?>
        
          <ul>
                <?php if(!empty( $facebook )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $facebook ); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                  </li>
                <?php }  if(!empty( $twitter )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $twitter ); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                  </li>
                 <?php }  if(!empty( $googleplus )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $googleplus ); ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                  </li>
                 <?php }  if(!empty( $youtube )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $youtube ); ?>" target="_blank"><i class="fa fa-youtube"></i></a>
                  </li>
                 <?php }  if(!empty( $linkedin )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
                  </li>
                 <?php }  if(!empty( $pinterest )) { ?>
                  <li>
                      <a href="<?php echo esc_url( $pinterest ); ?>" target="_blank"><i class="fa fa-pinterest"></i></a>
                  </li>
                <?php } ?>
          </ul>
          
      </div>
    </div>
    <?php         
        echo wp_kses_post($after_widget);
    }
   
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
        }
        return $instance;
    }

    public function form($instance) {
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? esc_attr($instance[$storevilla_widgets_name]) : '';
            storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
        }
    }
}

/**
 * Adds storevilla_blog_widget widget.
*/
add_action('widgets_init', 'storevilla_blog_widget');
function storevilla_blog_widget() {
    register_widget('storevilla_blog_widget_area');
}
class storevilla_blog_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'storevilla_blog_widget_area', __('SV: Blogs Widget Section','storevilla'), array(
            'description' => __('A widget that shows blogs posts', 'storevilla')
        ));
    }
    
    private function widget_fields() {
        
        $args = array(
          'type'       => 'post',
          'child_of'   => 0,
          'orderby'    => 'name',
          'order'      => 'ASC',
          'hide_empty' => 1,
          'taxonomy'   => 'category',
        );
        $categories = get_categories( $args );
        $cat_lists = array();
        foreach( $categories as $category ) {
            $cat_lists[$category->term_id] = $category->name;
        }

        $fields = array( 
            
            'storevilla_blogs_title' => array(
                'storevilla_widgets_name' => 'storevilla_blogs_title',
                'storevilla_widgets_title' => __('Blogs Top Title', 'storevilla'),
                'storevilla_widgets_field_type' => 'title',
            ),
            
            'storevilla_blogs_top_title' => array(
                'storevilla_widgets_name' => 'storevilla_blogs_top_title',
                'storevilla_widgets_title' => __('Blogs Main Title', 'storevilla'),
                'storevilla_widgets_field_type' => 'title',
            ),
           
            'blogs_category_list' => array(
              'storevilla_widgets_name' => 'blogs_category_list',
              'storevilla_mulicheckbox_title' => __('Select Blogs Category', 'storevilla'),
              'storevilla_widgets_field_type' => 'multicheckboxes',
              'storevilla_widgets_field_options' => $cat_lists
            ),
            
            'blogs_posts_display_order' => array(
                'storevilla_widgets_name' => 'blogs_posts_display_order',
                'storevilla_widgets_title' => __('Display Posts Order', 'storevilla'),
                'storevilla_widgets_field_type' => 'select',
                'storevilla_widgets_field_options' => array(
                        'ASC' => 'Accessing Order', 
                        'DESC' => 'Deaccessing Order'
                    )
            )                      
        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        /**
        ** wp query for first block
        **/
        $blog_top_title            = empty( $instance['storevilla_blogs_top_title'] ) ? '' : $instance['storevilla_blogs_top_title'];
        $blog_main_title           = empty( $instance['storevilla_blogs_title'] ) ? '' : $instance['storevilla_blogs_title'];
        $blogs_category_list       = empty( $instance['blogs_category_list'] ) ? '' : $instance['blogs_category_list'];
        $blogs_posts_display_order = empty( $instance['blogs_posts_display_order'] ) ? '' : $instance['blogs_posts_display_order'];
    
        $blogs_cat_id = array();
        if(!empty($blogs_category_list)){
            $blogs_cat_id = array_keys($blogs_category_list);
        }

        $blogs_posts = new WP_Query( array(
            'posts_per_page'      => 5,
            'post_type'           => 'post',
            'cat'                 => $blogs_cat_id,
            'order'               => $blogs_posts_display_order,
            'ignore_sticky_posts' => 1
        ));

        $total_count = $blogs_posts->post_count;

        echo wp_kses_post($before_widget); 
    ?>
        <div class="storevilla-blog-wrap">

            <div class="store-container">

                <div class="blog-outer-container">
                   
                    <div class="block-title">
                        <?php if( !empty( $blog_top_title ) ) { ?><span><?php echo esc_html( $blog_top_title ); ?></span> <?php } ?>
                        <?php if( !empty( $blog_main_title ) ) { ?><h2><?php echo esc_html( $blog_main_title ); ?></h2> <?php } ?>
                    </div>
                    
                    <div class="blog-inner clearfix">
                        
                        <?php 
                            $count = 1;
                            if( $blogs_posts->have_posts() ) : while( $blogs_posts->have_posts() ) : $blogs_posts->the_post();
                            if($count <= 2 || $count == 4 || $count == 5){
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'storevilla-blog-grid', true);
                            }elseif($count == 3){
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large', true);
                            }
                        ?>
                            <?php 
                                if($count == 1 || $count == 4 ){
                                    echo '<div class="blog-preview">';
                                }
                                if($count == 3){
                                    echo '<div class="large-blog-preview">';
                                }
                            ?>
                            <div class="blog-preview-item">
                                
                                <?php if( has_post_thumbnail() ){ ?>
                                
                                    <div class="entry-thumb">
                                        <a href="<?php the_permalink(); ?>">
                                            <img alt="<?php the_title_attribute() ?>" title="<?php the_title( ); ?>" src="<?php echo esc_url( $image[0] ); ?>">
                                        </a>
                                     </div>
                                     
                                <?php } ?>
                               
                                
                                <div class="blog-preview-info">
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <?php if( $count == 3 ) { ?>
                                        <div class="blog-preview_desc">
                                            <?php echo esc_html(wp_trim_words( get_the_content(), 80)); ?>
                                        </div>
                                    <?php } ?>
                                    <a class="blog-preview-btn" href="<?php the_permalink(); ?>"><?php esc_html_e('READ MORE','storevilla'); ?></a>
                                </div>
                                
                            </div>
                            <?php 
                                if( $count == 2 || $count == 5 || $count == 3 || $count == $total_count ){
                                    echo '</div>';
                                }
                            ?>
                            
                        <?php $count++; endwhile; endif; wp_reset_query(); ?>

                    </div>
            
                </div>

            </div>

        </div><!-- End Latest Blog -->
    <?php         
        echo wp_kses_post($after_widget);
    }
   
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
        }
        return $instance;
    }

    public function form($instance) {
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
            storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
        }
    }
}

/**
 * Adds storevilla_testimonial_widget widget.
*/
add_action('widgets_init', 'storevilla_testimonial_widget');
function storevilla_testimonial_widget() {
    register_widget('storevilla_testimonial_widget_area');
}
class storevilla_testimonial_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'storevilla_testimonial_widget_area', __('SV: Testimonial Widget Section','storevilla'), array(
            'description' => __('A widget that shows client testimonial posts', 'storevilla')
        ));
    }
    
    private function widget_fields() {
        
        $args = array(
          'type'       => 'post',
          'child_of'   => 0,
          'orderby'    => 'name',
          'order'      => 'ASC',
          'hide_empty' => 1,
          'taxonomy'   => 'category',
        );
        $categories = get_categories( $args );
        $cat_lists = array();
        foreach( $categories as $category ) {
            $cat_lists[$category->term_id] = $category->name;
        }

        $fields = array( 
            
            'storevilla_testimonial_top_title' => array(
                'storevilla_widgets_name' => 'storevilla_testimonial_top_title',
                'storevilla_widgets_title' => __('Testimonial Top Title', 'storevilla'),
                'storevilla_widgets_field_type' => 'title',
            ),
            
            'storevilla_testimonial_main_title' => array(
                'storevilla_widgets_name' => 'storevilla_testimonial_main_title',
                'storevilla_widgets_title' => __('Testimonial Main Title', 'storevilla'),
                'storevilla_widgets_field_type' => 'title',
            ),
           
            'testimonial_category_list' => array(
              'storevilla_widgets_name' => 'testimonial_category_list',
              'storevilla_mulicheckbox_title' => __('Select Blogs Category', 'storevilla'),
              'storevilla_widgets_field_type' => 'multicheckboxes',
              'storevilla_widgets_field_options' => $cat_lists
            ),

            'storevilla_testimonial_bg_image' => array(
                'storevilla_widgets_name' => 'storevilla_testimonial_bg_image',
                'storevilla_widgets_title' => __('Upload Background Image', 'storevilla'),
                'storevilla_widgets_field_type' => 'upload',
            ),            
                                  
        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        /**
        ** wp query for first block
        **/
        $testimonial_top_title     = empty( $instance['storevilla_testimonial_top_title'] ) ? '' : $instance['storevilla_testimonial_top_title'];
        $testimonial_main_title    = empty( $instance['storevilla_testimonial_main_title'] ) ? '' : $instance['storevilla_testimonial_main_title'];
        $testimonial_category_list = empty( $instance['testimonial_category_list'] ) ? '' : $instance['testimonial_category_list'];
        $testimonial_bg_image      = empty( $instance['storevilla_testimonial_bg_image'] ) ? '' : $instance['storevilla_testimonial_bg_image'];
    
        $testimonial_cat_id = array();
        if(!empty($testimonial_category_list)){
            $testimonial_cat_id = array_keys($testimonial_category_list);
        }

        $blogs_posts = new WP_Query( array(
            'posts_per_page'      => 5,
            'post_type'           => 'post',
            'cat'                 => $testimonial_cat_id,
        ));

        echo wp_kses_post($before_widget); 
    ?>

        <div class="testimonial-outer-container" <?php if(!empty( $testimonial_bg_image )) { ?> style="background-image:url(<?php echo esc_url( $testimonial_bg_image ); ?>); background-size:cover;"<?php } ?>>
            
            <div class="store-container">
                
                <div class="block-title">
                    <?php if( !empty( $testimonial_top_title ) ) { ?><span><?php echo esc_html( $testimonial_top_title ); ?></span> <?php } ?>
                    <?php if( !empty( $testimonial_main_title ) ) { ?><h2><?php echo esc_html( $testimonial_main_title ); ?></h2> <?php } ?>
                </div>
                
                <ul id="testimonial-area" class="testimonial-area cS-hidden">
                    
                    <?php if( $blogs_posts->have_posts() ) : while( $blogs_posts->have_posts() ) : $blogs_posts->the_post(); ?>
                        
                        <div class="testimonial-preview-item <?php echo esc_attr( $class ); ?>">
                            
                            <?php 
                            	if( has_post_thumbnail() ){ 
                            	$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail', true);

                            ?>                            
                                <div class="entry-thumb">
                                    <img alt="<?php the_title_attribute() ?>" title="<?php the_title( ); ?>" src="<?php echo esc_url( $image[0] ); ?>">
                                </div>                                 
                            <?php } ?>
                           
                            
                            <div class="testimonial-preview-info">
                                <div class="testimonial-preview_desc">
                                    <?php the_excerpt() ?>
                                </div>
                                <h2><?php the_title(); ?></h2>
                            </div>
                            
                        </div>
                        
                    <?php endwhile; endif; wp_reset_query(); ?>

                </ul>
        
            </div>

        </div><!-- End Latest Blog -->

    <?php         
        echo wp_kses_post($after_widget);
    }
   
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $instance[$storevilla_widgets_name] = storevilla_widgets_updated_field_value($widget_field, $new_instance[$storevilla_widgets_name]);
        }
        return $instance;
    }

    public function form($instance) {
        $widget_fields = $this->widget_fields();
        foreach ($widget_fields as $widget_field) {
            extract($widget_field);
            $storevilla_widgets_field_value = !empty($instance[$storevilla_widgets_name]) ? $instance[$storevilla_widgets_name] : '';
            storevilla_widgets_show_widget_field($this, $widget_field, $storevilla_widgets_field_value);
        }
    }
}

/**
 * StoreVilla Field Functional file
 * @package Store_Villa
*/
function storevilla_widgets_show_widget_field($instance = '', $widget_field = '', $storevilla_field_value = '') {
   
    //list category list in array
    $storevilla_category_list[0] = array(
        'value' => 0,
        'label' => 'Select Categories'
    );
    $storevilla_posts = get_categories();
    foreach ($storevilla_posts as $storevilla_post) :
        $storevilla_category_list[$storevilla_post->term_id] = array(
            'value' => $storevilla_post->term_id,
            'label' => $storevilla_post->name
        );
    endforeach;
    
    
    // Store Posts in array
    $storevilla_pagelist[0] = array(
        'value' => 0,
        'label' => 'Select Pages'
    );
    $arg = array('posts_per_page' => -1);
    $storevilla_pages = get_pages($arg);
    foreach ($storevilla_pages as $storevilla_page) :
        $storevilla_pagelist[$storevilla_page->ID] = array(
            'value' => $storevilla_page->ID,
            'label' => $storevilla_page->post_title
        );
    endforeach;

    extract($widget_field);

    switch ($storevilla_widgets_field_type) {

        // Standard text field
        case 'text' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="text" value="<?php echo esc_attr($storevilla_field_value) ; ?>" />

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //title
        case 'title' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="text" value="<?php echo esc_attr($storevilla_field_value) ; ?>" />
                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'group_start' :
            ?>
            <div class="storevilla-main-group" id="ap-font-awesome-list <?php echo esc_attr($instance->get_field_id(($storevilla_widgets_name))); ?>">
                <div class="storevilla-main-group-heading" style="font-size: 15px;  font-weight: bold;  padding-top: 12px;"><?php echo esc_html($storevilla_widgets_title); ?><span class="toogle-arrow"></span></div>
                <div class="storevilla-main-group-wrap">

            <?php
            break;

            case 'group_end':
            ?></div>
            </div><?php
            break;

        // Standard url field
        case 'url' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <input class="widefat" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="text" value="<?php echo esc_url($storevilla_field_value); ?>" />

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Textarea field
        case 'textarea' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <textarea class="widefat" rows="<?php echo esc_attr($storevilla_widgets_row); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>"><?php echo esc_textarea($storevilla_field_value); ?></textarea>
            </p>
            <?php
            break;

        // Checkbox field
        case 'checkbox' :
            ?>
            <p>
                <input id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="checkbox" value="1" <?php checked('1', $storevilla_field_value); ?>/>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?></label>

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Radio fields
        case 'radio' :
            ?>
            <p>
                <?php
                echo esc_html($storevilla_widgets_title);
                echo '<br />';
                foreach ($storevilla_widgets_field_options as $storevilla_option_name => $storevilla_option_title) {
                    ?>
                    <input id="<?php echo esc_attr($instance->get_field_id($storevilla_option_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="radio" value="<?php echo esc_attr($storevilla_option_name); ?>" <?php checked($storevilla_option_name, $storevilla_field_value); ?> />
                    <label for="<?php echo esc_attr($instance->get_field_id($storevilla_option_name)); ?>"><?php echo esc_html($storevilla_option_title); ?></label>
                    <br />
                <?php } ?>

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select field
        case 'select' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <select name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" class="widefat">
                    <?php foreach ($storevilla_widgets_field_options as $storevilla_option_name => $storevilla_option_title) { ?>
                        <option value="<?php echo esc_attr($storevilla_option_name); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_option_name)); ?>" <?php selected($storevilla_option_name, $storevilla_field_value); ?>><?php echo esc_html($storevilla_option_title); ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;
        
        // Select pages fields
        case 'selectpage' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?>:</label>
                <select name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" class="widefat">
                    <?php foreach ($storevilla_pagelist as $storevilla_page) { ?>
                        <option value="<?php echo esc_attr($storevilla_page['value']); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_page['label'])); ?>" <?php selected($storevilla_page['value'], $storevilla_field_value); ?>><?php echo esc_html($storevilla_page['label']); ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'number' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label><br />
                <input name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" type="number" step="1" min="1" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" value="<?php echo esc_attr($storevilla_field_value); ?>" class="widefat" />

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;        

        // Select category field
        case 'select_category' :
            ?>
            <p>
                <label for="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>"><?php echo esc_html($storevilla_widgets_title); ?> :</label>
                <select name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" class="widefat">
                    <?php foreach ($storevilla_category_list as $storevilla_single_post) { ?>
                        <option value="<?php echo esc_attr($storevilla_single_post['value']); ?>" id="<?php echo esc_attr($instance->get_field_id($storevilla_single_post['label'])); ?>" <?php selected($storevilla_single_post['value'], $storevilla_field_value); ?>><?php echo esc_html($storevilla_single_post['label']); ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($storevilla_widgets_description)) { ?>
                    <br />
                    <small><?php echo esc_html($storevilla_widgets_description); ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //Multi checkboxes
        case 'multicheckboxes' :
            
            if( isset( $storevilla_mulicheckbox_title ) ) { ?>
                <label><?php echo esc_html( $storevilla_mulicheckbox_title ); ?>:</label>
            <?php }
            echo '<div class="storevilla-multiplecat">';
                foreach ( $storevilla_widgets_field_options as $storevilla_option_name => $storevilla_option_title) {
                    if( isset( $storevilla_field_value[$storevilla_option_name] ) ) {
                        $storevilla_field_value[$storevilla_option_name] = 1;
                    }else{
                        $storevilla_field_value[$storevilla_option_name] = 0;
                    }                
                ?>
                    <p>
                        <input id="<?php echo esc_attr($instance->get_field_id($storevilla_widgets_name)); ?>" name="<?php echo esc_attr($instance->get_field_name($storevilla_widgets_name)).'['.esc_attr($storevilla_option_name).']'; ?>" type="checkbox" value="1" <?php checked('1', $storevilla_field_value[$storevilla_option_name]); ?>/>
                        <label for="<?php echo esc_attr($instance->get_field_id($storevilla_option_name)); ?>"><?php echo esc_html($storevilla_option_title); ?></label>
                    </p>
                <?php
                    }
            echo '</div>';
                if (isset($storevilla_widgets_description)) {
            ?>
                    <small><em><?php echo esc_html($storevilla_widgets_description); ?></em></small>
            <?php
                }
            
        break;

        case 'upload' :

            $output = '';
            $id = $instance->get_field_id($storevilla_widgets_name);
            $class = '';
            $int = '';
            $value = $storevilla_field_value;
            $name = $instance->get_field_name($storevilla_widgets_name);

            if ($value) {
                $class = ' has-file';
            }
            ?>
            <div class="sub-option section widget-upload">
            <label for="'.$instance->get_field_id($storevilla_widgets_name).'"><?php echo esc_html($storevilla_widgets_title); ?></label><br/>
            <input id="<?php echo esc_attr($id);; ?>" class="upload<?php echo esc_attr($class); ?>" type="text" name="<?php echo esc_attr($name); ?>" value="<?php esc_url($value); ?>" placeholder="<?php esc_attr_e('No file chosen', 'storevilla'); ?>" />
            <?php
            if (function_exists('wp_enqueue_media')) {
                if (( $value == '')) {
                    ?>
                    <input id="upload-<?php echo esc_attr($id); ?>" class="upload-button-wdgt button" type="button" value="<?php esc_attr_e('Upload', 'storevilla'); ?>" />
                    <?php
                } else {
                    ?>
                    <input id="remove-<?php echo esc_attr($id); ?>" class="remove-file button" type="button" value="<?php esc_attr_e('Remove', 'storevilla'); ?>" />
                    <?php
                }
            } else {
                ?>
                <p><i><?php esc_html_e('Upgrade your version of WordPress for full media support.', 'storevilla'); ?></i></p>
                <?php
            }
            ?>
            <div class="screenshot team-thumb" id="<?php echo esc_attr($id); ?>-image">
            <?php
            if ($value != '') {
                $image = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value);
                if ($image) {
                    ?>
                    <img src="' . $value . '" alt="<?php the_title_attribute() ?>" /><a class="remove-image"><?php esc_html_e( 'Remove', 'storevilla' ); ?></a>
                    <?php
                } else {
                    $parts = explode("/", $value);
                    for ($i = 0; $i < sizeof($parts); ++$i) {
                        $title = $parts[$i];
                    }
                    ?><div class="no-image"><span class="file_link"><a href="<?php esc_url($value); ?>" target="_blank" rel="external"><?php esc_html_e('View File', 'storevilla'); ?></a></span></div>
                    <?php
                }
            }
            ?>
            </div></div>
            <?php
            break;
    }
}

function storevilla_widgets_updated_field_value($widget_field, $new_field_value) {

    extract($widget_field);

    if ($storevilla_widgets_field_type == 'number') {

        return absint($new_field_value);

    } elseif ($storevilla_widgets_field_type == 'textarea') {
        
        if (!isset($storevilla_widgets_allowed_tags)) {
            $storevilla_widgets_allowed_tags = '<p><strong><em><a>';
        }

        return strip_tags($new_field_value, $storevilla_widgets_allowed_tags);
    } 
    elseif ($storevilla_widgets_field_type == 'url') {
        return esc_url_raw($new_field_value);
    }
    elseif ($storevilla_widgets_field_type == 'title') {
        return wp_kses_post($new_field_value);
    }
    elseif ($storevilla_widgets_field_type == 'multicheckboxes') {
        return ($new_field_value);
    }
    else {
        return strip_tags($new_field_value);
    }
}


/**
** Enqueue scripts for file uploader
**/
if ( ! function_exists( 'storevilla_media_scripts' ) ) {
    function storevilla_media_scripts($hook) {
        
        if( 'widgets.php' != $hook )
        return;

        if (function_exists('wp_enqueue_media'))
          wp_enqueue_media();
          wp_enqueue_script('storevilla-media-uploader', get_template_directory_uri() . '/assets/js/storevilla-init-admin.js', array( 'jquery', 'customize-controls' ), 1.0);
          wp_localize_script('storevilla-media-uploader', 'storevilla_l10n', array(
              'upload' => __('Upload', 'storevilla'),
              'remove' => __('Remove', 'storevilla')
          ));
        wp_enqueue_style( 'storevilla-style-admin', get_template_directory_uri() . '/assets/css/storevilla-admin.css');
        wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/fontawesome/css/font-awesome.min.css');
    }
}
add_action('admin_enqueue_scripts', 'storevilla_media_scripts');