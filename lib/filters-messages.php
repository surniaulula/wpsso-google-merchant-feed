<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
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
				'messages_info'         => 2,
				'messages_tooltip'      => 2,
				'messages_tooltip_meta' => 2,
			) );
		}

		public function filter_messages_info( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'info-gmf-' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'info-gmf-urls':

					$text .= '<blockquote class="top-info">';

					$text .= __( 'Google Merchant Feeds XML for WooCommerce, Easy Digital Downloads, and custom products are automatically created for each available language (as dictated by Polylang, WPLM, or the installed WordPress languages).', 'wpsso-google-merchant-feed' );

					$text .= '</blockquote>';

					break;
			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-gmf_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-gmf_img_size':

					$def_img_dims = $this->p->msgs->get_def_img_dims( 'gmf' );

					$text = sprintf( __( 'The dimensions used for the Google Merchant Feeds image (default dimensions are %s).', 'wpsso-google-merchant-feed' ), $def_img_dims ) . ' ';

					break;

			}	// End of 'tooltip' switch.

			return $text;
		}

		public function filter_messages_tooltip_meta( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-meta-gmf_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				/**
				 * Document SSO > Edit Media tab.
				 */
				case 'tooltip-meta-gmf_img_id':		// Image ID.

					$text = __( 'A customized image ID for the Google Merchant Feeds.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'The default value is inherited from the Schema markup or priority image.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= '<em>' . __( 'This option is disabled if a custom image URL is entered.', 'wpsso-google-merchant-feed' ) . '</em>';

				 	break;

				case 'tooltip-meta-gmf_img_url':	// or an Image URL.

					$text = __( 'A customized image URL (instead of an image ID) for the Google Merchant Feeds.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'The default value is inherited from the Schema markup or priority image.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= '<em>' . __( 'This option is disabled if a custom image ID is selected.', 'wpsso-google-merchant-feed' ) . '</em>';

				 	break;

			}	// End of 'tooltip-meta' switch.

			return $text;
		}
	}
}
