<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFiltersMessages' ) ) {

	class WpssoGmfFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.

		/*
		 * Instantiated by WpssoGmfFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'messages_info'         => 3,
				'messages_tooltip'      => 3,
				'messages_tooltip_meta' => 2,
			) );
		}

		public function filter_messages_info( $text, $msg_key, $info ) {

			if ( 0 !== strpos( $msg_key, 'info-gmf-' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'info-gmf-img':

					/*
					 * See https://support.google.com/merchants/answer/7052112.
					 * See https://support.google.com/merchants/answer/6324350.
					 */
					$text = '<p class="pro-feature-msg">';

					$text .= __( 'Product images must accurately display the entire product and include minimal or no product staging.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'Each product variation must use a unique image that represents the distinguishing details of that variation.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'Do not use a generic image, logo, icon, or illustration that is not of the actual product.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'Do not use an image that contains promotional elements or content that covers the product.',
						'wpsso-google-merchant-feed' ) . ' ';

					if ( ! empty( $this->p->avail[ 'ecom' ][ 'woocommerce' ] ) ) {

						if ( 'product' === $info[ 'mod' ][ 'post_type' ] ) {	// WooCommerce product editing page.

							if ( $this->p->util->wc->is_mod_variable( $info[ 'mod' ] ) ) {

								$text .= __( 'This is a variable product - images from product variations will supersede the main product image selected here.',
									'wpsso-google-merchant-feed' ) . ' ';
							}
						}
					}

					$text .= '</p>';

					break;
			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $msg_key, $info ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-gmf_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-gmf_img_size':

					$def_img_dims = $this->p->msgs->get_def_img_dims( 'gmf' );

					$text = sprintf( __( 'The dimensions used for the Google merchant feed XML image (default dimensions are %s).',
						'wpsso-google-merchant-feed' ), $def_img_dims ) . ' ';

					break;

				/*
				 * See https://support.google.com/merchants/answer/7052112?hl=en#shipping_and_returns.
				 */
				case 'tooltip-gmf_add_shipping':

					$text = __( 'Include shipping information for each product in the feed XML.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'Use this setting when shipping costs for your product are not defined in your Merchant Center account or when you need to override shipping costs or speeds defined in your Merchant Center account.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= '<strong>' . __( 'If you offer several world-wide shipping options, adding the shipping information for each country to each product may exceed available memory.', 'wpsso-google-merchant-feed' ) . '</strong> ';

					break;

				case 'tooltip-gmf_feed_exp_secs':

					$def_value = $this->p->opt->get_defaults( 'gmf_feed_exp_secs' );

					$text = sprintf( __( 'The XML file cache expiration time in seconds (default is %s).',
						'wpsso-google-merchant-feed' ), $def_value ) . ' ';

					$text .= __( 'When a product is updated, or the cache expires, the XML file cache is automatically refreshed.',
						'wpsso-google-merchant-feed' ) . ' ';

					break;

				case 'tooltip-gmf_feed_format':
				case 'tooltip-gmf_inventory_format':

					$def_value = $this->p->opt->get_defaults( str_replace( 'tooltip-', '', $msg_key ) );
					$def_label = $this->p->cf[ 'form' ][ 'feed_formats' ][ $def_value ];

					$text = sprintf( __( 'Choose the XML format for URLs shown in the settings page (default is %s).', 'wpsso-google-merchant-feed' ), $def_label ) . ' ';

					break;

				case 'tooltip-gmf_inventory_exp_secs':

					$def_value = $this->p->opt->get_defaults( 'gmf_inventory_exp_secs' );

					$text = sprintf( __( 'The XML file cache expiration time in seconds (default is %s).',
						'wpsso-google-merchant-feed' ), $def_value ) . ' ';

					$text .= __( 'When a product is updated, or the cache expires, the XML file cache is automatically refreshed.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'You can decrease the expiration time if you need to refresh product quantities in the XML file more frequently.',
						'wpsso-google-merchant-feed' ) . ' ';

					break;

				case 'tooltip-gmf_merchant_id':

					$text = __( 'When merchants set up a payments profile, Google assigns them a unique numeric code called a Merchant ID.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= sprintf( __( '<a href="%s">To find your Merchant ID follow these steps</a>: Sign in to your payments profile, at the top click Settings, find "Public merchant profile" for your merchant ID.', 'wpsso-google-merchant-feed' ), __( 'https://support.google.com/paymentscenter/answer/7163092', 'wpsso-google-merchant-feed' ) ) . ' ';

					$text .= sprintf( __( 'See the <a href="%1$s">%2$s</a> documentation for additional details.', 'wpsso-google-merchant-feed' ),
						__( 'https://support.google.com/merchants/answer/7677785', 'wpsso-google-merchant-feed' ),
						__( 'About the inventory feed specification', 'wpsso-google-merchant-feed' ) );

					break;

				case 'tooltip-gmf_store_code':

					$text = __( 'The store code from Google\'s Business Profiles.', 'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'The value is case-sensitive and must match the store code in your Google Business Profile.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= sprintf( __( 'See the <a href="%1$s">%2$s</a> or the <a href="%3$s">%4$s</a> documentation for additional details.',
						'wpsso-google-merchant-feed' ),
						__( 'https://support.google.com/merchants/answer/7677785', 'wpsso-google-merchant-feed' ),
						__( 'About the inventory feed specification', 'wpsso-google-merchant-feed' ),
						__( 'https://support.google.com/merchants/topic/9671633', 'wpsso-google-merchant-feed' ),
						__( 'Store errors in local product inventory feed', 'wpsso-google-merchant-feed' ) );

					break;

				case ( 0 === strpos( $msg_key, 'tooltip-gmf_feed_xml_' ) ? true : false ):

					$metabox_title = _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' );

					$text = sprintf( __( 'The %1$s includes all public WordPress post objects (ie. posts, pages, and custom post types) with an Open Graph type of "product" and a language of %2$s (ie. locale "%3$s").', 'wpsso-google-merchant-feed' ), $metabox_title, $info[ 'native_name' ], $info[ 'locale' ] );

					break;

				case ( 0 === strpos( $msg_key, 'tooltip-gmf_inventory_xml_' ) ? true : false ):

			 		$metabox_title = _x( 'Google Merchant Inventory XML', 'metabox title', 'wpsso-google-merchant-feed' );

					$text = sprintf( __( 'The %1$s includes all public WordPress post objects (ie. posts, pages, and custom post types) with an Open Graph type of "product" and a language of %2$s (ie. locale "%3$s").', 'wpsso-google-merchant-feed' ), $metabox_title, $info[ 'native_name' ], $info[ 'locale' ] );

					break;

			}	// End of 'tooltip' switch.

			return $text;
		}

		public function filter_messages_tooltip_meta( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-meta-gmf_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				/*
				 * Document SSO > Edit Media tab.
				 */
				case 'tooltip-meta-gmf_img_id':		// Image ID.

					$text = __( 'A customized image ID for the Google merchant feed XML.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'The default value is inherited from the Schema markup or priority image.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= '<em>' . __( 'This option is disabled if a custom image URL is entered.',
						'wpsso-google-merchant-feed' ) . '</em>';

				 	break;

				case 'tooltip-meta-gmf_img_url':	// or an Image URL.

					$text = __( 'A customized image URL (instead of an image ID) for the Google merchant feed XML.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= __( 'The default value is inherited from the Schema markup or priority image.',
						'wpsso-google-merchant-feed' ) . ' ';

					$text .= '<em>' . __( 'This option is disabled if a custom image ID is selected.',
						'wpsso-google-merchant-feed' ) . '</em>';

				 	break;

			}	// End of 'tooltip-meta' switch.

			return $text;
		}
	}
}
