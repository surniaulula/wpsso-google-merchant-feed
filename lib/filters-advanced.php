<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFiltersAdvanced' ) ) {

	class WpssoGmfFiltersAdvanced {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.

		/*
		 * Instantiated by WpssoGmfFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( is_admin() ) {

				$this->p->util->add_plugin_filters( $this, array(
					'plugin_image_sizes_rows' => 4,
				) );
			}
		}

		/*
		 * SSO > Advanced Settings > Plugin Settings > Image Sizes tab.
		 */
		public function filter_plugin_image_sizes_rows( $table_rows, $form, $network, $pp ) {

			$table_rows[ 'gmf_img_size' ] = '' .
				$form->get_th_html( _x( 'Google Merchant Feed XML', 'option label', 'wpsso-google-merchant-feed' ),
					$css_class = '', $css_id = 'gmf_img_size' ) . ( $pp ?
				'<td>' . $form->get_input_image_dimensions( 'gmf_img' ) . '</td>' :
				'<td class="blank">' . $form->get_no_input_image_dimensions( 'gmf_img' ) . '</td>' );

			return $table_rows;
		}
	}
}
