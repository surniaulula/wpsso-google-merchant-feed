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
				'plugin_image_sizes'     => 1,
				'cache_refreshed_notice' => 2,
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

		public function filter_cache_refreshed_notice( $notice_msg, $user_id = null ) {

			$xml_count      = 0;
			$current_locale = SucomUtil::get_locale();

			/*
			 * Calls SucomUtil::get_available_locale_names() and applies the 'sucom_available_feed_locale_names'
			 * filter.
			 *
			 * Returns an associative array with locale keys and native names (example: 'en_US' => 'English (United
			 * States)').
			 */
			$locale_names = SucomUtil::get_available_feed_locale_names();	// Uses a local cache.

			/*
			 * Move the current locale last to generate any notices in the current locale.
			 */
			SucomUtil::move_to_end( $locale_names, $current_locale );

			foreach ( $locale_names as $locale => $native_name ) {

				WpssoGmfXml::clear_cache( $locale );	// Clear the feed XML cache file.

				$xml = WpssoGmfXml::get( $locale );

				$xml_count++;
			}

			$metabox_title = _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' );

			$notice_msg .= sprintf( __( '%1$s for %2$s locales has been refreshed.', 'wpsso-google-merchant-feed' ), $metabox_title, $xml_count ) . ' ';

			return $notice_msg;
		}
	}
}
