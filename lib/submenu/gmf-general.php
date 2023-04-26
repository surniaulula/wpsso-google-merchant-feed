<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfSubmenuGmfGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoGmfSubmenuGmfGeneral extends WpssoAdmin {

		private $doing_task = false;

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/*
		 * Add settings page filters and actions hooks.
		 *
		 * Called by WpssoAdmin->load_setting_page() after the 'wpsso-action' query is handled.
		 */
		protected function add_plugin_hooks() {

			$this->doing_task = $this->p->util->cache->doing_task();

			$this->p->util->add_plugin_filters( $this, array(
				'form_button_rows' => 1,	// Form buttons for this settings page.
			), PHP_INT_MAX );			// Run filter last to remove all form buttons.
		}

		public function filter_form_button_rows( $form_button_rows ) {

			$show_save_settings = empty( $this->p->avail[ 'ecom' ][ 'any' ] ) ? false : true;

			if ( $this->doing_task ) {

				$form_button_rows = array();

			} elseif ( $show_save_settings ) {	// E-commerce plugin active.

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

		/*
		 * Called by WpssoAdmin->load_setting_page() after the 'wpsso-action' query is handled.
		 */
		protected function add_meta_boxes() {

			foreach ( array(
				'feed'      => _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' ),
				'inventory' => _x( 'Google Merchant Inventory XML', 'metabox title', 'wpsso-google-merchant-feed' ),
			) as $metabox_id => $metabox_title ) {

				$metabox_screen  = $this->pagehook;
				$metabox_context = 'normal';
				$metabox_prio    = 'default';
				$callback_args   = array(	// Second argument passed to the callback function / method.
					'page_id'       => $this->menu_id,
					'metabox_id'    => $metabox_id,
					'metabox_title' => $metabox_title,
				);

				add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
					array( $this, 'show_metabox_table' ), $metabox_screen,
						$metabox_context, $metabox_prio, $callback_args );
			}
		}

		public function get_table_rows( $metabox_id, $tab_key, $metabox_title = '' ) {

			$table_rows = array();

			if ( $this->doing_task ) {

				$this->add_table_rows_doing_task( $table_rows, $metabox_title );

				return $table_rows;
			}

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'gmf-general-feed':

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

				case 'gmf-general-inventory':

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

		private function add_table_rows_doing_task( &$table_rows, $metabox_title ) {	// Pass by reference is OK.

			$task_name_transl = _x( $this->doing_task, 'task name', 'wpsso' );

			$table_rows[ 'wpssogmf_disabled' ] = '<tr><td align="center">' .
				'<p class="status-msg">' . sprintf( __( 'A background task to %s is currently running.',
					'wpsso-google-merchant-feed' ), $task_name_transl ) . '</p>' .
				'<p class="status-msg">' . sprintf( __( '%s is currently unavailable pending completion of a maintenance task.',
					'wpsso-google-merchant-feed' ), $metabox_title ) . '</p>' .
				'</td></tr>';
		}
	}
}
