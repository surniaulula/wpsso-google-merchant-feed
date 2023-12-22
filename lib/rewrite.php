<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfRewrite' ) ) {

	class WpssoGmfRewrite {

		private $p;		// Wpsso class object.
		private $a;		// WpssoGmf class object.

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

			add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ), -1000 );

			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ), 1000 );

			self::maybe_add_rules();
		}

		static public function maybe_add_rules() {

			/*
			 * Maintain support for the old WPSSO GMF pre-v5.0.0 rewrite rule.
			 */
			if ( 'google-merchant' === WPSSOGMF_PAGENAME ) {

				add_rewrite_rule( '^merchant-feed/([^/]+)\.xml$', 'index.php?feed_name=' .
					WPSSOGMF_PAGENAME . '&feed_type=feed&feed_format=rss2&feed_locale=$matches[1]', 'top' );
			}

			global $wp_rewrite;

			$rewrite_rules   = $wp_rewrite->wp_rewrite_rules();
			$rewrite_key     = '^(' . WPSSOGMF_PAGENAME . ')/(feed|inventory)/(rss2)/([^\./]+)\.xml$';
			$rewrite_value   = 'index.php?feed_name=$matches[1]&feed_type=$matches[2]&feed_format=$matches[3]&feed_locale=$matches[4]';
			$rewrite_missing = empty( $rewrite_rules[ $rewrite_key ] ) || $rewrite_rules[ $rewrite_key ] !== $rewrite_value ? true : false;

			add_rewrite_rule( $rewrite_key, $rewrite_value, $after = 'top' );

			if ( $rewrite_missing ) {

				flush_rewrite_rules( $hard = false );	// Update only the 'rewrite_rules' option, not the .htaccess file.
			}
		}

		static public function query_vars( $vars ) {

			foreach ( array( 'feed_name', 'feed_type', 'feed_format', 'feed_locale' ) as $qv ) {

				if ( ! in_array( $qv, $vars, $strict = true ) ) {

					$vars[] = $qv;
				}
			}

			return $vars;
		}

		static public function template_redirect() {

			/*
			 * Make sure the requested name is valid.
			 */
			$request_name = get_query_var( 'feed_name' );

			if ( WPSSOGMF_PAGENAME !== $request_name ) {	// Nothing to do.

				return;
			}

			$wpsso =& Wpsso::get_instance();

			/*
			 * Make sure the requested type is valid.
			 */
			$request_type = get_query_var( 'feed_type' );

			if ( 'rss2' === $request_type ) {	// Backwards compatibility.

				$request_type   = 'feed';
				$request_format = 'rss2';

			} else {

				if ( 'inventory' === $request_type ) {

					if ( empty( $wpsso->avail[ 'ecom' ][ 'any' ] ) ) {	// No e-commerce plugin active.

						WpssoErrorException::http_error( 400 );
					}

				} elseif ( 'feed' !== $request_type ) {

					WpssoErrorException::http_error( 400 );
				}

				/*
				 * Make sure the requested format is valid.
				 */
				$request_format = get_query_var( 'feed_format' );

				if ( 'rss2' !== $request_format ) {

					WpssoErrorException::http_error( 400 );
				}
			}

			/*
			 * Make sure the requested locale is valid.
			 */
			$request_locale = get_query_var( 'feed_locale' );
			$request_locale = SucomUtil::sanitize_locale( $request_locale );
			$locale_names   = SucomUtil::get_available_feed_locale_names();

			if ( ! isset( $locale_names[ $request_locale ] ) ) {

				WpssoErrorException::http_error( 400 );

			} elseif ( $wpsso->util->cache->is_refresh_running() ) {

				WpssoErrorException::http_error( 503 );
			}

			global $wp_query;

			$wp_query->is_404 = false;

			ob_implicit_flush( $enable = true );
			ob_end_flush();

			$filename = SucomUtil::sanitize_file_name( $request_name . '-' . $request_locale . '.xml' );

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-Type: application/rss+xml' );
			header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

			$document_xml = WpssoGmfXml::get( $request_locale, $request_type );

			if ( ! $wpsso->debug->is_enabled( 'html' ) ) {	// Only add content length if not adding debug messages.

				header( 'Content-Length: ' . strlen( $document_xml ) );
			}

			// phpcs:ignore $document_xml is a complete rss2 XML document that should not be encoded - tag values have already been sanitized and encoded.
			echo $document_xml;

			unset( $document_xml );

			if ( $wpsso->debug->is_enabled( 'html' ) ) {

				echo $wpsso->debug->get_html( null, 'debug log' );
			}

			flush();
			sleep( $seconds = 1 );

			exit;
		}

		static public function get_url( $locale, $request_type = 'feed', $blog_id = null ) {

			global $wp_rewrite;

			if ( ! $wp_rewrite->using_permalinks() ) {

				$url = add_query_arg( array(
					'feed_name'   => WPSSOGMF_PAGENAME,
					'feed_type'   => $request_type,
					'feed_format' => 'rss2',
					'feed_locale' => $locale,
				), get_home_url( $blog_id ) );

			} else $url = get_home_url( $blog_id, WPSSOGMF_PAGENAME . '/' . $request_type . '/rss2/' . $locale . '.xml' );

			return $url;
		}
	}
}
