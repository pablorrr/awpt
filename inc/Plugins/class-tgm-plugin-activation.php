<?php
/**
 * Plugin installation and activation for WordPress themes.
 *
 * Please note that this is a drop-in library for a theme or plugin.
 * The authors of this library (Thomas, Gary and Juliette) are NOT responsible
 * for the support of your plugin or theme. Please contact the plugin
 * or theme author for support.
 *
 * @package   TGM-Plugin-Activation
 * @version   2.5.2
 * @link      http://tgmpluginactivation.com/
 * @author    Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright Copyright (c) 2011, Thomas Griffin
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: TGM Plugin Activation
 * Plugin URI:
 * Description: Plugin installation and activation for WordPress themes.
 * Version:     2.5.2
 * Author:      Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * Author URI:  http://tgmpluginactivation.com/
 * Text Domain: tgmpa
 * Domain Path: /languages/
 * Copyright:   2011, Thomas Griffin
 */

/*
	Copyright 2011 Thomas Griffin (thomasgriffinmedia.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {

	/**
	 * Automatic plugin installation and activation library.
	 *
	 * Creates a way to automatically install and activate plugins from within themes.
	 * The plugins can be either bundled, downloaded from the WordPress
	 * Plugin Repository or downloaded from another external source.
	 *
	 * @since 1.0.0
	 *
	 * @package TGM-Plugin-Activation
	 * @author  Thomas Griffin
	 * @author  Gary Jones
	 */
	require_once dirname(__FILE__) . '/TGM/TGM_Plugin_Activation.php';

	if ( ! function_exists( 'load_tgm_plugin_activation' ) ) {
		/**
		 * Ensure only one instance of the class is ever invoked.
		 */
		function load_tgm_plugin_activation() {
			$GLOBALS['tgmpa'] = TGM_Plugin_Activation::get_instance();
		}
	}

	if ( did_action( 'plugins_loaded' ) ) {
		load_tgm_plugin_activation();
	} else {
		add_action( 'plugins_loaded', 'load_tgm_plugin_activation' );
	}
}

