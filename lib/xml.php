<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;
use Vitalybaev\GoogleMerchant\Product\Shipping;

if ( ! class_exists( 'WpssoGmfXml' ) ) {

	class WpssoGmfXml {

		/**
		 * $read_cache = false when called by WpssoGmfFilters->filter_cache_refreshed_notice().
		 */
		static public function get( $read_cache = true ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$locale         = SucomUtil::get_locale();
			$cache_md5_pre  = 'wpsso_g_';
			$cache_type     = 'file';
			$cache_exp_secs = $wpsso->util->get_cache_exp_secs( $cache_md5_pre, $cache_type );
			$cache_salt     = __METHOD__ . '(locale:' . $locale . ')';
			$file_name_ext  = '.xml';

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'locale current = ' . $locale );
				$wpsso->debug->log( 'cache expire = ' . $cache_exp_secs );
				$wpsso->debug->log( 'cache salt = ' . $cache_salt );
			}

			if ( $read_cache && $cache_exp_secs ) {

				$xml = $wpsso->cache->get_cache_data( $cache_salt, $cache_type, $cache_exp_secs, $file_name_ext );

				if ( false !== $xml ) {

					return $xml;
				}
			}

			$site_title    = SucomUtil::get_site_name( $wpsso->options, $locale );
			$site_url      = SucomUtil::get_home_url( $wpsso->options, $locale );
			$site_desc     = SucomUtil::get_site_description( $wpsso->options, $locale );
			$col_og_type   = WpssoAbstractWpMeta::get_sortable_columns( $col_key = 'og_type' );
			$redir_enabled = $wpsso->util->is_redirect_disabled() ? false : true;

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'creating new feed' );
			}

			$feed = new Vitalybaev\GoogleMerchant\Feed( $site_title, $site_url, $site_desc );

			if ( ! empty( $col_og_type[ 'meta_key' ] ) ) {	// Just in case.

				$public_post_ids = WpssoPost::get_public_ids( array(
					'meta_key'   => $col_og_type[ 'meta_key' ],
					'meta_value' => 'product',
				) );

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log_arr( 'public_post_ids', $public_post_ids );
				}

				foreach ( $public_post_ids as $post_id ) {

					if ( $wpsso->util->robots->is_noindex( 'post', $post_id ) ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'skipping post id ' . $post_id . ': noindex is true' );
						}

						continue;

					/**
					 * If WPSSO is handling redirects, then exclude this post if it is being redirected.
					 */
					} elseif ( $redir_enabled && $wpsso->util->get_redirect_url( 'post', $post_id ) ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'skipping post id ' . $post_id . ': has redirect URL' );
						}

						continue;
					}

					$mod = $wpsso->post->get_mod( $post_id );

					if ( $mod[ 'is_archive' ] ) {	// Exclude the shop archive page.

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'skipping post id ' . $post_id . ': post is an archive page' );
						}

						continue;
					}

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'getting open graph array for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
					}

					$mt_og = $wpsso->og->get_array( $mod, $size_names = 'wpsso-gmf', $md_pre = array( 'gmf', 'schema', 'og' ) );

					if ( empty( $mt_og[ 'product:offers' ] ) ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'adding single offer for post id ' . $post_id );
						}

						self::add_feed_product( $feed, $mt_og );

					} elseif ( is_array( $mt_og[ 'product:offers' ] ) ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'adding multiple offers for post id ' . $post_id );
						}

						foreach ( $mt_og[ 'product:offers' ] as $num => $mt_offer ) {

							self::add_feed_product( $feed, $mt_og, $mt_offer );
						}
					}
				}
			}

			$xml = $feed->build();

			$wpsso->cache->save_cache_data( $cache_salt, $xml, $cache_type, $cache_exp_secs, $file_name_ext );

			return $xml;
		}

		static private function add_feed_product( &$feed, $mt_og, $mt_offer = null ) {

			$product = new Vitalybaev\GoogleMerchant\Product();

			self::add_product_data( $product, $mt_og, $dupe_check );

			if ( is_array( $mt_offer ) ) {

				self::add_product_data( $product, $mt_offer, $dupe_check );

				self::add_product_images( $product, $mt_offer );

			} else {

				self::add_product_images( $product, $mt_og );
			}

			$feed->addProduct( $product );
		}

		static private function add_product_data( &$product, $mt_data, &$dupe_check = array() ) {

			self::sanitize_mt_array( $mt_data );

			$names = array(
				'og:title'                  => 'setTitle',
				'og:description'            => 'setDescription',
				'og:url'                    => 'setLink',
				'og:url'                    => array( 'setAttribute', 'canonical_link', true ),	// Main product canonical URL.
				'product:item_group_id'     => array( 'setAttribute', 'item_group_id', false ),
				'product:retailer_item_id'  => 'setId',
				'product:title'             => 'setTitle',
				'product:description'       => 'setDescription',
				'product:url'               => 'setLink',
				'product:mfr_part_no'       => 'setMpn',
				'product:category'          => 'setGoogleCategory',	// Product category ID from Google product taxonomy.
				'product:retailer_category' => 'setProductType',	// String to organize bidding and reporting in Google Ads Shopping campaigns.
				'product:brand'             => 'setBrand',
				'product:availability'      => 'setAvailability',
				'product:condition'         => 'setCondition',
				'product:color'             => 'setColor',
				'product:material'          => 'setMaterial',
				'product:pattern'           => array( 'addAttribute', 'pattern', false ),
				'product:target_gender'     => array( 'addAttribute', 'gender', false ),
				'product:size'              => 'setSize',
				'product:size:type'         => array( 'addAttribute', 'size_type', false ),
				'product:price'             => 'setPrice',
				'product:sale_price'        => 'setSalePrice',
				'product:sale_price_dates'  => array( 'setAttribute', 'sale_price_effective_date', false ),
				'product:ean'               => array( 'addAttribute', 'gtin', false ),
				'product:gtin14'            => array( 'addAttribute', 'gtin', false ),
				'product:gtin13'            => array( 'addAttribute', 'gtin', false ),
				'product:gtin12'            => array( 'addAttribute', 'gtin', false ),
				'product:gtin8'             => array( 'addAttribute', 'gtin', false ),
				'product:gtin'              => array( 'addAttribute', 'gtin', false ),
				'product:isbn'              => array( 'addAttribute', 'gtin', false ),
				'product:upc'               => array( 'addAttribute', 'gtin', false ),
			);

			foreach ( $names as $mt_name => $method_name ) {

				if ( isset( $mt_data[ $mt_name ] ) && '' !== $mt_data[ $mt_name ] ) {

					$prop_name = '';
					$is_cdata  = false;

					if ( is_array( $method_name ) ) {

						list( $method_name, $prop_name, $is_cdata ) = $method_name;
					}

					if ( ! empty( $dupe_check[ $method_name ][ $prop_name ][ $mt_data[ $mt_name ] ] ) ) {

						continue;
					}

					if ( method_exists( $product, $method_name ) ) {	// Just in case.

						if ( $prop_name ) {

							$product->$method_name( $prop_name, $mt_data[ $mt_name ], $is_cdata );

							$dupe_check[ $method_name ][ $prop_name ][ $mt_data[ $mt_name ] ] = true;

						} else {

							$product->$method_name( $mt_data[ $mt_name ] );
						}
					}
				}
			}
		}

		static private function add_product_images( &$product, $mt_data ) {

			$mt_images = array();

			if ( isset( $mt_data[ 'og:image' ] ) && is_array( $mt_data[ 'og:image' ] ) ) {

				$mt_images = $mt_data[ 'og:image' ];

			} elseif ( ! empty( $mt_data[ 'product:retailer_item_id' ] ) && is_numeric( $mt_data[ 'product:retailer_item_id' ] ) ) {

				$wpsso =& Wpsso::get_instance();

				$post_id   = $mt_data[ 'product:retailer_item_id' ];
				$mod       = $wpsso->post->get_mod( $post_id );
				$max_nums  = $wpsso->util->get_max_nums( $mod, 'og' );
				$mt_images = $wpsso->media->get_all_images( $max_nums[ 'og_img_max' ], $size_names = 'wpsso-gmf', $mod,
					$md_pre = array( 'gmf', 'schema', 'og' ) );
			}

			if ( is_array( $mt_images ) ) {	// Just in case.

				$have_image = false;

				foreach ( $mt_images as $mt_image ) {

					if ( $image_url = SucomUtil::get_first_og_image_url( $mt_image ) ) {

						if ( ! $have_image ) {

							$product->setImage( $image_url );

							$have_image = true;

						} else {

							$product->addAdditionalImage( $image_url );
						}
					}
				}
			}
		}

		static private function sanitize_mt_array( &$mt_data ) {

			$wpsso =& Wpsso::get_instance();

			foreach ( array( 'product:availability', 'product:condition' ) as $mt_name ) {

				if ( isset( $mt_data[ $mt_name ] ) ) {

					$val = $mt_data[ $mt_name ];

					if ( isset( $wpsso->cf[ 'head' ][ 'gmf_content_map' ][ $mt_name ][ $val ] ) ) {

						$mt_data[ $mt_name ] = $wpsso->cf[ 'head' ][ 'gmf_content_map' ][ $mt_name ][ $val ];
					}
				}
			}

			foreach ( array( 'product:price', 'product:sale_price' ) as $mt_name ) {

				if ( isset( $mt_data[ $mt_name . ':amount' ] ) && isset( $mt_data[ $mt_name . ':currency' ] ) ) {

					$mt_data[ $mt_name ] = trim( $mt_data[ $mt_name . ':amount' ] . ' '. $mt_data[ $mt_name . ':currency' ] );
				}
			}

			foreach ( array( 'product:sale_price_dates' ) as $mt_name ) {

				if ( ! empty( $mt_data[ $mt_name . ':start_iso' ] ) && ! empty( $mt_data[ $mt_name . ':end_iso' ] ) ) {

					$mt_data[ $mt_name ] = $mt_data[ $mt_name . ':start_iso' ] . '/' . $mt_data[ $mt_name . ':end_iso' ];
				}
			}
		}
	}
}
