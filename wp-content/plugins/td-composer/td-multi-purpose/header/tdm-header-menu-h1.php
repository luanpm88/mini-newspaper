<div class="tdm-menu-btns-socials">
    <?php
    //check to see if we show the search form default = '' - main menu
    if(td_util::get_option('tds_search_placement') == '') { ?>
        <div class="header-search-wrap">
            <div class="td-search-btns-wrap">
                <a id="td-header-search-button" href="#" aria-label="Search" role="button" class="dropdown-toggle " data-toggle="dropdown"><i class="td-icon-search"></i></a>
                <?php if ( td_util::get_option('tds_hide_mobile_search') != 'hide' ) { ?>
                    <a id="td-header-search-button-mob" href="#" role="button" aria-label="Search" class="dropdown-toggle " data-toggle="dropdown"><i class="td-icon-search"></i></a>
                <?php } ?>
            </div>

            <div class="td-drop-down-search">
                <form method="get" class="td-search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
                    <div role="search" class="td-head-form-search-wrap">
                        <input id="td-header-search" type="text" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" /><input class="wpb_button wpb_btn-inverse btn" type="submit" id="td-header-search-top" value="<?php _etd('Search', TD_THEME_NAME)?>" />
                    </div>
                </form>
                <div id="td-aj-search"></div>
            </div>
        </div>
    <?php } else { ?>
        <div class="td-search-wrapper">
            <div id="td-top-search">
                <!-- Search -->
                <div class="header-search-wrap">
                    <div class="dropdown header-search">
                        <a id="td-header-search-button-mob" href="#" role="button" aria-label="Search" class="dropdown-toggle " data-toggle="dropdown"><i class="td-icon-search"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
    //check to see if we show the socials from our theme or from wordpress
    if(td_util::get_option('td_social_networks_menu_show') == 'show') {
        echo '<div class="td-header-menu-social">';
        //get the socials that are set by user
        $td_get_social_network = td_options::get_array('td_social_networks');

        if (!empty($td_get_social_network)) {
            foreach ($td_get_social_network as $social_id => $social_link) {
                if (!empty($social_link)) {
                    echo td_social_icons::get_icon($social_link, $social_id, true);
                }
            }
        }
        echo '</div>';
    }
    ?>

    <?php if ( td_util::get_option('tdm_menu_btn1_text') !== '' || td_util::get_option('tdm_menu_btn2_text') !== '' ) { ?>
        <div class="tdm-header-menu-btns">
            <?php
            $btn1_text = td_util::get_option('tdm_menu_btn1_text');
            $btn1_style = td_util::get_option('tdm_menu_btn1_style');
            $btn1_url = td_util::get_option('tdm_menu_btn1_url');
            $btn1_new_window = td_util::get_option('tdm_menu_btn1_open_in_new_window');
            $btn2_text = td_util::get_option('tdm_menu_btn2_text');
            $btn2_style = td_util::get_option('tdm_menu_btn2_style');
            $btn2_url = td_util::get_option('tdm_menu_btn2_url');
            $btn2_new_window = td_util::get_option('tdm_menu_btn2_open_in_new_window');

            if ( $btn1_text !== '' ) {
                echo do_shortcode('[tdm_block_button button_text="' . $btn1_text . '" button_size="tdm-btn-md" button_display="tdm-block-button-inline" button_url="' . $btn1_url . '" tds_button="' . $btn1_style . '" button_open_in_new_window="' . $btn1_new_window . '" el_class="tdm-menu-btn1"]');
            }

            if ( $btn2_text !== '' ) {
                echo do_shortcode('[tdm_block_button button_text="' . $btn2_text . '" button_size="tdm-btn-md" button_display="tdm-block-button-inline" button_url="' . $btn2_url . '" tds_button="' . $btn2_style . '" button_open_in_new_window="' . $btn2_new_window . '" el_class="tdm-menu-btn2"]');
            }
            ?>
        </div>
    <?php } ?>
</div>

<div id="td-header-menu" role="navigation">
    <?php if ( td_util::get_option('tds_hide_mobile_menu') != 'hide' ) { ?>
    <div id="td-top-mobile-toggle"><a href="#" aria-label="mobile-toggle"><i class="td-icon-font td-icon-mobile"></i></a></div>
    <?php } ?>
    <div class="td-main-menu-logo td-logo-in-menu">
        <?php
        if (td_util::get_option('tds_logo_menu_upload') == '') {
            require_once( TDSP_THEME_PATH . '/parts/header/logo-h1.php' );
        } else {
            require_once( TDSP_THEME_PATH . '/parts/header/logo-mobile-h1.php' );
        }?>
    </div>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'header-menu',
        'menu_class'=> 'sf-menu',
        'fallback_cb' => 'td_wp_page_menu',
        'walker' => new td_tagdiv_walker_nav_menu()
    ));


    //if no menu
    function td_wp_page_menu() {
        //this is the default menu
        echo '<ul class="sf-menu">';
        echo '<li class="menu-item-first"><a href="' . esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations">Click here - to select or create a menu</a></li>';
        echo '</ul>';
    }
    ?>
</div>

<?php
td_resources_load::render_script( TDC_SCRIPTS_URL . '/tdMenu.js' . TDC_SCRIPTS_VER, 'tdMenu-js', '', 'footer' );
td_resources_load::render_script( TDC_SCRIPTS_URL . '/tdAjaxSearch.js' . TDC_SCRIPTS_VER, 'tdAjaxSearch-js', '', 'footer' );
?>
