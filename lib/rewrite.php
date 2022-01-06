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
			add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ) );

			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		}

		static public function add_rules() {

			add_rewrite_rule( '(' . WPSSOGMF_PAGENAME . ')\/.*?([^\/]*)\.xml', 'index.php?pagename=$matches[1]&gmflang=$matches[2]', 'top' );
		}

		static public function flush_rules() {

			flush_rewrite_rules();
		}

		static public function query_vars( $vars ) {

			$vars[] = 'gmflang';

			return $vars;
		}

		static public function template_redirect() {

			$request_pagename = get_query_var( 'pagename' );

			if ( WPSSOGMF_PAGENAME !== $request_pagename ) {

				return;
			}

			/**
			 * Make sure to requested locale is valid, otherwise redirect using the default locale.
			 */
			$request_locale = get_query_var( 'gmflang' );
			$request_locale = SucomUtil::sanitize_locale( $request_locale );
			$current_locale = SucomUtil::get_locale();

			if ( $request_locale !== $current_locale ) {

				$locale_names = SucomUtil::get_available_feed_locale_names();

				if ( isset( $locale_names[ $request_locale ] ) ) {	// Just in case.

					switch_to_locale( $request_locale );	// Calls an action to clear the SucomUtil::get_locale() cache.

				} else {

					$default_locale = SucomUtil::get_locale( 'default' );

					$redirect_url = self::get_url( $default_locale );

					wp_redirect( $redirect_url );

					return;
				}
			}

			global $wp_query;

			$wp_query->is_404 = false;

			ob_implicit_flush( true );
			ob_end_flush();

			$content     = WpssoGmfXml::get();
			$disposition = 'attachment';
			$filename    = $request_pagename . '-' . $request_locale . '.xml';
			$length      = strlen( $content );

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-Type: application/rss+xml' );
			header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
			header( 'Content-Length: ' . $length );

			echo $content;

			flush();

			sleep( 1 );

			exit;
		}

		static public function get_url( $locale, $blog_id = null ) {

			$pagename = WPSSOGMF_PAGENAME;

			$url = get_home_url( $blog_id, $pagename . '/' . $locale . '.xml' );

			return apply_filters( 'wpsso_google_merchant_feed_url', $url, $locale, $pagename, $blog_id );
		}
	}
}
