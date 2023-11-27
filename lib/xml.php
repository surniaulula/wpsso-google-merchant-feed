<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;
use Vitalybaev\GoogleMerchant\Product\Shipping;

if ( ! class_exists( 'WpssoGmfXml' ) ) {

	class WpssoGmfXml {

		/*
		 * Clear the feed XML cache files.
		 *
		 * See WpssoGmfActions->action_refresh_post_cache().
		 */
		static public function clear_cache( $request_locale = null, $request_type = 'feed' ) {

			$wpsso =& Wpsso::get_instance();

			if ( ! $request_locale ) {

				$request_locale = SucomUtil::get_locale();
			}

			$cache_salt     = __CLASS__ . '::get(locale:' . $request_locale . '_type:' . $request_type . ')';
			$cache_file_ext = '.xml';

			$wpsso->cache->clear_cache_data( $cache_salt, $cache_file_ext );	// Clear the feed XML cache file.
		}

		static public function get( $request_locale = null, $request_type = 'feed' ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$original_locale = SucomUtil::get_locale();
			$current_locale  = $original_locale;
			$request_locale  = $request_locale ? $request_locale : $current_locale;
			$is_switched     = false;

			if ( $request_locale !== $current_locale ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'switching to request locale ' . $request_locale );
				}

				$is_switched    = switch_to_locale( $request_locale );	// Switches to locale if the WP language is installed.
				$current_locale = SucomUtil::get_locale();		// Update the current locale value.

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'switch to locale ' . ( $is_switched ? 'successful' : 'failed' ) );
				}
			}

			$cache_md5_pre  = 'wpsso_g_';
			$cache_type     = 'file';
			$cache_salt     = __CLASS__ . '::get(locale:' . $request_locale . '_type:' . $request_type . ')';
			$cache_file_ext = '.xml';
			$cache_exp_secs = $wpsso->util->get_cache_exp_secs( $cache_md5_pre, $cache_type );

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'original locale = ' . $original_locale );
				$wpsso->debug->log( 'request locale = ' . $request_locale );
				$wpsso->debug->log( 'current locale = ' . $current_locale );
				$wpsso->debug->log( 'cache expire = ' . $cache_exp_secs );
				$wpsso->debug->log( 'cache salt = ' . $cache_salt );
			}

			if ( $cache_exp_secs ) {

				$xml = $wpsso->cache->get_cache_data( $cache_salt, $cache_type, $cache_exp_secs, $cache_file_ext );

				if ( false !== $xml ) {

					if ( $is_switched ) {

						restore_previous_locale();
					}

					return $xml;
				}
			}

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'creating new feed' );
			}

			$site_title = SucomUtil::get_site_name( $wpsso->options, $request_locale );
			$site_url   = SucomUtil::get_home_url( $wpsso->options, $request_locale );
			$site_desc  = SucomUtil::get_site_description( $wpsso->options, $request_locale );
			$rss2_feed  = new Vitalybaev\GoogleMerchant\Feed( $site_title, $site_url, $site_desc, '2.0' );
			$query_args = array( 'meta_query' => WpssoAbstractWpMeta::get_column_meta_query_og_type( $og_type = 'product', $request_locale ) );
			$public_ids = WpssoPost::get_public_ids( $query_args );

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'adding ' . count( $public_ids ) . ' public ids' );

				$wpsso->debug->log_arr( 'public_ids', $public_ids );
			}

			foreach ( $public_ids as $post_id ) {

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

				if ( ! empty( $mt_og[ 'product:variants' ] ) && is_array( $mt_og[ 'product:variants' ] ) ) {

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'adding ' . count( $mt_og[ 'product:variants' ] ) . ' variants for post id ' . $post_id );
					}

					foreach ( $mt_og[ 'product:variants' ] as $num => $mt_single ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'adding variant #' . $num . ' for post id ' . $post_id );
						}

						self::add_feed_item( $rss2_feed, $mt_single, $request_type );
					}

				} else {

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'adding product for post id ' . $post_id );
					}

					self::add_feed_item( $rss2_feed, $mt_og, $request_type );
				}
			}

			$xml = $rss2_feed->build();

			if ( $cache_exp_secs ) {

				$wpsso->cache->save_cache_data( $cache_salt, $xml, $cache_type, $cache_exp_secs, $cache_file_ext );
			}

			if ( $is_switched ) {

				restore_previous_locale();
			}

			return $xml;
		}

		/*
		 * See product feed specification https://support.google.com/merchants/answer/7052112.
		 * See sales feed specification at https://support.google.com/merchants/answer/7676872.
		 * See inventory feed specification at https://support.google.com/merchants/answer/7677785.
		 * See store feed specification at https://support.google.com/merchants/answer/7677622.
		 */
		static private function add_feed_item( &$rss2_feed, array $mt_single, $request_type = 'feed' ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$item = null;

			self::sanitize_mt_array( $mt_single );

			switch ( $request_type ) {

				case 'feed':

					$callbacks = WpssoGmfConfig::get_callbacks( 'product' );

					$item = new Vitalybaev\GoogleMerchant\Product();

					self::add_item_data( $item, $mt_single, $callbacks );
	
					self::add_item_images( $item, $mt_single );

					self::add_item_shipping( $item, $mt_single );

					break;

				case 'inventory':

					$mt_single[ 'product:merchant_id' ] = isset( $wpsso->options[ 'gmf_merchant_id' ] ) ?  $wpsso->options[ 'gmf_merchant_id' ] : '';
					$mt_single[ 'product:store_code' ]  = isset( $wpsso->options[ 'gmf_store_code' ] ) ?  $wpsso->options[ 'gmf_store_code' ] : '';

					$callbacks = WpssoGmfConfig::get_callbacks( 'inventory' );

					$item = new Vitalybaev\GoogleMerchant\Inventory();

					self::add_item_data( $item, $mt_single, $callbacks );

					break;
			}

			if ( ! empty( $item ) ) $rss2_feed->addItem( $item );
		}

		static private function add_item_images( &$product, $mt_single ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$image_urls = $wpsso->og->get_product_retailer_item_image_urls( $mt_single, $size_names = 'wpsso-gmf', $md_pre = array( 'gmf', 'schema', 'og' ) );

			foreach ( $image_urls as $num => $image_url ) {

				if ( 0 == $num ) {

					$product->setImage( $image_url );

				} else $product->addAdditionalImage( $image_url );
			}
		}

		static private function add_item_shipping( &$product, $mt_single ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$mt_single_shipping = self::get_mt_single_shipping( $mt_single );

			if ( empty( $mt_single_shipping ) ) {	// Nothing to do.

				return;
			}

			$callbacks = WpssoGmfConfig::get_callbacks( 'shipping' );

			foreach ( $mt_single_shipping as $num => $ship_opts ) {

				$shipping = new Vitalybaev\GoogleMerchant\Product\Shipping();

				self::add_item_data( $shipping, $ship_opts, $callbacks );

				if ( 0 == $num ) {

					$product->setShipping( $shipping );

				} else $product->addShipping( $shipping );
			}
		}

		static private function add_item_data( &$item, array $data, array $callbacks ) {

			foreach ( $callbacks as $key => $callback ) {

				if ( empty( $callback ) ) {	// Not used.

					continue;

				} elseif ( isset( $data[ $key ] ) && '' !== $data[ $key ] ) {	// Not null or empty string.

					if ( is_array( $callback ) ) {

						list( $method_name, $prop_name, $is_cdata ) = $callback;

					} else list( $method_name, $prop_name, $is_cdata ) = array( $callback, '', false );

					$values = is_array( $data[ $key ] ) ? $data[ $key ] : array( $data[ $key ] );

					foreach ( $values as $value ) {

						foreach ( array( ':value' => ':units', '_cost'  => '_currency' ) as $value_suffix => $append_suffix ) {

							if ( false !== strpos( $key, $value_suffix ) ) {

								$key_append = preg_replace( '/' . $value_suffix . '$/', $append_suffix, $key );

								if ( ! empty( $data[ $key_append ] ) ) {

									$value .= ' ' . $data[ $key_append ];
								}
							}
						}

						if ( method_exists( $item, $method_name ) ) {	// Just in case.

							if ( $prop_name ) {

								$item->$method_name( $prop_name, $value, $is_cdata );

							} else $item->$method_name( $value );

						} else {

							$notice_pre = sprintf( '%s error:', __METHOD__ );

							$notice_msg = sprintf( __( '%1$s::%2$s() method does not exist.', 'wpsso-google-merchant-feed' ),
								get_class( $item ), $method_name );

							SucomUtil::safe_error_log( $notice_pre . ' ' . $notice_msg );
						}
					}
				}
			}
		}

		static private function get_mt_single_shipping( $mt_single ) {

			$shipping = array();

			if ( empty( $mt_single[ 'product:shipping_offers' ] ) ) {	// Nothing to do.

				return $shipping;
			}

			$callbacks = WpssoGmfConfig::get_callbacks( 'shipping' );

			foreach ( $mt_single[ 'product:shipping_offers' ] as $num => $ship_offer ) {

				if ( empty( $ship_offer[ 'shipping_destinations' ] ) ) {

					continue;
				}

				foreach ( $ship_offer[ 'shipping_destinations' ] as $ship_num => $ship_dest ) {

					$ship_opts = array();

					foreach ( $callbacks as $key => $callback ) {

						if ( empty( $callback ) ) {	// Not used.

							continue;

						} elseif ( isset( $ship_offer[ $key ] ) ) {

							$ship_opts[ $key ] = $ship_offer[ $key ];

						} elseif ( isset( $ship_offer[ 'shipping_rate' ][ $key ] ) ) {

							$ship_opts[ $key ] = $ship_offer[ 'shipping_rate' ][ $key ];

						} elseif ( isset( $ship_offer[ 'delivery_time' ][ $key ] ) ) {

							$ship_opts[ $key ] = $ship_offer[ 'delivery_time' ][ $key ];

							/*
							 * Transit and handling times are in days.
							 *
							 * If the unit code is HUR (hours), then convert the hours to days.
							 */
							$matches = null;

							if ( preg_match( '/^(.*)_(minimum|maximum)$/', $key, $matches ) ) {

								$unit_key = $matches[ 1 ] . '_unit_code';

								if ( isset( $ship_offer[ 'delivery_time' ][ $unit_key ] ) &&	// Just in case.
									'HUR' === $ship_offer[ 'delivery_time' ][ $unit_key ] ) {

									$ship_opts[ $key ] = round( $ship_opts[ $key ] / 24, 1 );
								}
							}

						} elseif ( isset( $ship_dest[ $key ] ) ) {

							$ship_opts[ $key ] = $ship_dest[ $key ];

						} else $ship_opts[ $key ] = null;
					}

					if ( empty( $ship_dest[ 'postal_code' ] ) ) {

						$ship_opts[ 'postal_code' ] = null;

						$shipping[] = $ship_opts;

					} else {

						foreach( $ship_dest[ 'postal_code' ] as $post_num => $postal_code ) {

							$postal_code = str_replace( '...', '-', $postal_code );

							$ship_opts[ 'postal_code' ] = $postal_code;

							$shipping[] = $ship_opts;
						}
					}
				}
			}

			return $shipping;
		}

		static private function sanitize_mt_array( &$mt_single ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$content_maps = $wpsso->cf[ 'head' ][ 'gmf_content_map' ];

			foreach ( $content_maps as $mt_name => $map ) {

				if ( isset( $mt_single[ $mt_name ] ) ) {

					self::map_mt_value( $mt_single[ $mt_name ], $map );
				}
			}

			foreach ( array( 'product:price', 'product:sale_price' ) as $mt_name ) {

				if ( isset( $mt_single[ $mt_name . ':amount' ] ) && isset( $mt_single[ $mt_name . ':currency' ] ) ) {

					$mt_single[ $mt_name ] = trim( $mt_single[ $mt_name . ':amount' ] . ' '. $mt_single[ $mt_name . ':currency' ] );
				}
			}

			foreach ( array( 'product:sale_price_dates' ) as $mt_name ) {

				if ( ! empty( $mt_single[ $mt_name . ':start_iso' ] ) && ! empty( $mt_single[ $mt_name . ':end_iso' ] ) ) {

					$mt_single[ $mt_name ] = $mt_single[ $mt_name . ':start_iso' ] . '/' . $mt_single[ $mt_name . ':end_iso' ];
				}
			}
		}

		static private function map_mt_value( &$value, array $map ) {

			if ( is_array( $value ) ) {

				foreach ( $value as $num => &$arr_val ) {

					self::map_mt_value( $arr_val, $map );
				}

			} elseif ( isset( $map[ $value ] ) ) {	// Allow for false.

				$value = $map[ $value ];
			}
		}
	}
}
