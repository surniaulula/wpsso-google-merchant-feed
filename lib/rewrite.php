<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2021 Jean-Sebastien Morisset (https://wpsso.com/)
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

			add_action( 'init', array( __CLASS__, 'add_rules' ) );

			add_action( 'activated_plugin', array( __CLASS__, 'add_flush_rules' ) );
			add_action( 'after_switch_theme', array( __CLASS__, 'add_flush_rules' ) );
			add_action( 'upgrader_process_complete', array( __CLASS__, 'add_flush_rules' ) );

			add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ) );

			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		}

		static public function add_rules() {

			add_rewrite_rule( '(' . WPSSOGMF_PAGENAME . ')\/.*?([^\/]*)\.xml', 'index.php?pagename=$matches[1]&gmflang=$matches[2]', 'top' );
		}

		static public function add_flush_rules() {

			self::add_rules();

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

				$avail_locales = SucomUtil::get_available_locales();	// Uses a local static cache.

				if ( $request_locale && in_array( $request_locale, $avail_locales ) ) {	// Just in case.

					switch_to_locale( $request_locale );	// Calls an action to clear the SucomUtil::get_locale() cache.

				} else {

					$default_locale = SucomUtil::get_locale( 'default' );
					$redirect_url   = self::get_url( $default_locale );

					wp_redirect( $redirect_url );

					return;
				}
			}

			$xml         = WpssoGmfXml::get();
			$disposition = 'attachment';
			$filename    = $request_pagename . '-' . $request_locale . '.xml';
			$length      = strlen( $xml );

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-Type: application/rss+xml' );
			header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
			header( 'Content-Length: ' . $length );

			echo $xml;

			exit;
		}

		static public function get_url( $locale, $blog_id = null ) {

			return get_home_url( $blog_id, WPSSOGMF_PAGENAME . '/' . $locale . '.xml' );
		}
	}
}
