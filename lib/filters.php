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

		private $p;		// Wpsso class object.
		private $a;		// WpssoGmf class object.

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
		}

		public function filter_cache_refreshed_notice( $notice_msg, $user_id, $read_cache ) {

			$avail_locales = SucomUtil::get_available_locales();	// Uses a local static cache.
			$xml_count     = 0;

			foreach ( $avail_locales as $locale ) {

				switch_to_locale( $locale );	// Calls an action to clear the SucomUtil::get_locale() cache. 

				$xml = WpssoGmfXml::get( $read_cache );

				$xml_count++;
			}

			restore_current_locale();	// Calls an action to clear the SucomUtil::get_locale() cache.

			$notice_msg .= sprintf( __( 'The Google Merchant Feed XML cache for %1$d locales has been refreshed.', 'wpsso' ), $xml_count ) . ' ';

			return $notice_msg;
		}
	}
}
