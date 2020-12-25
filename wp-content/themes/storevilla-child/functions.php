<?php
add_action('wp_enqueue_scripts', 'my_child_theme_scripts');

function my_child_theme_scripts() {
    wp_enqueue_style('bootstrap-style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('fonts-style-child', get_stylesheet_directory_uri() . '/css/main-fonts.css');
    wp_enqueue_script('myscript', get_stylesheet_directory_uri() . '/js/myscript.js', array(), esc_attr($theme_version), true);
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

add_action('storevilla_footer', 'storevilla_credit', 20);

function storevilla_credit() {
    
}

;
add_action('storevilla_footer', 'storevilla_payment_logo', 40);

function storevilla_payment_logo() {
    
}

;
/* breadcrumbs */

function wpcourses_breadcrumb($sep = ' > ') {
    global $post;
    $out = '';
    $out .= '<nav class="wpcourses-breadcrumbs">';
    $out .= '<a href="' . home_url('/') . '">Главная</a>';
    $out .= '<span class="wpcourses-breadcrumbs-sep">' . $sep . '</span>';
    if (is_single()) {
        $terms = get_the_terms($post, 'category');
        if (is_array($terms) && $terms !== array()) {
            $out .= '<a href="' . get_term_link($terms[0]) . '">' . $terms[0]->name . '</a>';
            $out .= '<span class="wpcourses-breadcrumbs-sep">' . $sep . '</span>';
        }
    }
    if (is_singular()) {
        $out .= '<span class="wpcourses-breadcrumbs-last">' . get_the_title() . '</span>';
    }
    if (is_search()) {
        $out .= get_search_query();
    }
    $out .= '</nav><!--.wpcourses-breadcrumbs-->';
    return $out;
}

function woocommerce_get_product_thumbnail($size = 'woocommerce_single', $deprecated1 = 0, $deprecated2 = 0) {
    global $product;

    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);

    return $product ? $product->get_image($image_size) : '';
}

function woocommerce_template_loop_category_title($category) {
    ?>
    <h2 class="woocommerce-loop-category__title">
        <?php
        echo esc_html($category->name);

        if ($category->count > 0) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo apply_filters('woocommerce_subcategory_count_html', '', $category);
        }
        ?>
    </h2>
    <?php
}

add_action('after_setup_theme', 'yourtheme_setup');

function yourtheme_setup() {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}


require (get_stylesheet_directory() . '/inc/template-woocommerce-fun.php');
require (get_stylesheet_directory() . '/inc/template-tags-child.php');
