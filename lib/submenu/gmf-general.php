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
		 * Called by WpssoAdmin->load_setting_page() after the 'wpsso-action' query is handled.
		 *
		 * Add settings page filter and action hooks.
		 */
		protected function add_plugin_hooks() {

			$this->p->util->add_plugin_filters( $this, array(
				'form_button_rows' => 1,	// Form buttons for this settings page.
			), PHP_INT_MAX );			// Run filter last to remove all form buttons.
		}

		/*
		 * Remove all action buttons from this settings page and add a "Refresh Feed XML Cache" button.
		 */
		public function filter_form_button_rows( $form_button_rows ) {

			$form_button_rows = array(
				array(
					'refresh_feed_xml_cache' => _x( 'Refresh Feed XML Cache', 'submit button', 'wpsso-google-merchant-feed' ),
				),
			);

			return $form_button_rows;
		}

		/*
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$metabox_id      = 'gmf';
			$metabox_title   = _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_' . $metabox_id ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		public function show_metabox_gmf() {

			$metabox_id  = 'gmf';
			$tab_key     = 'general';
			$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );
			$table_rows  = $this->get_table_rows( $metabox_id, $tab_key );
			$table_rows  = apply_filters( $filter_name, $table_rows, $this->form, $network = false );

			$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'gmf-general':

					$locale_names = SucomUtil::get_available_feed_locale_names();
					$doing_task   = $this->p->util->cache->doing_task();

					if ( 'clear' === $doing_task || 'refresh' === $doing_task ) {
			
						$task_name_transl = _x( $doing_task, 'task name', 'wpsso' );
						$metabox_title    = _x( 'Google Merchant Feed XML', 'metabox title', 'wpsso-google-merchant-feed' );

						$table_rows[ 'wpssogmf_disabled' ] = '<tr><td align="center">' .
							'<p class="status-msg">' . sprintf( __( 'A background task to %s the cache is currently running.',
								'wpsso-google-merchant-feed' ), $task_name_transl ) . '</p>' .
							'<p class="status-msg">' . sprintf( __( '%s will be available when this task is complete.',
								'wpsso-google-merchant-feed' ), $metabox_title ) . '</p>' .
							'</td></tr>';

					} else {
					
						foreach ( $locale_names as $locale => $native_name ) {

							$url = WpssoGmfRewrite::get_url( $locale );
							$xml = WpssoGmfXml::get( $locale );
	
							$item_count = substr_count( $xml, '<item>' );
							$img_count  = substr_count( $xml, '<g:image_link>' );
							$addl_count = substr_count( $xml, '<g:additional_image_link>' );
							$xml_size   = number_format( ( strlen( $xml ) / 1024 ) );
	
							$table_rows[ 'gmf_url_' . $locale ] = '' .
								$this->form->get_th_html( $native_name, $css_class = 'medium' ) .
								'<td>' . $this->form->get_no_input_clipboard( $url ) .
								'<p class="status-msg left">' .
								sprintf( _x( '%1$s feed items, %2$s image links, %3$s addl image links, %4$s KB feed size.',
									'option comment', 'wpsso-google-merchant-feed' ),
										$item_count, $img_count, $addl_count, $xml_size ) .
								'</p>' .
								'</td>';
						}
					}

					break;
			}

			return $table_rows;
		}
	}
}
