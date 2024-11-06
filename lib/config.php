<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfConfig' ) ) {

	class WpssoGmfConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssogmf' => array(			// Plugin acronym.
					'version'     => '9.9.0',	// Plugin version.
					'opt_version' => '5',		// Increment when changing default option values.
					'short'       => 'WPSSO GMF',	// Short plugin name.
					'name'        => 'WPSSO Google Merchant Feed XML',
					'desc'        => 'Google Merchant product and inventory feed XML for WooCommerce and custom product pages, including multilingual support.',
					'slug'        => 'wpsso-google-merchant-feed',
					'base'        => 'wpsso-google-merchant-feed/wpsso-google-merchant-feed.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-google-merchant-feed',
					'domain_path' => '/languages',

					/*
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '18.17.0',
						),
					),

					/*
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/*
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/*
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'google-merchant' => 'Google Merchant',
						),
					),

					/*
					 * Callbacks for Vitalybaev\GoogleMerchant classes.
					 */
					'callbacks' => array(
						'inventory' => array(
							'og:time'                  => 'setTimestamp',
							'product:merchant_id'      => 'setTargetCustomerId',
							'product:store_code'       => 'setStoreCode',
							'product:retailer_item_id' => 'setId',
							'product:ean'              => 'addGtin',	// One or more.
							'product:gtin14'           => 'addGtin',	// One or more.
							'product:gtin13'           => 'addGtin',	// One or more.
							'product:gtin12'           => 'addGtin',	// One or more.
							'product:gtin8'            => 'addGtin',	// One or more.
							'product:gtin'             => 'addGtin',	// One or more.
							'product:isbn'             => 'addGtin',	// One or more.
							'product:upc'              => 'addGtin',	// One or more.
							'product:quantity'         => 'setQuantity',
							'product:price'            => 'setPrice',
						),
						'product' => array(
							'og:title'                            => 'setTitle',
							'og:description'                      => 'setDescription',
							'og:updated_time'                     => 'setUpdated',
							'og:url'                              => 'setCanonicalLink',
							'product:retailer_item_id'            => 'setId',
							'product:title'                       => 'setTitle',
							'product:description'                 => 'setDescription',
							'product:updated_time'                => 'setUpdated',
							'product:url'                         => 'setLink',
							'product:availability'                => 'setAvailability',
							'product:price'                       => 'setPrice',
							'product:sale_price'                  => 'setSalePrice',
							'product:sale_price_dates'            => 'setSalePriceEffectiveDate',
							'product:category'                    => 'setGoogleCategory',
							'product:retailer_category'           => 'setProductType',
							'product:brand'                       => 'setBrand',
							'product:ean'                         => 'addGtin',	// One or more.
							'product:gtin14'                      => 'addGtin',	// One or more.
							'product:gtin13'                      => 'addGtin',	// One or more.
							'product:gtin12'                      => 'addGtin',	// One or more.
							'product:gtin8'                       => 'addGtin',	// One or more.
							'product:gtin'                        => 'addGtin',	// One or more.
							'product:isbn'                        => 'addGtin',	// One or more.
							'product:upc'                         => 'addGtin',	// One or more.
							'product:mfr_part_no'                 => 'setMpn',
							'product:condition'                   => 'setCondition',
							'product:adult_type'                  => 'setAdult',
							'product:energy_efficiency:value'     => 'setEnergyEfficiencyClass',
							'product:energy_efficiency:min_value' => 'setMinEnergyEfficiencyClass',
							'product:energy_efficiency:max_value' => 'setMaxEnergyEfficiencyClass',
							'product:age_group'                   => 'setAgeGroup',
							'product:color'                       => 'setColor',
							'product:target_gender'               => 'setGender',
							'product:material'                    => 'setMaterial',
							'product:pattern'                     => 'setPattern',
							'product:size'                        => 'setSize',
							'product:size_group'                  => 'addSizeType',	// One or more.
							'product:size_system'                 => 'setSizeSystem',
							'product:item_group_id'               => 'setItemGroupId',
							'product:length:value'                => 'setProductLength',
							'product:length:units'                => null,
							'product:width:value'                 => 'setProductWidth',
							'product:width:units'                 => null,
							'product:height:value'                => 'setProductHeight',
							'product:height:units'                => null,
							'product:weight:value'                => 'setProductWeight',
							'product:weight:units'                => null,
							'product:shipping_length:value'       => 'setShippingLength',
							'product:shipping_length:value'       => null,
							'product:shipping_width:value'        => 'setShippingWidth',
							'product:shipping_width:value'        => null,
							'product:shipping_height:value'       => 'setShippingHeight',
							'product:shipping_height:value'       => null,
							'product:shipping_weight:value'       => 'setShippingWeight',
							'product:shipping_weight:value'       => null,
						),
						'shipping' => array(
							'shipping_name'          => 'setLocationGroupName',
							'shipping_rate_name'     => 'setService',
							'shipping_rate_cost'     => 'setPrice',
							'shipping_rate_currency' => null,
							'country_code'           => 'setCountry',
							'region_code'            => 'setRegion',
							'postal_code'            => 'setPostalCode',
							'handling_minimum'	 => array( 'setAttribute', 'min_handling_time', false ),
							'handling_maximum'	 => array( 'setAttribute', 'max_handling_time', false ),
							'handling_unit_code'     => null,
							'transit_minimum'	 => array( 'setAttribute', 'min_transit_time', false ),
							'transit_maximum'	 => array( 'setAttribute', 'max_transit_time', false ),
							'transit_unit_code'      => null,
						),
					),

					/*
					 * Declare compatibility with WooCommerce HPOS.
					 *
					 * See https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book.
					 */
					'wc_compat' => array(
						'custom_order_tables',
					),
				),
			),
			'opt' => array(
				'defaults' => array(
					'gmf_img_width'          => 1200,
					'gmf_img_height'         => 1200,
					'gmf_img_crop'           => 1,
					'gmf_img_crop_x'         => 'center',
					'gmf_img_crop_y'         => 'center',
					'gmf_feed_exp_secs'      => WEEK_IN_SECONDS,
					'gmf_feed_format'        => 'atom',
					'gmf_inventory_exp_secs' => HOUR_IN_SECONDS,
					'gmf_inventory_format'   => 'atom',
					'gmf_merchant_id'        => '',
					'gmf_store_code'         => '',
					'gmf_add_shipping'       => 0,
				),
			),	// End of 'opt' array.
			'form' => array(
				'feed_formats' => array(
					'atom' => 'Atom 1.0',
					'rss'  => 'RSS 2.0',
				),
			),	// End of 'form' array.
			'head' => array(

				/*
				 * Ensure the best performance by using the highest quality images. Submit the largest, highest
				 * resolution, full-size image you have for the product, up to 64 megapixels and 16MB file size. We
				 * recommend images of at least 800 x 800 pixels.
				 *
				 * See https://support.google.com/merchants/answer/6324350.
				 */
				'limit_min' => array(
					'gmf_img_width'  => 800,
					'gmf_img_height' => 800,
				),

				/*
				 * See https://support.google.com/merchants/answer/7052112.
				 */
				'gmf_content_map' => array(

					/*
					 * Validated on 2023/11/26.
					 *
					 * See https://support.google.com/merchants/answer/6324463.
					 * See https://developers.facebook.com/docs/marketing-api/catalog/reference/.
					 */
					'product:age_group' => array(
						'adult'    => 'adult',
						'all ages' => 'adult',
						'teen'     => 'adult',
						'kids'     => 'kids',
						'toddler'  => 'toddler',
						'infant'   => 'infant',
						'newborn'  => 'newborn',
					),
				),
			),	// End of 'head' array.
			'wp' => array(
				'cache' => array(
					'file' => array(
						'wpssogmf_feed_' => array(
							'label'   => 'Google Merchant Feed XML',
							'opt_key' => 'gmf_feed_exp_secs',
							'filter'  => 'wpsso_cache_expire_gmf_feed_xml',	// See WpssoUtil->get_cache_exp_secs().
						),
						'wpssogmf_inventory_' => array(
							'label'   => 'Google Merchant Inventory XML',
							'opt_key' => 'gmf_inventory_exp_secs',
							'filter'  => 'wpsso_cache_expire_gmf_inventory_xml',	// See WpssoUtil->get_cache_exp_secs().
						),
					),
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssogmf' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function get_callbacks( $item_type ) {

			return self::$cf[ 'plugin' ][ 'wpssogmf' ][ 'callbacks' ][ $item_type ];
		}

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSOGMF_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssogmf' ];

			/*
			 * Define fixed constants.
			 */
			define( 'WPSSOGMF_FILEPATH', $plugin_file );
			define( 'WPSSOGMF_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-google-merchant-feed/wpsso-google-merchant-feed.php.
			define( 'WPSSOGMF_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSOGMF_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-google-merchant-feed.
			define( 'WPSSOGMF_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSOGMF_VERSION', $info[ 'version' ] );

			/*
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( ! is_array( $var_const ) ) {

				$var_const = self::get_variable_constants();
			}

			/*
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

			$var_const[ 'WPSSOGMF_PAGENAME' ]               = 'google-merchant';
			$var_const[ 'WPSSOGMF_CACHE_REFRESH_MAX_TIME' ] = 600;		// 10 mins by default.

			/*
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {

					$var_const[ $name ] = constant( $name );
				}
			}

			return $var_const;
		}

		/*
		 * Require library files with functions or static methods in require_libs().
		 *
		 * Require and instantiate library files with dynamic methods in init_objects().
		 */
		public static function require_libs( $plugin_file ) {

			require_once WPSSOGMF_PLUGINDIR . 'vendor/autoload.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/actions.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/register.php';
			require_once WPSSOGMF_PLUGINDIR . 'lib/rewrite.php';	// Static methods required by WpssoGmfRegister->activate_plugin().
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

						return SucomUtil::sanitize_classname( 'wpssogmf' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
