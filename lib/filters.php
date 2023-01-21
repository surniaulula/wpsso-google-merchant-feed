<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
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

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'plugin_image_sizes'                    => 1,
				'cache_refreshed_notice'                => 3,
				'request_url_query_attrs_cache_disable' => '__return_true',
			) );

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

		/*
		 * See WpssoGmfActions->action_load_setting_page_refresh_feed_xml_cache().
		 */
		public function filter_cache_refreshed_notice( $notice_msg, $user_id = null, $read_cache = false ) {

			$xml_count = 0;

			$locale_names = SucomUtil::get_available_feed_locale_names();	// Uses a local cache.

			foreach ( $locale_names as $locale => $native_name ) {

				switch_to_locale( $locale );

				$xml = WpssoGmfXml::get( $read_cache );

				$xml_count++;
			}

			restore_current_locale();	// Calls an action to clear the SucomUtil::get_locale() cache.

			$notice_msg .= sprintf( __( 'The Google Merchant Feed XML for %d locales has been refreshed.',
				'wpsso-google-merchant-feed' ), $xml_count ) . ' ';

			return $notice_msg;
		}
	}
}
