<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFiltersEdit' ) ) {

	class WpssoGmfFiltersEdit {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.

		/*
		 * Instantiated by WpssoGmfFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'mb_sso_edit_media_schema_rows' => 5,
			) );
		}

		public function filter_mb_sso_edit_media_schema_rows( $table_rows, $form, $head_info, $mod, $args ) {

			if ( ! $mod[ 'is_public' ] ) {

				return $table_rows;
			}

			$is_product    = isset( $head_info[ 'og:type' ] ) && 'product' === $head_info[ 'og:type' ] ? true : false;
			$media_info    = array( 'pid' => '' );
			$media_request = array( 'pid' );

			if ( $is_product ) {

				$this->p->util->maybe_set_ref( $args[ 'canonical_url' ], $mod,
					__( 'getting google merchant feed image', 'wpsso-google-merchant-feed' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-gmf', $media_request, $mod, $md_pre = array( 'schema', 'og' ) );

			} elseif ( ! $this->p->util->is_schema_disabled() ) {

				$this->p->util->maybe_set_ref( $args[ 'canonical_url' ], $mod,
					__( 'getting schema 1:1 image', 'wpsso-google-merchant-feed' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-schema-1x1', $media_request, $mod, $md_pre = array( 'og' ) );

			} else {

				$this->p->util->maybe_set_ref( $args[ 'canonical_url' ], $mod,
					__( 'getting open graph image', 'wpsso-google-merchant-feed' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-opengraph', $media_request, $mod, $md_pre = array( 'none' ) );
			}

			$this->p->util->maybe_unset_ref( $args[ 'canonical_url' ] );

			$form_rows = array(
				'subsection_gmf' => array(
					'tr_class' => 'hide_og_type hide_og_type_product',
					'td_class' => 'subsection',
					'header'   => 'h4',
					'label'    => _x( 'Google Merchant Feed XML (Main Product)', 'metabox title', 'wpsso-google-merchant-feed' )
				),
				'gmf_img_info' => array(
					'tr_class'  => 'hide_og_type hide_og_type_product',
					'table_row' => '<td colspan="2">' . $this->p->msgs->get( 'info-gmf-img', array( 'mod' => $mod ) ) . '</td>',
				),
				'gmf_img_id' => array(
					'tr_class' => 'hide_og_type hide_og_type_product',
					'th_class' => 'medium',
					'label'    => _x( 'Image ID', 'option label', 'wpsso-google-merchant-feed' ),
					'tooltip'  => 'meta-gmf_img_id',
					'content'  => $form->get_input_image_upload( 'gmf_img', $media_info[ 'pid' ] ),
				),
				'gmf_img_url' => array(
					'tr_class' => 'hide_og_type hide_og_type_product',
					'th_class' => 'medium',
					'label'    => _x( 'or an Image URL', 'option label', 'wpsso-google-merchant-feed' ),
					'tooltip'  => 'meta-gmf_img_url',
					'content'  => $form->get_input_image_url( 'gmf_img' ),
				),
			);

			return $form->get_md_form_rows( $table_rows, $form_rows, $head_info, $mod );
		}
	}
}
