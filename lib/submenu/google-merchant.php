<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
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

			/* TODO Add an inventory metabox:
			 *
			 * 'inventory' => _x( 'Google Merchant Inventory XML', 'metabox title', 'wpsso-google-merchant-feed' ),
			 */
			$this->menu_metaboxes = array(
				'feedxml' => _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' ),
			);
		}

		protected function add_settings_page_callbacks() {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 'form_button_rows' => 1 ), PHP_INT_MAX );
		}

		public function filter_form_button_rows( $form_button_rows ) {

			if ( $this->p->util->cache->is_refresh_running() ) {

				return array();
			}
			
			if ( ! empty( $this->p->avail[ 'ecom' ][ 'any' ] ) ) {	// E-commerce plugin is active.

				/*
				 * Remove the "Change to View" button from this settings page.
				 */
				if ( isset( $form_button_rows[ 0 ] ) ) {	// Just in case.

					$form_button_rows[ 0 ] = SucomUtil::preg_grep_keys( '/^change_show_options/', $form_button_rows[ 0 ], $invert = true );
				}

				$form_button_rows[ 0 ][ 'refresh_feed_xml_cache' ] = _x( 'Refresh XML Cache', 'submit button', 'wpsso-google-merchant-feed' );

			} else {

				/*
				 * Remove all action buttons from this settings page and add a "Refresh XML Cache" button.
				 */
				$form_button_rows = array(
					array(
						'refresh_feed_xml_cache' => _x( 'Refresh XML Cache', 'submit button', 'wpsso-google-merchant-feed' ),
					),
				);
			}

			return $form_button_rows;
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );

			if ( $this->p->util->cache->is_refresh_running() ) {

				$task_name_transl = _x( 'refresh the cache', 'task name', 'wpsso' );

				$table_rows[ 'wpssogmf_disabled' ] = '<tr><td align="center">' .
					'<p class="status-msg">' . sprintf( __( 'A background task to %s is currently running.',
						'wpsso-google-merchant-feed' ), $task_name_transl ) . '</p>' .
					'<p class="status-msg">' . sprintf( __( '%s is currently unavailable pending completion of a cache refresh task.',
						'wpsso-google-merchant-feed' ), $args[ 'metabox_title' ] ) . '</p>' .
					'</td></tr>';

				return $table_rows;
			}

			switch ( $match_rows ) {

				case 'google-merchant-feedxml':

					$locale_names = SucomUtil::get_available_feed_locale_names();

					foreach ( $locale_names as $locale => $native_name ) {

						$url = WpssoGmfRewrite::get_url( $locale );
						$xml = WpssoGmfXml::get( $locale );

						$item_count = substr_count( $xml, '<item>' );
						$img_count  = substr_count( $xml, '<g:image_link>' );
						$addl_count = substr_count( $xml, '<g:additional_image_link>' );
						$xml_size   = number_format( ( strlen( $xml ) / 1024 ) );	// XML size in KB.

						$table_rows[ 'gmf_url_' . $locale ] = '' .
							$this->form->get_th_html( $native_name, $css_class = 'medium' ) .
							'<td>' . $this->form->get_no_input_clipboard( $url ) .
							'<p class="status-msg left">' .
							sprintf( _x( '%1$s feed items, %2$s image links, and %3$s additional image links.',
								'option comment', 'wpsso-google-merchant-feed' ), $item_count, $img_count, $addl_count ) .
							'</p>' .
							'</td>';
					}

					break;

				case 'google-merchant-inventory':

					if ( empty( $this->p->avail[ 'ecom' ][ 'any' ] ) ) {	// No e-commerce plugin active.

						$table_rows[ 'wpssogmf_inventory_disabled' ] = '<tr><td align="center">' .
							'<p class="status-msg">' . __( 'An e-commerce plugin is required to manage product inventory quantities.',
								'wpsso-google-merchant-feed' ) . '</p>' .
							'<p class="status-msg">' . sprintf( __( '%s is unavailable pending the activation of an e-commerce plugin.',
								'wpsso-google-merchant-feed' ), $metabox_title ) . '</p>' .
							'</td></tr>';

						return $table_rows;
					}

					/*
					 * Store Code: The store identifier from Google's Business Profiles.
					 *
					 * See https://www.google.com/business/.
					 * See https://support.google.com/business/answer/6300665?hl=en.
					 *
					 * A unique alphanumeric identifier for each local business. This attribute is
					 * case-sensitive and must match the store codes that you submitted in your Business
					 * Profiles.
					 */
					$table_rows[ 'gmf_store_code' ] = '' .
						$this->form->get_th_html( _x( 'Store Code', 'option label', 'wpsso' ),
							$css_class = 'medium', $css_id = 'gmf_store_code' ) .
						'<td>' . $this->form->get_input( 'gmf_store_code', 'short' ) . '</td>';

					if ( ! empty( $this->p->options[ 'gmf_store_code' ] ) ) {

						$locale_names = SucomUtil::get_available_feed_locale_names();

						foreach ( $locale_names as $locale => $native_name ) {

							$url = WpssoGmfRewrite::get_url( $locale, $content = 'inventory' );

							$table_rows[ 'gmf_url_' . $locale ] = '' .
								$this->form->get_th_html( $native_name, $css_class = 'medium' ) .
								'<td>' . $this->form->get_no_input_clipboard( $url ) . '</td>';
						}
					}

					break;
			}

			return $table_rows;
		}
	}
}
