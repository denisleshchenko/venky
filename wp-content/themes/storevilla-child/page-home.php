<?php get_header(); ?>
<div class="banner">
    <div class="container">
        <div class="row banner-phone">            
            <div class="col-sm-6 left"><a href="tel:+74732463217">8(473)246-32-17</a></div>
            <div class="col-sm-6 right"><a href="tel:+79913267422">8(991)326-74-22</a></div>
        </div>
        <div class="row banner-back">            
            <div class="col-lg-12">
                <img src="<?= get_stylesheet_directory_uri() ?>/img/angel.png" alt="">
                <div class="row banner-ul">
                    <div class="col-md-6 col-lg-5 order-sm-first order-md-first order-lg-first order-last">
                        <?php
                        echo get_categories_product();
                        ?>
                    </div>
                    <div class="banner-ul-p d-md-block d-lg-block col-md-6 col-lg-7 order-sm-last order-md-last order-lg-last order-first">
                        <p>
                            <b>Оптовая</b> и <b>розничная</b> торговля. <b>Доставка</b> по области и городу <b>своим транспортом</b>.
                        </p>
                    </div>
                </div>
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

        <div class="bck-bakend"><span class="bakend"></span></div>

        <h2 class="main-h2">Если у вас есть вопросы, просто заполните форму</h2>
        <span class="main-h2-span">наш менеджер вам перезвонит</span>
        <div class="home-form">
            <?php echo do_shortcode('[contact-form-7 id="18" title="Контактная форма на главной"]'); ?>
        </div>
    </main><!-- #main -->

</div><!-- #primary -->

<?php
get_footer();
