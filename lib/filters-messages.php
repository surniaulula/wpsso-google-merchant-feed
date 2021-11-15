<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFiltersMessages' ) ) {

	class WpssoGmfFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;     // WpssoGmf class object.

		/**
		 * Instantiated by WpssoGmfFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array( 
				'messages_info'    => 2,
			) );
		}

		public function filter_messages_info( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'info-gmf-' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'info-gmf-urls':

					$text .= '<blockquote class="top-info">';

					$text .= __( 'Google merchant XML feeds for your WooCommerce, Easy Digital Downloads, and custom products are automatically created for each available language (as dictated by Polylang, WPLM, or the installed WordPress languages).', 'wpsso-google-merchant-feed' );

					$text .= '</blockquote>';

					break;
			}

			return $text;
		}
	}
}
