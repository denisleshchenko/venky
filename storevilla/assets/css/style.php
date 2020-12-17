<?php
	function storevilla_dynamic_styles() {
		$tpl_color = get_theme_mod( 'storevilla_tpl_color', '#0091D5' );

		$custom_css = '';

        /** Background **/
		$custom_css .= "
            .main-navigation,
            .header-wrap .search-cart-wrap .advance-search .sv_search_form #searchsubmit,
            .top-header-regin ul li span.cart-count,
            .main-navigation ul ul,
            .widget_storevilla_cat_widget_area .category-slider li .item-img a:hover .sv_category_count,
            .product-button-wrap a.button,
            .product-button-wrap a.added_to_cart,
            .widget_storevilla_promo_pages_area .promo-area a:hover button,
            .widget_storevilla_blog_widget_area .blog-preview a.blog-preview-btn:hover,
            .widget_storevilla_blog_widget_area .large-blog-preview a.blog-preview-btn:hover,
            .lSSlideOuter .lSPager.lSpg > li:hover a,
            .lSSlideOuter .lSPager.lSpg > li.active a,
            .widget_storevilla_contact_info_area ul li span:hover,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .yith-woocompare-widget a.clear-all:hover,
            .yith-woocompare-widget .compare.button,
            .widget_search form input[type=submit],
            .widget_product_search form input[type=submit],
            .no-results.not-found .search-form .search-submit,
            .woocommerce nav.woocommerce-pagination ul li a:focus,
            .woocommerce nav.woocommerce-pagination ul li a:hover,
            .woocommerce nav.woocommerce-pagination ul li span.current,
            .item-img .new-label.new-top-left,
            .widget_storevilla_cat_with_product_widget_area .block-title-desc a.view-bnt:hover,
            .site-header-cart .woocommerce a.button.wc-forward,
            .woocommerce #respond input#submit,
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button,
            .header-wrap .search-cart-wrap .advance-search .sv_search_form #searchsubmit:hover,
            .normal-search .search-form .search-submit:hover,
            .quantity button,
            .woocommerce #respond input#submit.alt.disabled,
            .woocommerce #respond input#submit.alt.disabled:hover,
            .woocommerce #respond input#submit.alt:disabled,
            .woocommerce #respond input#submit.alt:disabled:hover,
            .woocommerce #respond input#submit.alt[disabled]:disabled,
            .woocommerce #respond input#submit.alt[disabled]:disabled:hover,
            .woocommerce a.button.alt.disabled,
            .woocommerce a.button.alt.disabled:hover,
            .woocommerce a.button.alt:disabled,
            .woocommerce a.button.alt:disabled:hover,
            .woocommerce a.button.alt[disabled]:disabled,
            .woocommerce a.button.alt[disabled]:disabled:hover,
            .woocommerce button.button.alt.disabled,
            .woocommerce button.button.alt.disabled:hover,
            .woocommerce button.button.alt:disabled,
            .woocommerce button.button.alt:disabled:hover,
            .woocommerce button.button.alt[disabled]:disabled,
            .woocommerce button.button.alt[disabled]:disabled:hover,
            .woocommerce input.button.alt.disabled,
            .woocommerce input.button.alt.disabled:hover,
            .woocommerce input.button.alt:disabled,
            .woocommerce input.button.alt:disabled:hover,
            .woocommerce input.button.alt[disabled]:disabled,
            .woocommerce input.button.alt[disabled]:disabled:hover,
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
            .woocommerce div.product .woocommerce-tabs ul.tabs li:hover,
            .woocommerce #respond input#submit.alt,
            .woocommerce a.button.alt,
            .woocommerce button.button.alt,
            .woocommerce input.button.alt,
            .main-navigation ul ul li.current-menu-item a,
            .main-navigation ul ul li a:hover,
            .main-navigation ul ul li.current-menu-item a,
            .main-navigation ul ul li a:hover,
            .woocommerce-MyAccount-navigation ul li.is-active a,
            .woocommerce-MyAccount-navigation ul li:hover a,
            .woocommerce #respond input#submit.alt:hover,
            .woocommerce a.button.alt:hover,
            .woocommerce button.button.alt:hover,
            .woocommerce input.button.alt:hover,
            .woocommerce-cart table.cart .quantity button:hover,
            .comments-area .form-submit .submit,
            .error-homepage a,
            .error-homepage a:hover,
            .pagination .nav-links span.current,
            .pagination .nav-links a:hover{
                background: {$tpl_color};                
            }";
            
        /** Color **/
        $custom_css .= "
            .main-navigation ul li ul li:hover a,
            .top-header .top-header-regin ul li a:hover,
            .top-header-regin .count .fa.fa-shopping-basket:hover,
            .top-header ul.store-quickinfo li a:hover,
            ul.add-to-links li a:hover,
            .main-widget-wrap .block-title span,
            .block-title span,
            .widget_storevilla_column_product_widget_area .column-wrap .col-wrap .block-title h2,
            .widget_nav_menu ul li a:hover,
            .widget_pages ul li a:hover,
            .widget_recent_entries ul li a:hover,
            .widget_meta ul li a:hover,
            .widget_archive ul li a:hover,
            .widget_categories ul li a:hover,
            a.scrollup:hover,
            h3.widget-title, h3.comment-reply-title,
            .comments-area h2.comments-title,
            .woocommerce .star-rating span::before,
            .woocommerce a.remove,
            .woocommerce nav.woocommerce-pagination ul li a,
            .woocommerce nav.woocommerce-pagination ul li span,
            .woocommerce-tabs.wc-tabs-wrapper h2,
            .woocommerce .woocommerce-tabs p.stars a,
            .single-product .compare.button:hover,
            .single-product .entry-summary .yith-wcwl-add-to-wishlist a:hover,
            td a,
            .woocommerce-MyAccount-navigation ul li a,
            .woocommerce-MyAccount-content a,
            .woocommerce a.remove,
            table.wishlist_table .product-name a:hover,
            table.wishlist_table .product-name a.button:hover,
            .storevilla-blog h3 a:hover,
            .storevilla-blog .sv-post-content .category-name span a:hover,
            .storevilla-blog .sv-post-foot a:hover,
            .storevilla-blog .sv-post-content a.sv-btn-countinuereading:hover,
            .blog-meta li a:hover,
            .post-navigation .nav-links .nav-previous a:hover,
            .post-navigation .nav-links .nav-next a:hover,
            .widget_recent_comments ul li .comment-author-link a,
            .top-header .top-navigation ul li a:hover{
               color: {$tpl_color}; 
            }";
            
        /** Border Color **/
        $custom_css .= "
            .product-button-wrap a.button,
            .product-button-wrap a.added_to_cart,
            .widget_storevilla_promo_pages_area .promo-area a:hover button,
            .widget_storevilla_blog_widget_area .blog-preview a.blog-preview-btn:hover,
            .widget_storevilla_blog_widget_area .large-blog-preview a.blog-preview-btn:hover,
            .widget_storevilla_contact_info_area ul li span:hover,
            .yith-woocompare-widget a.clear-all:hover,
            .yith-woocompare-widget .compare.button,
            .woocommerce nav.woocommerce-pagination ul,
            .woocommerce nav.woocommerce-pagination ul li,
            .widget_storevilla_cat_with_product_widget_area .block-title-desc a.view-bnt:hover,
            .product-button-wrap a,
            .woocommerce #respond input#submit,
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button,
            .woocommerce div.product form.cart div.quantity,
            .woocommerce-MyAccount-navigation ul li a,
            .woocommerce-MyAccount-content,
            .woocommerce .woocommerce-message,
            .pagination .nav-links span.current,
            .pagination .nav-links a:hover{
                border-color: {$tpl_color};
            }";
            
        /** Border Top Color **/
        $custom_css .= "
            .woocommerce div.product .woocommerce-tabs .panel.entry-content.wc-tab{
                border-top-color: {$tpl_color}; 
            }";
            
        /** Transparent Border Color **/
        $custom_css .= "
            .item-img .new-label.new-top-left:before{
                border-color: transparent transparent transparent {$tpl_color}; 
            }";

        /** Media Queries **/
        $custom_css .= "
            @media (max-width: 768px){
                  .main-navigation ul li a{
                        color: {$tpl_color} !important;
                  }
            }";

		wp_add_inline_style( 'storevilla-style', $custom_css );

	}
	add_action( 'wp_enqueue_scripts', 'storevilla_dynamic_styles' );