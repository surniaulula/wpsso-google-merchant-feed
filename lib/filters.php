<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFilters' ) ) {

	class WpssoGmfFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.
		private $msgs;	// WpssoGmfFiltersMessages class object.

		/**
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
				'cache_refreshed_notice' => 3,
			) );

			if ( is_admin() ) {

				require_once WPSSOGMF_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoGmfFiltersMessages( $plugin, $addon );
			}
		}

		public function filter_cache_refreshed_notice( $notice_msg, $user_id, $read_cache ) {

			$xml_count = 0;

			$locale_names = SucomUtil::get_available_feed_locale_names();	// Uses a local static cache.

			foreach ( $locale_names as $locale => $native_name ) {

				switch_to_locale( $locale );	// Calls an action to clear the SucomUtil::get_locale() cache. 

				$xml = WpssoGmfXml::get( $read_cache );

				$xml_count++;
			}

			restore_current_locale();	// Calls an action to clear the SucomUtil::get_locale() cache.

			$notice_msg .= sprintf( __( 'The Google Merchant Feeds XML cache for %1$d locales has been refreshed.', 'wpsso' ), $xml_count ) . ' ';

			return $notice_msg;
		}
	}
}
