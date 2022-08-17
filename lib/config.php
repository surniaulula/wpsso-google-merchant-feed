<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfConfig' ) ) {

	class WpssoGmfConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssogmf' => array(			// Plugin acronym.
					'version'     => '3.0.0-dev.4',	// Plugin version.
					'opt_version' => '2',		// Increment when changing default option values.
					'short'       => 'WPSSO GMF',	// Short plugin name.
					'name'        => 'WPSSO Google Merchant Feeds XML',
					'desc'        => 'Google Merchant Feeds XML for WooCommerce, Easy Digital Downloads, and Custom Products.',
					'slug'        => 'wpsso-google-merchant-feed',
					'base'        => 'wpsso-google-merchant-feed/wpsso-google-merchant-feed.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-google-merchant-feed',
					'domain_path' => '/languages',

					/**
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '13.0.0-dev.4',
						),
					),

					/**
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/**
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/**
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'gmf-general' => 'Merchant Feeds',
						),
					),
				),
			),
			'opt' => array(
				'defaults' => array(
					'gmf_img_width'  => 1200,
					'gmf_img_height' => 1200,
					'gmf_img_crop'   => 1,
					'gmf_img_crop_x' => 'center',
					'gmf_img_crop_y' => 'center',
				),
			),
			'head' => array(

				/**
				 * Ensure the best performance by using the highest quality images. Submit the largest, highest
				 * resolution, full-size image you have for the product, up to 64 megapixels and 16MB file size. We
				 * recommend images of at least 800 x 800 pixels.
				 *
				 * See https://support.google.com/merchants/answer/6324350?hl=en.
				 */
				'limit_min' => array(
					'gmf_img_width'  => 800,
					'gmf_img_height' => 800,
				),
				'gmf_content_map' => array(
					'product:availability' => array(
						'https://schema.org/BackOrder'           => 'backorder',	// BACKORDER.
						'https://schema.org/Discontinued'        => 'out_of_stock',	// OUT_OF_STOCK.
						'https://schema.org/InStock'             => 'in_stock',		// IN_STOCK.
						'https://schema.org/InStoreOnly'         => 'in_stock',		// IN_STOCK.
						'https://schema.org/LimitedAvailability' => 'in_stock',		// IN_STOCK.
						'https://schema.org/OnlineOnly'          => 'in_stock',		// IN_STOCK.
						'https://schema.org/OutOfStock'          => 'out_of_stock',	// OUT_OF_STOCK.
						'https://schema.org/PreOrder'            => 'preorder',		// PREORDER.
						'https://schema.org/PreSale'             => 'preorder',		// PREORDER.
						'https://schema.org/SoldOut'             => 'out_of_stock',	// OUT_OF_STOCK.
					),
					'product:condition' => array(
						'https://schema.org/DamagedCondition'     => 'used',		// USED.
						'https://schema.org/NewCondition'         => 'new',		// NEW_PRODUCT.
						'https://schema.org/RefurbishedCondition' => 'refurbished',	// REFURBISHED.
						'https://schema.org/UsedCondition'        => 'used',		// USED.
					),
				),
			),
			'wp' => array(
				'file' => array(
					'wpsso_g_' => array(
						'label'  => 'Google Merchant Feeds XML',
						'value'  => DAY_IN_SECONDS,
						'filter' => 'wpsso_cache_expire_gmf_xml',
					),
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssogmf' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSOGMF_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssogmf' ];

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSOGMF_FILEPATH', $plugin_file );
			define( 'WPSSOGMF_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-google-merchant-feed/wpsso-google-merchant-feed.php.
			define( 'WPSSOGMF_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSOGMF_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-google-merchant-feed.
			define( 'WPSSOGMF_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSOGMF_VERSION', $info[ 'version' ] );

			/**
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( ! is_array( $var_const ) ) {

				$var_const = (array) self::get_variable_constants();
			}

			/**
			 * Define the variable constants, if not already defined.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( ! defined( $name ) ) {

					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() {

			$var_const = array();

			$var_const[ 'WPSSOGMF_PAGENAME' ] = 'merchant-feed';

			/**
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {

					$var_const[ $name ] = constant( $name );
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_file ) {

			require_once WPSSOGMF_PLUGINDIR . 'vendor/autoload.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/actions.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/register.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/rewrite.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/xml.php';

			add_filter( 'wpssogmf_load_lib', array( __CLASS__, 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false !== $success ) {

				return $success;
			}

			if ( ! empty( $classname ) ) {

				if ( class_exists( $classname ) ) {

					return $classname;
				}
			}

			if ( ! empty( $filespec ) ) {

				$file_path = WPSSOGMF_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						$classname = SucomUtil::sanitize_classname( 'wpssogmf' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
