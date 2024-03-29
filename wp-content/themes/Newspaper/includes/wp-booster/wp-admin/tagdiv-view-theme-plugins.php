<?php
/**
 * Created by ra on 5/15/2015.
 */

if ( current_user_can( 'activate_plugins' ) ) {
	// deactivate a plugin from tgm
	if ( isset( $_GET['td_deactivate_plugin_slug'] ) ) {

		if ( empty( $_GET['td_magic_token'] ) || wp_verify_nonce( $_GET['td_magic_token'], 'td-panel-plugins' ) === false ) {
			echo 'Permission denied';
			die;
		}

		$td_deactivate_plugin_slug = $_GET['td_deactivate_plugin_slug'];
		if ( !empty($td_deactivate_plugin_slug) ) {
			$plugins = TGM_Plugin_Activation::$instance->plugins;
			foreach ( $plugins as $plugin ) {
				if ( $plugin['slug'] == $td_deactivate_plugin_slug ) {
					deactivate_plugins($plugin['file_path']);
					?>
                    <script type="text/javascript">
                        window.location = "admin.php?page=td_theme_plugins";
                    </script>
					<?php
					break;
				}
			}
		}
	}

	// Activate a plugin
	if ( isset($_GET['td_activate_plugin_slug']) ) {

		if ( empty($_GET['td_magic_token']) || wp_verify_nonce( $_GET['td_magic_token'], 'td-panel-plugins' ) === false ) {
			echo 'Permission denied';
			die;
		}

		$td_activate_plugin_slug = $_GET['td_activate_plugin_slug'];
		if ( !empty($td_activate_plugin_slug) ) {
			$plugins = TGM_Plugin_Activation::$instance->plugins;

			foreach ( $plugins as $plugin ) {
				if ( $plugin['slug'] == $td_activate_plugin_slug ) {
					activate_plugins($plugin['file_path']);
					?>
                    <script type="text/javascript">
                        window.location = "admin.php?page=td_theme_plugins";
                    </script>
					<?php
					break;
				}
			}
		}
	}
}

require_once TAGDIV_ROOT_DIR . "/includes/wp-booster/wp-admin/tagdiv-view-header.php";

?>

