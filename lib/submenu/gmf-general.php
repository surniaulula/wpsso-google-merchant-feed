<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
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

		/**
		 * Called by WpssoAdmin->load_setting_page() after the 'wpsso-action' query is handled.
		 *
		 * Add settings page filter and action hooks.
		 */
		protected function add_plugin_hooks() {

			$this->p->util->add_plugin_filters( $this, array(
				'form_button_rows' => 1,	// Form buttons for this settings page.
			), PHP_INT_MAX );			// Run filter last to remove all form buttons.
		}

		/**
		 * Remove all submit / action buttons from this settings page.
		 */
		public function filter_form_button_rows( $form_button_rows ) {

			return array();
		}

		/**
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

			$metabox_id = 'gmf';

			$tab_key = 'general';

			$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

			$table_rows = $this->get_table_rows( $metabox_id, $tab_key );

			$table_rows = apply_filters( $filter_name, $table_rows, $this->form, $network = false );

			$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'gmf-general':

					$locale_names = SucomUtil::get_available_feed_locale_names();

					foreach ( $locale_names as $locale => $native_name ) {

						$url  = WpssoGmfRewrite::get_url( $locale );

						$table_rows[ 'gmf_url_' . $locale ] = '' .
							$this->form->get_th_html( $native_name ) .
							'<td>' . $this->form->get_no_input_clipboard( $url ) . '</td>';
					}

					break;
			}

			return $table_rows;
		}
	}
}
