<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 13.07.2017
 * Time: 9:38
 */

class tds_menu_sub_active2 extends td_style {

	private $unique_block_class;
    private $unique_style_class;
	private $atts = array();
	private $index_style;

	function __construct( $atts, $unique_block_class = '', $index_style = '') {
		$this->atts = $atts;
		$this->unique_block_class = $unique_block_class;
		$this->index_style = $index_style;
	}

	private function get_css() {

        $compiled_css = '';

        $general_block_class = (td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) ? '.tdc-row' : '';
        $unique_block_class = ((td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) ? '.tdc-row .tdc-column .' : '.') . $this->unique_block_class;


		$raw_css =
			"<style>

				/* @style_general_menu_sub_active2 */
				$general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li > a .tdb-menu-item-text {
                  position: relative;
                }
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
                  content: '';
                  position: absolute;
                  bottom: 0;
                  left: 0;
                  width: 100%;
                  height: 2px;
                  background-color: var(--td_theme_color, #4db2ec);
                  opacity: 0;
                  -webkit-transition: all 0.3s ease-in-out;
                  transition: all 0.3s ease-in-out;
                }
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu.current-menu-item > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li.current-menu-item > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li.current-menu-ancestor > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li.current-category-ancestor > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu:hover > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li:hover > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .tdb-menu ul .tdb-normal-menu.tdb-hover > a .tdb-menu-item-text:after,
                $general_block_class .tds_menu_sub_active2 .td-pulldown-filter-list li.tdb-hover > a .tdb-menu-item-text:after {
                  opacity: 1;
                }

				
				/* @sub_text_color_h */
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a,
				$unique_block_class .td-pulldown-filter-list li:hover a {
					color: @sub_text_color_h;
				}
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .td-pulldown-filter-list li:hover a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .td-pulldown-filter-list li:hover a .tdb-sub-menu-icon-svg svg * {
					fill: @sub_text_color_h;
				}
				/* @sub_color_h */
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a i,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a i,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a i,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a i,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a i {
					color: @sub_color_h;
				}
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-item > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-menu-ancestor > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.current-category-ancestor > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu.tdb-hover > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .tdb-menu ul .tdb-normal-menu:hover > a .tdb-sub-menu-icon-svg svg *,
				$unique_block_class .td-pulldown-filter-list li:hover a .tdb-sub-menu-icon-svg svg,
				$unique_block_class .td-pulldown-filter-list li:hover a .tdb-sub-menu-icon-svg svg * {
					fill: @sub_color_h;
				}
				
				/* @line_width */
				$unique_block_class .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
				$unique_block_class .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
				    width: @line_width;
				}
				/* @line_height */
				$unique_block_class .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
				$unique_block_class .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
				    height: @line_height;
				}
				/* @line_alignment */
				$unique_block_class .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
				$unique_block_class .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
				    bottom: -@line_alignment;
				}
				/* @line_color_solid */
				$unique_block_class .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
				$unique_block_class .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
				    background-color: @line_color_solid;
				}
				/* @line_color_gradient */
				$unique_block_class .tdb-menu ul .tdb-normal-menu > a .tdb-menu-item-text:after,
				$unique_block_class .td-pulldown-filter-list li > a .tdb-menu-item-text:after {
				    @line_color_gradient
				}

			</style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->atts);

        $compiled_css .= $td_css_res_compiler->compile_css();
		return $compiled_css;
	}

    /**
     * Callback pe media
     *
     * @param $responsive_context td_res_context
     * @param $atts
     */
    static function cssMedia( $res_ctx ) {

        $res_ctx->load_settings_raw( 'style_general_menu_sub_active2', 1 );

        // colors
        $res_ctx->load_settings_raw( 'sub_text_color_h', $res_ctx->get_style_att( 'sub_text_color_h', __CLASS__ ) );
        $res_ctx->load_settings_raw( 'sub_color_h', $res_ctx->get_style_att( 'sub_color_h', __CLASS__ ) );

        // line width
        $line_width = $res_ctx->get_style_att( 'line_width', __CLASS__ );
        $res_ctx->load_settings_raw( 'line_width', $line_width );
        if( $line_width != '' && is_numeric($line_width) ) {
            $res_ctx->load_settings_raw( 'line_width', $line_width . 'px' );
        }

        // line height
        $line_height = $res_ctx->get_style_att( 'line_height', __CLASS__ );
        if( $line_height != '' && is_numeric($line_height) ) {
            $res_ctx->load_settings_raw( 'line_height', $line_height . 'px' );
        }

        // line alignment
        $res_ctx->load_settings_raw( 'line_alignment', $res_ctx->get_style_att( 'line_alignment', __CLASS__ ) . 'px' );

        // line color
        $res_ctx->load_color_settings( 'line_color', 'line_color_solid', 'line_color_gradient', '', '', __CLASS__ );

    }

	function render( $index_style = '' ) {
		if ( ! empty( $index_style ) ) {
			$this->index_style = $index_style;
		}
        $this->unique_style_class = td_global::td_generate_unique_id();

		$buffy = $this->get_style($this->get_css());

		return $buffy;
	}

	function get_style_att( $att_name ) {
		return $this->get_att( $att_name ,__CLASS__, $this->index_style );
	}

	function get_atts() {
		return $this->atts;
	}
}