<div class="td-admin-wrap about-wrap theme-browser td-admin-plugins">
    <h1>Install premium plugins</h1>
    <div class="about-text">
        <p>
            Install the included plugins with ease. All the plugins are well-tested to work with the theme and we keep them up to date. The theme comes packed with the following premium plugins:
        </p>
    </div>

    <div class="td-admin-columns">
		<?php

		$wp_plugin_list = get_plugins();

		// Get plugins that require update
		$td_plugins_update_list = array();
        //there are issues with ABSPATH on wp.com servers, so we use WP_PLUGIN_DIR check $file_data bellow
        //$plugins_path = ABSPATH . 'wp-content/plugins';

		// Check for Plugin updates
		wp_update_plugins();
		$plugin_updates = get_site_transient( 'update_plugins' );

		foreach ( $plugin_updates as $index => $value ) {
			if ( $index == "response" ) {
				if( isset( $value["amp/amp.php"] ) ) {
					$td_amp_new_ver = $value["amp/amp.php"]->new_version;
				}
			}
		}

		foreach ( tagdiv_global::get_td_plugins() as $constant => $settings ) {
			$plugin_name = strtolower( str_replace('_', '-', $constant ) );
			$plugin = $plugin_name . '/' . $plugin_name . '.php';

			//Add AMP plugin to the update plugins list
			if ( $plugin == 'amp/amp.php' ) {
				if ( isset( $td_amp_new_ver ) ) {
					$td_plugins_update_list[] = $plugin;
					continue;
				}
			}

			if ( $plugin === 'td-subscription/td-subscription.php') {
			    if ( !empty($plugin_updates->response['td-subscription/td-subscription.php'])) {
				    $td_plugins_update_list[] = $plugin;
			    }
			}

			//plugin exists and it's inactive
			if ( !class_exists( $settings['class'], false ) && isset( $wp_plugin_list[$plugin] ) ) {

				//add AMP plugin to the update plugins list if it is inactive
				if ( $plugin == 'amp/amp.php' ) {
					if( isset( $td_amp_new_ver ) ) {
						$td_plugins_update_list[] = $plugin;
					}
					continue;
				}

				//read plugin file
				global $wp_filesystem;
                //there are issues with ABSPATH on wp.com servers, so we use WP_PLUGIN_DIR
                $file_data = $wp_filesystem->get_contents( WP_PLUGIN_DIR . '/' . $plugin );

                preg_match('/define\s*\(\s*\'' . $constant . '\',\s*\'(.*)\'\s*\)/', $file_data, $matches);

				if ( !isset($matches[1] ) || $matches[1] !==  $settings['version'] ) {
					$td_plugins_update_list[] = $plugin;
				}
			}
		}

		//asort($theme_plugins);
		$td_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;

		$sorted_plugins = array();
		$demo_plugins = array();

		// sort theme plugins based on the config array
		foreach ( tagdiv_global::$theme_plugins_list as $td_global_plugin ) {

			// don't add plugins that supposed to appear in theme's plugins list
			if ( !$td_global_plugin['td_show_in_theme_plugins']) {
                if ( !$td_global_plugin['td_demo_plugin'] ) {
                    continue;
                }
			}

			foreach ( $td_tgm_theme_plugins as $td_tgm_theme_plugin ) {

                if ( $td_global_plugin['name'] == $td_tgm_theme_plugin['name'] && $td_tgm_theme_plugin['td_demo_plugin'] ) {
                    $demo_plugins[] = $td_tgm_theme_plugin;
                }
				if ( $td_global_plugin['name'] == $td_tgm_theme_plugin['name'] &&  !$td_tgm_theme_plugin['td_demo_plugin'] ) {
					$sorted_plugins[] = $td_tgm_theme_plugin;
				}

            }

		}

        //echo '<pre>';
        //var_dump($td_plugins_update_list);
        //echo '</pre>';

		$td_tgm_theme_plugins = array_merge( $sorted_plugins, tagdiv_global::$theme_plugins_for_info_list );

		foreach ( $td_tgm_theme_plugins as $td_tgm_theme_plugin ) {

			$tmp_class = 'td-plugin-not-installed';
			$required_label = $td_tgm_theme_plugin['required_label'];
			$plugin_msg = '';

			if ( isset( $td_tgm_theme_plugin['file_path'] ) ) {
				// file_path key is missing from elements that come from td_global::$theme_plugins_for_info_list
				if ( tagdiv_util::is_active( $td_tgm_theme_plugin ) ) {

					//show update button for AMP even if it is active
					if ( $td_tgm_theme_plugin['file_path'] == 'amp/amp.php' && in_array ( $td_tgm_theme_plugin['file_path'],  $td_plugins_update_list ) ) {
						$tmp_class = 'td-plugin-update';
                    } else {
						$tmp_class = 'td-plugin-active';
						$required_label = 'active';
					}

					if ( $td_tgm_theme_plugin['file_path'] == 'td-subscription/td-subscription.php' && in_array ( $td_tgm_theme_plugin['file_path'],  $td_plugins_update_list ) ) {
						$tmp_class .= ' td-plugin-update';
					}

				} else if ( in_array ( $td_tgm_theme_plugin['file_path'],  $td_plugins_update_list) ) {
					$tmp_class = 'td-plugin-update';
				} else if ( isset( $wp_plugin_list[$td_tgm_theme_plugin['file_path']] ) ) {
					$tmp_class = 'td-plugin-inactive';
				}

			} else {
				$tmp_class = 'td-plugin-no-install';
			}

			?>
            <div class="td-wp-admin-plugin <?php echo esc_attr( $tmp_class ) ?>">

                <!-- Import content -->
                <div class="td-plugin-image">
                    <span class="td-plugin-required td-<?php echo esc_attr( $required_label ) ?>"><?php printf( '%1$s', $required_label ) ?></span>

                    <?php

                    if ($td_tgm_theme_plugin['slug'] === 'td-subscription' && is_plugin_active('td-subscription/td-subscription.php') && defined('TD_SUBSCRIPTION_VERSION')) {
                        ?>
                            <span class="td-plugin-version">V <?php printf( '%1$s', TD_SUBSCRIPTION_VERSION ) ?></span>
                        <?php
                    }

                    ?>

                    <img class="td-demo-thumb" src="<?php echo esc_url( $td_tgm_theme_plugin['img'] ) ?>"/>
                    <div class="td-plugin-meta">
                        <h3 class="theme-name"><?php printf( '%1$s', $td_tgm_theme_plugin['name'] ) ?></h3>
						<?php

						if ( $plugin_msg == '' ) {
							echo '<p>' . $td_tgm_theme_plugin['text'] . '</p>';
						} else {
							echo '<p class="td-plugin-notice">' . $plugin_msg . '</p><a href="' . admin_url( 'plugins.php' ) . '" target="_blank">Go to plugins</a>';
						}

						// for plugins like rev slider/tagdiv mobile theme or visual composer the source is not set
						$td_tgm_theme_plugin_source = '';
						if( isset( $td_tgm_theme_plugin['source'] ) ) {
							$td_tgm_theme_plugin_source = $td_tgm_theme_plugin['name'];
						}

						?>
                        <div class="td-plugin-buttons">
                            <a class="td-plugin-button td-button-install-plugin" href="<?php
							echo esc_url( wp_nonce_url(
								add_query_arg(
									array(
										'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
										'plugin'		=> urlencode($td_tgm_theme_plugin['slug']),
										'plugin_name'   => urlencode($td_tgm_theme_plugin['name']),
										'plugin_source' => urlencode($td_tgm_theme_plugin_source),
										'tgmpa-install' => 'install-plugin',
										'return_url' => 'td_theme_plugins'
									),
									admin_url('themes.php')
								),
								'tgmpa-install',
                                'tgmpa-nonce'
							));
							?>">Install</a>
                            <a class="td-plugin-button td-button-update-plugin" href="<?php
							echo esc_url( wp_nonce_url(
								add_query_arg(
									array(
										'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
										'plugin'		=> urlencode($td_tgm_theme_plugin['slug']),
										'plugin_name'   => urlencode($td_tgm_theme_plugin['name']),
										'plugin_source' => urlencode($td_tgm_theme_plugin_source),
										'tgmpa-update'  => 'update-plugin',
										'return_url'    => 'td_theme_plugins'
									),
									admin_url('themes.php')
								),
								'tgmpa-update',
								'tgmpa-nonce'
							));
							$op_name = 'Update';
							if ('td-subscription' === $td_tgm_theme_plugin['slug'] && !empty($plugin_updates->response['td-subscription/td-subscription.php'])) {
							    $op_name = 'Update to ' . $plugin_updates->response['td-subscription/td-subscription.php']->new_version;
                            }

							?>"><?php echo $op_name ?></a>
                            <a class="td-plugin-button td-button-uninstall-plugin" href="<?php
							echo esc_url(
								add_query_arg(
									array(
										'page'		  	            => urlencode('td_theme_plugins'),
										'td_deactivate_plugin_slug'	=> urlencode($td_tgm_theme_plugin['slug']),
										'td_magic_token' => wp_create_nonce('td-panel-plugins')
									),
									admin_url('admin.php')
								));
							?>">Deactivate</a>
                            <a class="td-plugin-button td-button-activate-plugin" href="<?php
							echo esc_url(
								add_query_arg(
									array(
										'page'		  	            => urlencode('td_theme_plugins'),
										'td_activate_plugin_slug'	=> urlencode($td_tgm_theme_plugin['slug']),
										'td_magic_token' => wp_create_nonce('td-panel-plugins')
									),
									admin_url('admin.php')
								));
							?>">Activate</a>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}?>
    </div>

    <br>

<!--    <h1>Dedicated Plugins for Prebuilt Websites</h1>-->
<!--    <br>-->
    <div class="td-admin-accordion">
        <div class="td-admin-accordion-item">
            <div class="td-admin-accordion-title">
                <div class="td-admin-accordion-title-label">Dedicated Plugins for Prebuilt Websites</div>
                <div class="td-box-close-open-icon"></div>
            </div>

            <div class="td-admin-accordion-content">
                <div class="about-text">
                    <p>Some prebuilt website designs require a specific plugin to bring beautiful layouts and slick functionality. In this section, you will find the free dedicated plugins. For your convenience, the plugins have the same name as the prebuilt website design. So have fun and build an awesome website!</p>
                </div>

                <div class="td-admin-columns td-demo-plugins-install">
                    <?php foreach ($demo_plugins as $demo_plugin) {
                        $tmp_class = 'td-plugin-not-installed';
                        $required_label = $demo_plugin['required_label'];
                        $plugin_msg = '';

                        if ( isset( $demo_plugin['file_path'] ) ) {
                            //            var_dump($demo_plugin );

                            // file_path key is missing from elements that come from td_global::$theme_plugins_for_info_list
                            if ( tagdiv_util::is_active( $demo_plugin ) ) {
                                $tmp_class = 'td-plugin-active';
                                $required_label = 'active';

                            } else if ( isset( $wp_plugin_list[$demo_plugin['file_path']] ) ) {
                                $tmp_class = 'td-plugin-inactive';
                            }

                        } else {
                            $tmp_class = 'td-plugin-no-install';
                        }

                        ?>
                        <div class="td-wp-admin-plugin <?php echo esc_attr( $tmp_class ) ?>">

                            <!-- Import content -->
                            <div class="td-plugin-image">
                                <span class="td-plugin-required td-<?php echo esc_attr( $required_label ) ?>"><?php printf( '%1$s', $required_label ) ?></span>

                                <img class="td-demo-thumb" src="<?php echo esc_url( $demo_plugin['img'] ) ?>"/>
                                <div class="td-plugin-meta">
                                    <h3 class="theme-name"><?php printf( '%1$s', $demo_plugin['name'] ) ?></h3>
                                    <?php

                                    if ( $plugin_msg == '' ) {
                                        echo '<p>' . $demo_plugin['text'] . '</p>';
                                    } else {
                                        echo '<p class="td-plugin-notice">' . $plugin_msg . '</p><a href="' . admin_url( 'plugins.php' ) . '" target="_blank">Go to plugins</a>';
                                    }

                                    // for plugins like rev slider/tagdiv mobile theme or visual composer the source is not set
                                    $td_tgm_theme_plugin_source = '';
                                    if( isset( $demo_plugin['source'] ) ) {
                                        $td_tgm_theme_plugin_source = $demo_plugin['name'];
                                    }

                                    ?>
                                    <div class="td-plugin-buttons">
                                        <a class="td-plugin-button td-button-install-plugin" href="<?php
                                        echo esc_url( wp_nonce_url(
                                            add_query_arg(
                                                array(
                                                    'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
                                                    'plugin'		=> urlencode($demo_plugin['slug']),
                                                    'plugin_name'   => urlencode($demo_plugin['name']),
                                                    'plugin_source' => urlencode($td_tgm_theme_plugin_source),
                                                    'tgmpa-install' => 'install-plugin',
                                                    'return_url' => 'td_theme_plugins'
                                                ),
                                                admin_url('themes.php')
                                            ),
                                            'tgmpa-install',
                                            'tgmpa-nonce'
                                        ));
                                        ?>">Install</a>
                                        <a class="td-plugin-button td-button-update-plugin" href="<?php
                                        echo esc_url( wp_nonce_url(
                                            add_query_arg(
                                                array(
                                                    'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
                                                    'plugin'		=> urlencode($demo_plugin['slug']),
                                                    'plugin_name'   => urlencode($demo_plugin['name']),
                                                    'plugin_source' => urlencode($td_tgm_theme_plugin_source),
                                                    'tgmpa-update'  => 'update-plugin',
                                                    'return_url'    => 'td_theme_plugins'
                                                ),
                                                admin_url('themes.php')
                                            ),
                                            'tgmpa-update',
                                            'tgmpa-nonce'
                                        ));
                                        $op_name = 'Update';
                                        ?>"><?php echo $op_name ?></a>
                                        <a class="td-plugin-button td-button-uninstall-plugin" href="<?php
                                        echo esc_url(
                                            add_query_arg(
                                                array(
                                                    'page'		  	            => urlencode('td_theme_plugins'),
                                                    'td_deactivate_plugin_slug'	=> urlencode($demo_plugin['slug']),
                                                    'td_magic_token' => wp_create_nonce('td-panel-plugins')
                                                ),
                                                admin_url('admin.php')
                                            ));
                                        ?>">Deactivate</a>
                                        <a class="td-plugin-button td-button-activate-plugin" href="<?php
                                        echo esc_url(
                                            add_query_arg(
                                                array(
                                                    'page'		  	            => urlencode('td_theme_plugins'),
                                                    'td_activate_plugin_slug'	=> urlencode($demo_plugin['slug']),
                                                    'td_magic_token' => wp_create_nonce('td-panel-plugins')
                                                ),
                                                admin_url('admin.php')
                                            ));
                                        ?>">Activate</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>

    <h1>Tested Plugins</h1>
    <div class="about-text">
        <p>With each theme release we provide a list of fully suported plugins. In order to make the plugins look and work better, the theme may add custom stylesheets and hook custom code to them.
            We manually inspect each plugin periodically. If we missed something, feel free to contact us!</p>
    </div>

    <div class="td-supported-plugin-list">
        <div class="td-supported-plugin"><p>WP Super Cache</p><span> - caching plugin</span></div>
        <div class="td-supported-plugin"><p>Contact form 7</p><span>- used to make contact forms</span></div>
        <div class="td-supported-plugin"><p>bbPress</p><span>- forum plugin</span></div>
        <div class="td-supported-plugin"><p>BuddyPress</p><span>- social network plugin</span></div>
		<div class="td-supported-plugin"><p>WPML</p><span> - The WordPress Multilingual Plugin</span></div>
        <div class="td-supported-plugin"><p>Jetpack</p><span>- plugin with lots of features *it may slow down your site</span></div>
        <div class="td-supported-plugin"><p>WooCommerce</p><span>- eCommerce solution</span></div>
        <div class="td-supported-plugin"><p>WordPress (Yoast) SEO</p><span> - SEO plugin</span></div>
        <div class="td-supported-plugin"><p>Simple Local Avatar</p><span> - Change users avatars</span></div>
        <div class="td-supported-plugin"><p>GDPR Cookie Consent</p><span> - GDPR compliance plugin</span></div>
        <div class="td-supported-plugin"><p>HubSpot All-In-One Marketing</p><span> - Grow your email list, generate leads, and manage contacts</span></div>
    </div>
</div>
