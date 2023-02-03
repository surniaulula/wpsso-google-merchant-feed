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

			add_action( 'wp_loaded', array( __CLASS__, 'add_rules' ), 1000 );
			add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ), -1000 );

			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ), 2000 );
		}

		/*
		 * Add and flush rewrite rules only if necessary.
		 */
		static public function add_rules() {

			global $wp_rewrite;

			$rewrite_rules = $wp_rewrite->wp_rewrite_rules();
			$rewrite_key   = '^(' . WPSSOGMF_PAGENAME . ')/feed/(rss2)/([^\./]+)\.xml$';
			$rewrite_value = 'index.php?pagename=$matches[1]&feed=$matches[2]&locale=$matches[3]';
			$rewrite_flush = empty( $rewrite_rules[ $rewrite_key ] ) ? true : false;

			/*
			 * Maintain support for the old WPSSO GMF pre-v5.0.0 rewrite rule.
			 */
			if ( 'google-merchant' === WPSSOGMF_PAGENAME ) {

				add_rewrite_rule( '^merchant-feed/([^/]+)\.xml$', 'index.php?pagename=' . WPSSOGMF_PAGENAME . '&feed=rss2&locale=$matches[1]', 'top' );
			}

			/*
			 * Always re-add and move the rewrite rule back to the top.
			 */
			add_rewrite_rule( $rewrite_key, $rewrite_value, $after = 'top' );

			if ( $rewrite_flush ) {
			
				flush_rewrite_rules( $hard = false );
			}
		}

		/*
		 * Add the 'locale' query variable.
		 * 
		 * The 'pagename' and 'feed' variables should already be defined by WordPress - include them just in case.
		 */
		static public function query_vars( $vars ) {

			foreach ( array( 'pagename', 'feed', 'locale' ) as $qv ) {

				if ( ! in_array( $qv, $vars, $strict = true ) ) {

					$vars[] = $qv;
				}
			}

			return $vars;
		}

		static public function template_redirect() {

			/*
			 * Make sure the requested pagename is valid.
			 */
			$request_pagename = get_query_var( 'pagename' );

			if ( WPSSOGMF_PAGENAME !== $request_pagename ) {	// Nothing to do.

				return;
			}

			/*
			 * Make sure the requested feed is valid.
			 */
			$request_feed = get_query_var( 'feed' );

			if ( 'rss2' !== $request_feed ) {

				SucomUtil::safe_error_log( sprintf( __( '%s error: %s', 'wpsso-google-merchant-feed' ),
					__METHOD__, __( 'Requested feed type is invalid.', 'wpsso-google-merchant-feed' ) ) );

				return;
			}

			/*
			 * Make sure the requested locale is valid, otherwise redirect using the default locale.
			 */
			$request_locale = get_query_var( 'locale' );
			$request_locale = SucomUtil::sanitize_locale( $request_locale );

			if ( empty( $request_locale ) ) {

				SucomUtil::safe_error_log( sprintf( __( '%s error: %s', 'wpsso-google-merchant-feed' ),
					__METHOD__, __( 'Requested locale value is empty.', 'wpsso-google-merchant-feed' ) ) );

				return;
			}

			$current_locale = SucomUtil::get_locale( $mixed = 'current' );
			$default_locale = SucomUtil::get_locale( $mixed = 'default' );

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'locale request = ' . $request_locale );
				$wpsso->debug->log( 'locale current = ' . $current_locale );
				$wpsso->debug->log( 'locale default = ' . $default_locale );
			}

			if ( $request_locale !== $current_locale ) {

				$locale_names = SucomUtil::get_available_feed_locale_names();

				if ( isset( $locale_names[ $request_locale ] ) ) {	// Just in case.

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'switching to request locale ' . $request_locale );
					}

					$is_switched = switch_to_locale( $request_locale );

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'switch to locale ' . ( $is_switched ? 'successful' : 'failed' ) );

						$wp_locale      = get_locale();
						$current_locale = SucomUtil::get_locale( $mixed = 'current' );

						$wpsso->debug->log( 'locale wp = ' . $wp_locale );
						$wpsso->debug->log( 'locale current = ' . $current_locale );
					}

				} else {

					$redirect_url = self::get_url( $default_locale );

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'unknown request locale ' . $request_locale );

						$wpsso->debug->log( 'redirect to default locale URL = ' . $redirect_url );
					}

					wp_redirect( $redirect_url );

					return;
				}
			}

			global $wp_query;

			$wp_query->is_404 = false;

			ob_implicit_flush( $enable = true );
			ob_end_flush();

			$document_xml = WpssoGmfXml::get();
			$disposition  = 'attachment';
			$filename     = SucomUtil::sanitize_file_name( $request_pagename . '-' . $request_locale . '.xml' );

			if ( $wpsso->debug->enabled ) {

				$document_xml .= $wpsso->debug->get_html( null, 'debug log' );
			}

			$content_len = strlen( $document_xml );

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-Type: application/rss+xml' );
			header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
			header( 'Content-Length: ' . $content_len );

			// phpcs:ignore $document_xml is a complete rss2 XML document that should not be encoded - tag values have already been sanitized and encoded.
			echo $document_xml;

			flush();
			sleep( $seconds = 1 );

			exit;
		}

		static public function get_url( $locale, $blog_id = null ) {

			global $wp_rewrite;

			if ( ! $wp_rewrite->using_permalinks() ) {

				$url = add_query_arg( array( 'pagename' => WPSSOGMF_PAGENAME, 'feed' => 'rss2', 'locale'  => $locale ), get_home_url( $blog_id ) );

			} else {

				$url = get_home_url( $blog_id, WPSSOGMF_PAGENAME . '/feed/rss2/' . $locale . '.xml' );
			}

			return $url;
		}
	}
}