if ( ! function_exists( 'tgmpa' ) ) {
	/**
	 * Helper function to register a collection of required plugins.
	 *
	 * @since 2.0.0
	 * @api
	 *
	 * @param array $plugins An array of plugin arrays.
	 * @param array $config  Optional. An array of configuration values.
	 */
	function tgmpa( $plugins, $config = array() ) {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

		foreach ( $plugins as $plugin ) {
			call_user_func( array( $instance, 'register' ), $plugin );
		}

		if ( ! empty( $config ) && is_array( $config ) ) {
			// Send out notices for deprecated arguments passed.
			if ( isset( $config['notices'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.2.0', 'The `notices` config parameter was renamed to `has_notices` in TGMPA 2.2.0. Please adjust your configuration.' );
				if ( ! isset( $config['has_notices'] ) ) {
					$config['has_notices'] = $config['notices'];
				}
			}

			if ( isset( $config['parent_menu_slug'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.4.0', 'The `parent_menu_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.' );
			}
			if ( isset( $config['parent_url_slug'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.4.0', 'The `parent_url_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.' );
			}

			call_user_func( array( $instance, 'config' ), $config );
		}
	}
}

/**
 * WP_List_Table isn't always available. If it isn't available,
 * we load it here.
 *
 * @since 2.2.0
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists('TGMPA_List_Table') ) {

	/**
	 * List table class for handling plugins.
	 *
	 * Extends the WP_List_Table class to provide a future-compatible
	 * way of listing out all required/recommended plugins.
	 *
	 * Gives users an interface similar to the Plugin Administration
	 * area with similar (albeit stripped down) capabilities.
	 *
	 * This class also allows for the bulk install of plugins.
	 *
	 * @since 2.2.0
	 *
	 * @package TGM-Plugin-Activation
	 * @author  Thomas Griffin
	 * @author  Gary Jones
	 */
	require_once dirname(__FILE__) . '/TGM/TGMPA_List_Table.php';
}


if ( ! class_exists( 'TGM_Bulk_Installer' ) ) {

	/**
	 * Hack: Prevent TGMPA v2.4.1- bulk installer class from being loaded if 2.4.1- is loaded after 2.5+.
	 */
	class TGM_Bulk_Installer {
	}
}
if ( ! class_exists( 'TGM_Bulk_Installer_Skin' ) ) {

	/**
	 * Hack: Prevent TGMPA v2.4.1- bulk installer skin class from being loaded if 2.4.1- is loaded after 2.5+.
	 */
	class TGM_Bulk_Installer_Skin {
	}
}

/**
 * The WP_Upgrader file isn't always available. If it isn't available,
 * we load it here.
 *
 * We check to make sure no action or activation keys are set so that WordPress
 * does not try to re-include the class when processing upgrades or installs outside
 * of the class.
 *
 * @since 2.2.0
 */
add_action( 'admin_init', 'tgmpa_load_bulk_installer' );
if ( ! function_exists( 'tgmpa_load_bulk_installer' ) ) {
	/**
	 * Load bulk installer
	 */
	function tgmpa_load_bulk_installer() {
		// Silently fail if 2.5+ is loaded *after* an older version.
		if ( ! isset( $GLOBALS['tgmpa'] ) ) {
			return;
		}

		// Get TGMPA class instance.
		$tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

		if ( isset( $_GET['page'] ) && $tgmpa_instance->menu === $_GET['page'] ) {
			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			if ( ! class_exists( '/TGM/TGMPA_Bulk_Installer' ) ) {

				/**
				 * Installer class to handle bulk plugin installations.
				 *
				 * Extends WP_Upgrader and customizes to suit the installation of multiple
				 * plugins.
				 *
				 * @since 2.2.0
				 *
				 * @internal Since 2.5.0 the class is an extension of Plugin_Upgrader rather than WP_Upgrader
				 * @internal Since 2.5.2 the class has been renamed from TGM_Bulk_Installer to TGMPA_Bulk_Installer.
				 *           This was done to prevent backward compatibility issues with v2.3.6.
				 *
				 * @package TGM-Plugin-Activation
				 * @author  Thomas Griffin
				 * @author  Gary Jones
				 */
				require_once dirname(__FILE__) . '/TGM/TGMPA_Bulk_Installer.php';

			}

			if ( ! class_exists( 'TGMPA_Bulk_Installer_Skin' ) ) {

				/**
				 * Installer skin to set strings for the bulk plugin installations..
				 *
				 * Extends Bulk_Upgrader_Skin and customizes to suit the installation of multiple
				 * plugins.
				 *
				 * @since 2.2.0
				 *
				 * @internal Since 2.5.2 the class has been renamed from TGM_Bulk_Installer_Skin to
				 *           TGMPA_Bulk_Installer_Skin.
				 *           This was done to prevent backward compatibility issues with v2.3.6.
				 *
				 * @see https://core.trac.wordpress.org/browser/trunk/src/wp-admin/includes/class-wp-upgrader-skins.php
				 *
				 * @package TGM-Plugin-Activation
				 * @author  Thomas Griffin
				 * @author  Gary Jones
				 */
                require_once dirname(__FILE__) . '/TGM/TGMPA_Bulk_Installer_Skin.php';

			}
		}
	}
}

if ( ! class_exists( 'TGMPA_Utils' ) ) {

	/**
	 * Generic utilities for TGMPA.
	 *
	 * All methods are static, poor-dev name-spacing class wrapper.
	 *
	 * Class was called TGM_Utils in 2.5.0 but renamed TGMPA_Utils in 2.5.1 as this was conflicting with Soliloquy.
	 *
	 * @since 2.5.0
	 *
	 * @package TGM-Plugin-Activation
	 * @author  Juliette Reinders Folmer
	 */
	require_once dirname(__FILE__) . '/TGM/TGMPA_Utils.php';

} // End of class_exists wrapper