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
					'version'     => '4.2.0-dev.3',	// Plugin version.
					'opt_version' => '2',		// Increment when changing default option values.
					'short'       => 'WPSSO GMF',	// Short plugin name.
					'name'        => 'WPSSO Google Merchant Feed XML',
					'desc'        => 'Google Merchant Feed XMLs for WooCommerce, Easy Digital Downloads, and Custom Products.',
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
							'min_version'   => '14.2.0-dev.3',
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
				 * See https://support.google.com/merchants/answer/6324350.
				 */
				'limit_min' => array(
					'gmf_img_width'  => 800,
					'gmf_img_height' => 800,
				),
				'gmf_content_map' => array(

					/**
					 * See https://support.google.com/merchants/answer/6324508.
					 */
					'product:adult_type' => array(
						'https://schema.org/AlcoholConsideration'                     => true,
						'https://schema.org/DangerousGoodConsideration'               => true,
						'https://schema.org/HealthcareConsideration'                  => true,
						'https://schema.org/NarcoticConsideration'                    => true,
						'https://schema.org/ReducedRelevanceForChildrenConsideration' => true,
						'https://schema.org/SexualContentConsideration'               => true,
						'https://schema.org/TobaccoNicotineConsideration'             => true,
						'https://schema.org/UnclassifiedAdultConsideration'           => true,
						'https://schema.org/ViolenceConsideration'                    => true,
						'https://schema.org/WeaponConsideration'                      => true,
					),

					/**
					 * Validated on 2022/09/24.
					 *
					 * See https://developers.facebook.com/docs/marketing-api/catalog/reference/.
					 *
					 * See https://support.google.com/merchants/answer/6324463.
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

					/**
					 * See https://support.google.com/merchants/answer/6324448.
					 */
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

					/**
					 * Validated 2022/12/24.
					 *
					 * See https://support.google.com/merchants/answer/6324469.
					 */
					'product:condition' => array(
						'https://schema.org/DamagedCondition'     => 'used',		// USED.
						'https://schema.org/NewCondition'         => 'new',		// NEW_PRODUCT.
						'https://schema.org/RefurbishedCondition' => 'refurbished',	// REFURBISHED.
						'https://schema.org/UsedCondition'        => 'used',		// USED.
					),

					/**
					 * Validated 2022/12/24.
					 *
					 * See https://schema.org/EUEnergyEfficiencyEnumeration.
					 * See https://support.google.com/merchants/answer/7562785.
					 */
					'product:energy_efficiency:value' => array(
						'https://schema.org/EUEnergyEfficiencyCategoryA3Plus' => 'A+++',
						'https://schema.org/EUEnergyEfficiencyCategoryA2Plus' => 'A++',
						'https://schema.org/EUEnergyEfficiencyCategoryA1Plus' => 'A+',
						'https://schema.org/EUEnergyEfficiencyCategoryA'      => 'A',
						'https://schema.org/EUEnergyEfficiencyCategoryB'      => 'B',
						'https://schema.org/EUEnergyEfficiencyCategoryC'      => 'C',
						'https://schema.org/EUEnergyEfficiencyCategoryD'      => 'D',
						'https://schema.org/EUEnergyEfficiencyCategoryE'      => 'E',
						'https://schema.org/EUEnergyEfficiencyCategoryF'      => 'F',
						'https://schema.org/EUEnergyEfficiencyCategoryG'      => 'G',
					),
					'product:energy_efficiency:min_value' => array(
						'https://schema.org/EUEnergyEfficiencyCategoryA3Plus' => 'A+++',
						'https://schema.org/EUEnergyEfficiencyCategoryA2Plus' => 'A++',
						'https://schema.org/EUEnergyEfficiencyCategoryA1Plus' => 'A+',
						'https://schema.org/EUEnergyEfficiencyCategoryA'      => 'A',
						'https://schema.org/EUEnergyEfficiencyCategoryB'      => 'B',
						'https://schema.org/EUEnergyEfficiencyCategoryC'      => 'C',
						'https://schema.org/EUEnergyEfficiencyCategoryD'      => 'D',
						'https://schema.org/EUEnergyEfficiencyCategoryE'      => 'E',
						'https://schema.org/EUEnergyEfficiencyCategoryF'      => 'F',
						'https://schema.org/EUEnergyEfficiencyCategoryG'      => 'G',
					),
					'product:energy_efficiency:max_value' => array(
						'https://schema.org/EUEnergyEfficiencyCategoryA3Plus' => 'A+++',
						'https://schema.org/EUEnergyEfficiencyCategoryA2Plus' => 'A++',
						'https://schema.org/EUEnergyEfficiencyCategoryA1Plus' => 'A+',
						'https://schema.org/EUEnergyEfficiencyCategoryA'      => 'A',
						'https://schema.org/EUEnergyEfficiencyCategoryB'      => 'B',
						'https://schema.org/EUEnergyEfficiencyCategoryC'      => 'C',
						'https://schema.org/EUEnergyEfficiencyCategoryD'      => 'D',
						'https://schema.org/EUEnergyEfficiencyCategoryE'      => 'E',
						'https://schema.org/EUEnergyEfficiencyCategoryF'      => 'F',
						'https://schema.org/EUEnergyEfficiencyCategoryG'      => 'G',
					),

					/**
					 * See https://support.google.com/merchants/answer/6324497.
					 */
					'product:size_group' => array(
						'https://schema.org/WearableSizeGroupRegular'   => 'regular',
						'https://schema.org/WearableSizeGroupPetite'    => 'petite',
						'https://schema.org/WearableSizeGroupPlus'      => 'plus',
						'https://schema.org/WearableSizeGroupTall'      => 'tall',
						'https://schema.org/WearableSizeGroupBig'       => 'big',
						'https://schema.org/WearableSizeGroupMaternity' => 'maternity',
					),

					/**
					 * Validated on 2022/12/26.
					 *
					 * See https://support.google.com/merchants/answer/6324502.
					 */
					'product:size_system' => array(
						'https://schema.org/WearableSizeSystemAU'     => 'AU',
						'https://schema.org/WearableSizeSystemBR'     => 'BR',
						'https://schema.org/WearableSizeSystemCN'     => 'CN',
						'https://schema.org/WearableSizeSystemDE'     => 'DE',
						'https://schema.org/WearableSizeSystemEurope' => 'EU',
						'https://schema.org/WearableSizeSystemFR'     => 'FR',
						'https://schema.org/WearableSizeSystemIT'     => 'IT',
						'https://schema.org/WearableSizeSystemJP'     => 'JP',
						'https://schema.org/WearableSizeSystemMX'     => 'MEX',
						'https://schema.org/WearableSizeSystemUK'     => 'UK',
						'https://schema.org/WearableSizeSystemUS'     => 'US',
					),
				),
			),
			'wp' => array(
				'file' => array(
					'wpsso_g_' => array(
						'label'  => 'Google Merchant Feed XML',
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

		/**
		 * Require library files with functions or static methods in require_libs().
		 *
		 * Require and instantiate library files with dynamic methods in init_objects().
		 */
		public static function require_libs( $plugin_file ) {

			require_once WPSSOGMF_PLUGINDIR . 'vendor/autoload.php';
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

						$classname = SucomUtil::sanitize_classname( 'wpssogmf' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
