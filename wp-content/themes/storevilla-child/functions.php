<?php

add_action('wp_enqueue_scripts', 'my_child_theme_scripts');

function my_child_theme_scripts() {
    wp_enqueue_style('bootstrap-style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('fonts-style-child', get_stylesheet_directory_uri() . '/css/main-fonts.css');
    wp_enqueue_script('myscript', get_stylesheet_directory_uri() . '/js/myscript.js', array(), esc_attr( $theme_version ), true);
}

function get_categories_product($categories_list = "") {
    $get_categories_product = get_terms("product_cat", [
        "orderby" => "id", // Тип сортировки
        "order" => "ASC", // Направление сортировки
        "hide_empty" => 0, // Скрывать пустые. 1 - да, 0 - нет.
    ]);

    if (count($get_categories_product) > 0) {

        $categories_list = '<ul class="banner_categories_list">';

        foreach ($get_categories_product as $categories_item) {

            $categories_list .= '<li><a href="' . esc_url(get_term_link((int) $categories_item->term_id)) . '">' . esc_html($categories_item->name) . '</a></li>';
        }

        $categories_list .= '</ul>';
    }
    return $categories_list;
}
add_action( 'storevilla_footer', 'storevilla_credit', 20 );
 function storevilla_credit() {	
};
add_action( 'storevilla_footer', 'storevilla_payment_logo', 40 );
function storevilla_payment_logo() {	
};
/* breadcrumbs */
function wpcourses_breadcrumb( $sep = ' > ' ) {
	global $post;
	$out = '';
	$out .= '<nav class="wpcourses-breadcrumbs">';
	$out .= '<a href="' . home_url( '/' ) . '">Главная</a>';
	$out .= '<span class="wpcourses-breadcrumbs-sep">' . $sep . '</span>';
	if ( is_single() ) {
		$terms = get_the_terms( $post, 'category' );
		if ( is_array( $terms ) && $terms !== array() ) {
			$out .= '<a href="' . get_term_link( $terms[0] ) . '">' . $terms[0]->name . '</a>';
			$out .= '<span class="wpcourses-breadcrumbs-sep">' . $sep . '</span>';
		}
	}
	if ( is_singular() ) {
		$out .= '<span class="wpcourses-breadcrumbs-last">' . get_the_title() . '</span>';
	}
	if ( is_search() ) {
		$out .= get_search_query();
	}
	$out .= '</nav><!--.wpcourses-breadcrumbs-->';
	return $out;
}

require (get_stylesheet_directory() .'/inc/template-tags-child.php');