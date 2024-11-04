<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfSubmenuGoogleMerchant' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoGmfSubmenuGoogleMerchant extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;

			$this->menu_metaboxes = array(
				'feed'      => _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' ),
			 	'inventory' => _x( 'Google Merchant Inventory XML', 'metabox title', 'wpsso-google-merchant-feed' ),
			);
		}

		protected function add_form_buttons( &$form_button_rows ) {

			/*
			 * Remove all buttons if a cache refresh is running.
			 */
			if ( $this->p->util->cache->is_refresh_running() ) {

				return array();
			}

			/*
			 * Add a "Refresh XML Cache" button.
			 */
			$form_button_rows[ 0 ][ 'refresh_feed_xml_cache' ] = _x( 'Refresh XML Cache', 'submit button', 'wpsso-google-merchant-feed' );
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );
			$is_public  = get_option( 'blog_public' );

			if ( $this->p->util->cache->is_refresh_running() ) {

				$task_name_transl = _x( 'refresh the cache', 'task name', 'wpsso-google-merchant-feed' );

				$table_rows[ 'wpssogmf_disabled' ] = '<tr><td align="center">' .
					'<p class="status-msg">' . sprintf( __( 'A background task to %s is currently running.',
						'wpsso-google-merchant-feed' ), $task_name_transl ) . '</p>' .
					'<p class="status-msg">' . sprintf( __( '%s is currently unavailable pending completion of a cache refresh task.',
						'wpsso-google-merchant-feed' ), $args[ 'metabox_title' ] ) . '</p>' .
					'</td></tr>';

				return $table_rows;

			} elseif ( ! $is_public ) {

				$settings_url         = get_admin_url( $blog_id = null, 'options-reading.php' );
				$noindex_label_transl = _x( 'No Index', 'option label', 'wpsso' );
				$directives           = WpssoUtilRobots::get_default_directives();

				if ( ! empty( $directives[ 'noindex' ] ) ) {	// Just in case.

					$table_rows[ 'wpssogmf_disabled' ] = '<tr><td align="center">' .
						'<p class="status-msg">' . sprintf( __( 'The WordPress <a href="%s">Search Engine Visibility</a> option is set to discourage search engines from indexing this site.', 'wpsso-google-merchant-feed' ), $settings_url ) . '</p>' .
						'<p class="status-msg">' . sprintf( __( '%1$s is currently unavailable since all products are marked as %2$s by default.',
							'wpsso-google-merchant-feed' ), $args[ 'metabox_title' ], $noindex_label_transl ) . '</p>' .
						'</td></tr>';

					return $table_rows;
				}
			}

			switch ( $match_rows ) {

				case 'google-merchant-feed':

					/*
					 * See https://support.google.com/merchants/answer/7052112?hl=en#shipping_and_returns.
					 */
					$table_rows[ 'gmf_add_shipping' ] = $this->form->get_tr_hide( $in_view = 'basic', 'gmf_add_shipping' ) .
						$this->form->get_th_html( _x( 'Include Shipping', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_add_shipping' ) .
						'<td>' . $this->form->get_checkbox( 'gmf_add_shipping' ) . ' ' .
							_x( '(not recommended - see help for warning)', 'option comment', 'wpsso-google-merchant-feed' ) . '</td>';

					$table_rows[ 'gmf_feed_exp_secs' ] = $this->form->get_tr_hide( $in_view = 'basic', 'gmf_feed_exp_secs' ) .
						$this->form->get_th_html( _x( 'XML Cache Expiration', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_feed_exp_secs' ) .
						'<td>' . $this->form->get_input( 'gmf_feed_exp_secs', 'medium' ) . ' ' .
							_x( 'seconds', 'option comment', 'wpsso-google-merchant-feed' ) . '</td>';

					$table_rows[ 'gmf_feed_format' ] = $this->form->get_tr_hide( $in_view = 'basic', 'gmf_feed_format' ) .
						$this->form->get_th_html( _x( 'XML Format', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_feed_format' ) .
						'<td>' . $this->form->get_select( 'gmf_feed_format', $this->p->cf[ 'form' ][ 'feed_formats' ], 'medium' ) . '</td>';

					$locale_names = SucomUtilWP::get_available_feed_locale_names();

					foreach ( $locale_names as $locale => $native_name ) {

						$feed_type   = 'feed';
						$feed_format = $this->p->options[ 'gmf_' . $feed_type . '_format' ];
						$url         = WpssoGmfRewrite::get_url( $locale, $feed_type, $feed_format );
						$css_id      = SucomUtil::sanitize_css_id( 'gmf_feed_xml_' . $locale );
						$xml_info    = array();

						if ( ! SucomUtil::get_const( 'WPSSOGMF_XML_INFO_DISABLE', false ) ) {

							$xml         = WpssoGmfXml::get( $locale, $feed_type, $feed_format );
							$item_count  = substr_count( $xml, 'atom' === $feed_format? '<entry>' : '<item>' );
							$img_count   = substr_count( $xml, '<g:image_link>' );
							$addl_count  = substr_count( $xml, '<g:additional_image_link>' );
							$xml_size    = number_format( ( strlen( $xml ) / 1024 ) );	// XML size in KB.

							unset( $xml );

							$xml_info = array(
								sprintf( _x( '%s feed items', 'option comment', 'wpsso-google-merchant-feed' ), $item_count ),
								sprintf( _x( '%s image links', 'option comment', 'wpsso-google-merchant-feed' ), $img_count ),
								sprintf( _x( '%s additional image links', 'option comment', 'wpsso-google-merchant-feed' ), $addl_count ),
								sprintf( _x( '%s KB file size', 'option comment', 'wpsso-google-merchant-feed' ), $xml_size ),
							);
						}

						$table_rows[ $css_id ] = '' .
							$this->form->get_th_html( $native_name, $css_class = '', $css_id,
								array( 'locale' => $locale, 'native_name' => $native_name ) ) .
							'<td>' . $this->form->get_no_input_clipboard( $url ) .
							'<p class="status-msg left">' . implode( '; ', $xml_info ) . '</p></td>';
					}

					break;

				case 'google-merchant-inventory':

			 		$metabox_title = _x( 'Google Merchant Inventory XML', 'metabox title', 'wpsso-google-merchant-feed' );

					if ( empty( $this->p->avail[ 'ecom' ][ 'any' ] ) ) {	// No e-commerce plugin active.

						$table_rows[ 'wpssogmf_inventory_disabled' ] = '<tr><td align="center">' .
							'<p class="status-msg">' . __( 'An e-commerce plugin is required to manage product inventory quantities.',
								'wpsso-google-merchant-feed' ) . '</p>' .
							'<p class="status-msg">' . sprintf( __( '%s URLs are unavailable pending the activation of an e-commerce plugin.',
								'wpsso-google-merchant-feed' ), $metabox_title ) . '</p>' .
							'</td></tr>';

						return $table_rows;
					}

					/*
					 * The merchant ID of this retailer.
					 *
					 * See https://support.google.com/merchants/answer/7677785.
					 */
					$table_rows[ 'gmf_merchant_id' ] = '' .
						$this->form->get_th_html( _x( 'Google Merchant ID', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_merchant_id' ) .
						'<td>' . $this->form->get_input( 'gmf_merchant_id', 'medium' ) . '</td>';

					/*
					 * Store Code: The store identifier from Google's Business Profiles.
					 *
					 * See https://support.google.com/merchants/answer/7677785.
					 * See https://support.google.com/business/answer/6300665.
					 * See https://www.google.com/business/.
					 *
					 * A unique alphanumeric identifier for each local business. This attribute is
					 * case-sensitive and must match the store codes that you submitted in your Business
					 * Profiles.
					 */
					$table_rows[ 'gmf_store_code' ] = '' .
						$this->form->get_th_html( _x( 'Google Store Code', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_store_code' ) .
						'<td>' . $this->form->get_input( 'gmf_store_code', 'medium' ) . '</td>';

					$table_rows[ 'gmf_inventory_exp_secs' ] = $this->form->get_tr_hide( $in_view = 'basic', 'gmf_inventory_exp_secs' ) .
						$this->form->get_th_html( _x( 'XML Cache Expiration', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_inventory_exp_secs' ) .
						'<td>' . $this->form->get_input( 'gmf_inventory_exp_secs', 'medium' ) . ' ' .
							_x( 'seconds', 'option comment', 'wpsso-google-merchant-feed' ) . '</td>';

					$table_rows[ 'gmf_inventory_format' ] = $this->form->get_tr_hide( $in_view = 'basic', 'gmf_inventory_format' ) .
						$this->form->get_th_html( _x( 'XML Format', 'option label', 'wpsso-google-merchant-feed' ),
							$css_class = '', $css_id = 'gmf_inventory_format' ) .
						'<td>' . $this->form->get_select( 'gmf_inventory_format', $this->p->cf[ 'form' ][ 'feed_formats' ], 'medium' ) . '</td>';

					if ( empty( $this->p->options[ 'gmf_merchant_id' ] ) ||
						empty( $this->p->options[ 'gmf_store_code' ] ) ) {

						$table_rows[ 'wpssogmf_inventory_disabled' ] = '<tr><td align="center" colspan="2">' .
							'<p class="status-msg">' . __( 'The Google Merchant ID and Store Code are required to create inventory XML.',
								'wpsso-google-merchant-feed' ) . '</p>' .
							'<p class="status-msg">' . sprintf( __( '%s URLs are unavailable pending Google Merchant ID and Store Code values.',
								'wpsso-google-merchant-feed' ), $metabox_title ) . '</p>' .
							'</td></tr>';

					} else {

						$locale_names = SucomUtilWP::get_available_feed_locale_names();

						foreach ( $locale_names as $locale => $native_name ) {

							$feed_type   = 'inventory';
							$feed_format = $this->p->options[ 'gmf_' . $feed_type . '_format' ];
							$url         = WpssoGmfRewrite::get_url( $locale, $feed_type, $feed_format );
							$css_id      = SucomUtil::sanitize_css_id( 'gmf_inventory_xml_' . $locale );
							$xml_info    = array();

							if ( ! SucomUtil::get_const( 'WPSSOGMF_XML_INFO_DISABLE', false ) ) {

								$xml         = WpssoGmfXml::get( $locale, $feed_type, $feed_format );
								$item_count  = substr_count( $xml, 'atom' === $feed_format? '<entry>' : '<item>' );
								$xml_size    = number_format( ( strlen( $xml ) / 1024 ) );	// XML size in KB.

								unset( $xml );

								$xml_info = array(
									sprintf( _x( '%s inventory items', 'option comment', 'wpsso-google-merchant-feed' ), $item_count ),
									sprintf( _x( '%s KB file size', 'option comment', 'wpsso-google-merchant-feed' ), $xml_size ),
								);
							}

							$table_rows[ $css_id ] = '' .
								$this->form->get_th_html( $native_name, $css_class = '', $css_id,
									array( 'locale' => $locale, 'native_name' => $native_name ) ) .
								'<td>' . $this->form->get_no_input_clipboard( $url ) .
								'<p class="status-msg left">' . implode( '; ', $xml_info ) . '</p></td>';
						}
					}

					break;
			}

			return $table_rows;
		}
	}
}
