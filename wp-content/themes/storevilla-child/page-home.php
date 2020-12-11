<?php get_header(); ?>
<div class="banner">
    <div class="container">
        <div class="row banner-phone">            
            <div class="col-lg-6 left">8(473)246-32-17</div>
             <div class="col-lg-6 right">8(991)326-74-22</div>
        </div>
        <div class="row banner-back">            
            <div class="col-lg-12">
                <img src="<?=get_stylesheet_directory_uri()?>/img/angel.png" alt="">
            </div>
                           
        </div>
    </div>
</div>
<div id="primary" class="container">
    <main id="main" class="site-main" role="main">

        <div class="home-content">
            <?php
            the_content();

            wp_link_pages();
            ?>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
