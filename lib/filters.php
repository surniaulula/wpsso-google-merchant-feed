<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfFilters' ) ) {

	class WpssoGmfFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.
		private $msgs;	// WpssoGmfFiltersMessages class object.

		/**
		 * Instantiated by WpssoGmf->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'plugin_image_sizes'     => 1,
				'cache_refreshed_notice' => 3,
			) );

			if ( is_admin() ) {

				require_once WPSSOGMF_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoGmfFiltersMessages( $plugin, $addon );

				$this->p->util->add_plugin_filters( $this, array(
					'plugin_image_sizes_rows'            => 2,	// SSO > Advanced Settings > Plugin Settings > Image Sizes tab.
					'metabox_sso_edit_media_schema_rows' => 5,
				) );
			}
		}

		public function filter_plugin_image_sizes( array $sizes ) {

			$sizes[ 'gmf' ] = array(	// Option prefix.
				'name'         => 'gmf',
				'label_transl' => _x( 'Google Merchant Feeds', 'option label', 'wpsso-google-merchant-feed' ),
			);

			return $sizes;
		}

		/**
		 * SSO > Advanced Settings > Plugin Settings > Image Sizes tab.
		 */
		public function filter_plugin_image_sizes_rows( $table_rows, $form ) {

			$table_rows[ 'gmf_img_size' ] = '' .
				$form->get_th_html( _x( 'Google Merchant Feeds', 'option label', 'wpsso-google-merchant-feed' ),
					$css_class = '', $css_id = 'gmf_img_size' ) . 
				'<td>' . $form->get_input_image_dimensions( 'gmf_img' ) . '</td>';

			return $table_rows;
		}

		public function filter_metabox_sso_edit_media_schema_rows( $table_rows, $form, $head_info, $mod, $canonical_url ) {

			if ( ! $mod[ 'is_public' ] ) {

				return $table_rows;
			}

			$is_product      = isset( $head_info[ 'og:type' ] ) && 'product' === $head_info[ 'og:type' ] ? true : false;
			$schema_disabled = $this->p->util->is_schema_disabled();
			$schema_msg      = $this->p->msgs->maybe_schema_disabled();
			$media_info      = array( 'pid' => '' );
			$media_request   = array( 'pid' );

			if ( $is_product ) {

				$this->p->util->maybe_set_ref( $canonical_url, $mod, __( 'getting google merchant feeds image', 'wpsso' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-gmf', $media_request, $mod, $md_pre = array( 'schema', 'og' ) );

			} elseif ( ! $schema_disabled ) {

				$this->p->util->maybe_set_ref( $canonical_url, $mod, __( 'getting schema 1:1 image', 'wpsso' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-schema-1x1', $media_request, $mod, $md_pre = array( 'og' ) );

			} else {

				$this->p->util->maybe_set_ref( $canonical_url, $mod, __( 'getting open graph image', 'wpsso' ) );

				$media_info = $this->p->media->get_media_info( $size_name = 'wpsso-opengraph', $media_request, $mod, $md_pre = array( 'none' ) );
			}

			$this->p->util->maybe_unset_ref( $canonical_url );

			$form_rows = array(
				'subsection_gmf' => array(
					'tr_class' => 'hide_og_type hide_og_type_product',
					'td_class' => 'subsection top',
					'header'   => 'h4',
					'label'    => _x( 'Google Merchant Feeds (Main Product)', 'metabox title', 'wpsso-google-merchant-feed' )
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

		public function filter_cache_refreshed_notice( $notice_msg, $user_id, $read_cache = false ) {

			$xml_count = 0;

			$locale_names = SucomUtil::get_available_feed_locale_names();	// Uses a local static cache.

			foreach ( $locale_names as $locale => $native_name ) {

				switch_to_locale( $locale );	// Calls an action to clear the SucomUtil::get_locale() cache. 

				$xml = WpssoGmfXml::get( $read_cache );

				$xml_count++;
			}

			restore_current_locale();	// Calls an action to clear the SucomUtil::get_locale() cache.

			$notice_msg .= sprintf( __( 'The Google Merchant Feeds XML cache for %1$d locales has been refreshed.', 'wpsso-google-merchant-feed' ), $xml_count ) . ' ';

			return $notice_msg;
		}
	}
}
