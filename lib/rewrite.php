<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoGmfRewrite' ) ) {

	class WpssoGmfRewrite {

		private $p;		// Wpsso class object.
		private $a;		// WpssoGmf class object.

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

			add_action( 'wp_loaded', array( __CLASS__, 'add_rules' ) );
			add_action( 'activated_plugin', array( __CLASS__, 'flush_rules' ) );
			add_action( 'after_switch_theme', array( __CLASS__, 'flush_rules' ) );
			add_action( 'upgrader_process_complete', array( __CLASS__, 'flush_rules' ) );
			add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ), -2000 );

			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		}

		static public function add_rules() {

			add_rewrite_rule( '^' . WPSSOGMF_PAGENAME . '\/([^\/]+)\.xml$', 'index.php?pagename=' . WPSSOGMF_PAGENAME . '&gmflang=$matches[1]', 'top' );
		}

		static public function flush_rules() {

			flush_rewrite_rules();
		}

		static public function query_vars( $vars ) {

			$vars[] = 'gmflang';

			return $vars;
		}

		static public function template_redirect() {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$request_pagename = get_query_var( 'pagename' );

			if ( WPSSOGMF_PAGENAME !== $request_pagename ) {

				return;
			}

			/**
			 * Make sure the requested locale is valid, otherwise redirect using the default locale.
			 */
			$request_locale = get_query_var( 'gmflang' );
			$request_locale = SucomUtil::sanitize_locale( $request_locale );

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'getting current locale' );
			}

			$current_locale = SucomUtil::get_locale( $mixed = 'current' );

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'getting default locale' );
			}

			$default_locale = SucomUtil::get_locale( $mixed = 'default' );

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

					$switched = switch_to_locale( $request_locale );

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'switch to locale ' . ( $switched ? 'successful' : 'failed' ) );

						$wp_locale      = get_locale();
						$current_locale = SucomUtil::get_locale( $mixed = 'current' );

						$wpsso->debug->log( 'wp locale = ' . $wp_locale );
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

			/**
			 * Do not use esc_xml() as this replaces XML markup by HTML entities (ie. '<' by '&lt;' for example).
			 */
			$attachment  = WpssoGmfXml::get();
			$disposition = 'attachment';
			$filename    = SucomUtil::sanitize_file_name( $request_pagename . '-' . $request_locale . '.xml' );

			if ( $wpsso->debug->enabled ) {

				/**
				 * Do not use esc_html() as this replaces HTML markup by HTML entities (ie. '<' by '&lt;' for example).
				 */
				$attachment .= $wpsso->debug->get_html( null, 'debug log' );
			}

			$content_len = strlen( $attachment );	// Escaped XML and HTML attachment content length.

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-Type: application/rss+xml' );
			header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
			header( 'Content-Length: ' . $content_len );

			echo $attachment;

			flush();
			sleep( $seconds = 1 );

			exit;
		}

		static public function get_url( $locale, $blog_id = null ) {

			global $wp_rewrite;

			if ( ! $wp_rewrite->using_permalinks() ) {

				$url = add_query_arg( array( 'pagename' => WPSSOGMF_PAGENAME, 'gmflang'  => $locale ), get_home_url( $blog_id ) );

			} else {

				$url = get_home_url( $blog_id, WPSSOGMF_PAGENAME . '/' . $locale . '.xml' );
			}

			return apply_filters( 'wpsso_google_merchant_feed_url', $url, $locale, WPSSOGMF_PAGENAME, $blog_id );
		}
	}
}
