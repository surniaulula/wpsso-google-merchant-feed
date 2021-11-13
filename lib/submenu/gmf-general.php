<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2021 Jean-Sebastien Morisset (https://wpsso.com/)
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
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$metabox_id      = 'gmf';
			$metabox_title   = _x( 'Google Merchant Feeds', 'metabox title', 'wpsso-google-merchant-feed' );
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

					$table_rows[] = '<td colspan="5">' . $this->p->msgs->get( 'info-gmf-urls' ) . '</td>';

					require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/translation-install.php';

					$translations  = wp_get_available_translations();	// Since WP v4.0.
					$avail_locales = SucomUtil::get_available_locales();	// Uses a local static cache.

					foreach ( $avail_locales as $locale ) {

						if ( isset( $translations[ $locale ][ 'native_name' ] ) ) {

							$native_name = $translations[ $locale ][ 'native_name' ];

						} elseif ( 'en_US' === $locale ) {

							$native_name = 'English (United States)';

						} else {

							$native_name = $locale;
						}

						$url = WpssoGmfRewrite::get_url( $locale );

						$table_rows[ 'gmf-url-' . $locale ] = '' . 
							$this->form->get_th_html( $native_name ) .
							'<td>' . $this->form->get_no_input_clipboard( $url ) . '</td>';
					}

					break;
			}

			return $table_rows;
		}
	}
}
