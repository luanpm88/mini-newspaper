<?php

class tds_eastcoastcheck_module03 extends td_module {

    function __construct( $post_data_array, $module_atts = array() ) {
        //run the parrent constructor
        parent::__construct( $post_data_array, $module_atts );
    }

    function render( $shortcode_class = '', $style_class = '' ) {
        ob_start();
        $image_size = $this->get_shortcode_att('image_size', '', $style_class);

        if (empty($image_size)) {
            $image_size = 'td_696x0';
        }


        $additional_classes_array = array('tdb_module_loop');
        $additional_classes_array = apply_filters( 'td_composer_module_exclusive_class', $additional_classes_array, $this->post );
        $title_length = $this->get_shortcode_att('mc1_tl', '', $style_class);
        $title_tag = $this->get_shortcode_att('mc1_title_tag', '', $style_class);
//        $excerpt_length = $this->get_shortcode_att('mc1_el', '', $style_class);



        ?>
        <style>

        </style>
        <div class="<?php echo $this->get_module_classes($additional_classes_array);?>">
            <div class="td-module-container">
                <div class="td-ecoast3-module-cont">
                    <div class="td-ecoast3-image-cont td-image-container">
                        <?php echo $this->get_image($image_size, true);?>
                    </div>
                    <div class="td-ecoast3-meta-info-cont">
                        <div class="td-ecoast3-taxonomy">
                            <?php
                                $terms = get_the_terms( $this->post->ID, 'tdtax_services');
                                if ( function_exists('get_field') ) {

                                    if (false !== $terms && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            echo '<a href="' . get_term_link($term->term_id) . '">' . get_field('tdcf_taxonomy_icon', 'tdtax_services_' . $term->term_id) . '</a>';
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <div class="td-ecoast3-meta-info">
                            <div class="td-eastcoast03-title"><?php echo $this->get_title($title_length, $title_tag); ?></div>
                            <div class="td-eastcoast03-tax-location">
                                <?php
                                    $terms = get_the_terms( $this->post->ID, 'tdtax_locations');
                                    if ( false !== $terms && !is_wp_error($terms) ) {
                                        foreach ($terms as $term) {
                                            echo '<a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php return ob_get_clean();
    }
}
