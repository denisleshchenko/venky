<?php
function storevilla_footer_widgets() {
    if (is_active_sidebar('storevillafooter-5')) {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 5);
    } elseif (is_active_sidebar('storevillafooter-4')) {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 4);
    } elseif (is_active_sidebar('storevillafooter-3')) {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 3);
    } elseif (is_active_sidebar('storevillafooter-2')) {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 2);
    } elseif (is_active_sidebar('storevillafooter-1')) {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 1);
    } else {
        $widget_columns = apply_filters('storevilla_footer_widget_regions', 0);
    }
    if ($widget_columns > 0) :
        ?>
        <?php $val = (100 / $widget_columns) / 8.3; ?>
        <section class="footer-widgets  clearfix">

            <div class="top-footer-wrap container">

                <div class="store-container row">

                    <?php $i = 0;
                    while ($i < $widget_columns) : $i++; ?>

                            <?php if (is_active_sidebar('storevillafooter-' . $i)) : ?>

                            <section class="col-md-<?php echo intval($val); ?> block footer-wid-<?php echo intval($i); ?>">
                            <?php dynamic_sidebar('storevillafooter-' . intval($i)); ?>
                            </section>

                        <?php endif; ?>

                    <?php endwhile;

                    if (is_active_sidebar('storevillaquickinfo')) {
                        ?>		
                        <div class="footer-quick-info" role="complementary">				
            <?php dynamic_sidebar('storevillaquickinfo'); ?>				
                        </div>			
        <?php } ?>

                </div>

            </div>

        </section><!-- .footer-widgets  -->

    <?php
    endif;
}

 ?>