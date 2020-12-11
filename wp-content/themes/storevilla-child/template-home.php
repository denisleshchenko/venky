<?php
/**
 * Template Name: Front Page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

get_header(); ?>

  <?php do_action( 'storevilla_before_homepage' ); ?>

      <?php
          /**
           * @hooked storevilla_main_slider - 10
           * @hooked storevilla_main_widget - 20
           * @hooked storevilla_brand_logo - 30
           */
          do_action( 'storevilla_homepage' ); 
      ?>

  <?php do_action( 'storevilla_after_homepage' ); ?>


<?php get_footer();
