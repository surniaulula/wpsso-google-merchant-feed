<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFilters' ) ) {

	class WpssoGmfFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.
		private $adv;	// WpssoGmfFiltersAdvanced class object.
		private $edit;	// WpssoGmfFiltersEdit class object.
		private $msgs;	// WpssoGmfFiltersMessages class object.

		/*
		 * Instantiated by WpssoGmf->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( $do_once ) return;	// Stop here.

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'plugin_image_sizes'     => 1,
				'cache_refreshed_notice' => 2,
			) );

			/*
			 * See https://support.google.com/merchants/answer/7052112?hl=en#shipping_and_returns.
			 */
			$add_shipping = apply_filters( 'wpsso_gmf_add_shipping', empty( $this->p->options[ 'gmf_add_shipping' ] ) ? false : true );

			if ( $add_shipping ) {

				$this->p->util->add_plugin_filters( $this, array(
					'og_add_mt_shipping_offers' => '__return_true',
				), PHP_INT_MAX );	// Run last.
			}

			if ( is_admin() ) {

				require_once WPSSOGMF_PLUGINDIR . 'lib/filters-advanced.php';

				$this->adv = new WpssoGmfFiltersAdvanced( $plugin, $addon );

				require_once WPSSOGMF_PLUGINDIR . 'lib/filters-edit.php';

				$this->edit = new WpssoGmfFiltersEdit( $plugin, $addon );

				require_once WPSSOGMF_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoGmfFiltersMessages( $plugin, $addon );
			}
		}

		public function filter_plugin_image_sizes( array $sizes ) {

			$sizes[ 'gmf' ] = array(	// Option prefix.
				'name'         => 'gmf',
				'label_transl' => _x( 'Google Merchant Feed XML', 'option label', 'wpsso-google-merchant-feed' ),
			);

			return $sizes;
		}

		public function filter_cache_refreshed_notice( $notice_msg = '', $user_id = null ) {

			return WpssoGmfXml::cache_refreshed_notice( $notice_msg );
		}
	}
}
