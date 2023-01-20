<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfActions' ) ) {

	class WpssoGmfActions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoGmf class object.

		/*
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

			$this->p->util->add_plugin_actions( $this, array(
				'check_head_info'    => 3,
				'refresh_post_cache' => 2,
			) );

			if ( is_admin() ) {

				$this->p->util->add_plugin_actions( $this, array(
					'load_setting_page_refresh_feed_xml_cache' => 4,
				) );
			}
		}

		/*
		 * The post, term, or user has an ID, is public, and (in the case of a post) the post status is published.
		 */
		public function action_check_head_info( array $head_info, array $mod, $ref_url ) {

			$is_product = isset( $head_info[ 'og:type' ] ) && 'product' === $head_info[ 'og:type' ] ? true : false;

			if ( $is_product && ! $mod[ 'is_archive' ] ) {	// Exclude the shop page.

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'getting open graph array for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
				}

				$this->p->util->maybe_set_ref( $ref_url, $mod, __( 'checking google merchant feeds', 'wpsso-google-merchant-feed' ) );

				$mt_og = $this->p->og->get_array( $mod, $size_names = 'wpsso-gmf', $md_pre = array( 'gmf', 'schema', 'og' ) );

				$this->p->util->maybe_unset_ref( $ref_url );

				if ( empty( $mt_og[ 'product:offers' ] ) ) {

					$image_url = $this->get_product_image_url( $mt_og, $mod, $ref_url );

				} elseif ( is_array( $mt_og[ 'product:offers' ] ) ) {

					foreach ( $mt_og[ 'product:offers' ] as $num => $mt_offer ) {

						$image_url = $this->get_product_image_url( $mt_offer, $mod, $ref_url );
					}
				}
			}
		}

		/*
		 * Once the post cache is cleared and refreshed, clear the feed XML.
		 */
		public function action_refresh_post_cache( $post_id, $mod ) {

			$og_type = $this->p->og->get_mod_og_type_id( $mod );

			if ( 'product' === $og_type ) {

				$locale = SucomUtil::get_locale( $mod );

				$xml = WpssoGmfXml::clear_cache( $locale );
			}
		}

		public function action_load_setting_page_refresh_feed_xml_cache( $pagehook, $menu_id, $menu_name, $menu_lib ) {

			$notice_msg = '';

			$notice_msg = $this->a->filters->filter_cache_refreshed_notice( $notice_msg );

			$this->p->notice->upd( $notice_msg );
		}

		private function get_product_image_url( $mt_data, $mod, $canonical_url ) {

			$mt_images = array();

			if ( isset( $mt_data[ 'og:image' ] ) && is_array( $mt_data[ 'og:image' ] ) ) {

				$mt_images = $mt_data[ 'og:image' ];

			} elseif ( ! empty( $mt_data[ 'product:retailer_item_id' ] ) && is_numeric( $mt_data[ 'product:retailer_item_id' ] ) ) {

				$post_id   = $mt_data[ 'product:retailer_item_id' ];
				$mod       = $this->p->post->get_mod( $post_id );	// Redefine the $mod array for the variation post ID.
				$max_nums  = $this->p->util->get_max_nums( $mod, 'og' );

				$this->p->util->maybe_set_ref( $canonical_url, $mod, __( 'getting google merchant feeds images', 'wpsso-google-merchant-feed' ) );

				$mt_images = $this->p->media->get_all_images( $max_nums[ 'og_img_max' ], $size_names = 'wpsso-gmf', $mod,
					$md_pre = array( 'gmf', 'schema', 'og' ) );

				$this->p->util->maybe_unset_ref( $canonical_url );
			}

			if ( is_array( $mt_images ) ) {	// Just in case.

				foreach ( $mt_images as $mt_image ) {

					if ( $image_url = SucomUtil::get_first_og_image_url( $mt_image ) ) {

						return $image_url;
					}
				}

				/*
				 * An is_admin() test is required to make sure the WpssoMessages class is available.
				 */
				if ( $this->p->notice->is_admin_pre_notices() ) {

					if ( ! empty( $mod[ 'post_type_label_single' ] ) ) {

						$this->p->util->maybe_set_ref( $canonical_url, $mod, __( 'checking google merchant feeds images', 'wpsso-google-merchant-feed' ) );

						/*
						 * See https://support.google.com/merchants/answer/7052112.
						 * See https://support.google.com/merchants/answer/6324350.
						 */
						$notice_msg = sprintf( __( 'A Google merchant feed XML %1$s attribute could not be generated for %2$s ID %3$s.', 'wpsso-google-merchant-feed' ), '<code>image_link</code>', $mod[ 'post_type_label_single' ], $mod[ 'id' ] ) . ' ';

						$notice_msg .= sprintf( __( 'Google requires at least one %1$s attribute for each product variation in the Google merchant feed XML.', 'wpsso-google-merchant-feed' ), '<code>image_link</code>' );

						$notice_key = $mod[ 'name' ] . '-' . $mod[ 'id' ] . '-notice-missing-gmf-image';

						$this->p->notice->err( $notice_msg, null, $notice_key );

						$this->p->util->maybe_unset_ref( $canonical_url );
					}
				}
			}
		}
	}
}
