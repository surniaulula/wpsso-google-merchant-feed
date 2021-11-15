<?php
/**
 * Plugin Name: WPSSO Google Merchant Feeds
 * Plugin Slug: wpsso-google-merchant-feed
 * Text Domain: wpsso-google-merchant-feed
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-google-merchant-feed/
 * Assets URI: https://surniaulula.github.io/wpsso-google-merchant-feed/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Google Merchant Feeds for your WooCommerce, Easy Digital Downloads, and Custom Products (WPSSO Core Premium Required).
 * Requires PHP: 7.0
 * Requires At Least: 5.0
 * Tested Up To: 5.8.2
 * WC Tested Up To: 5.9.0
 * Version: 1.1.0-rc.2
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2014-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstracts/add-on.php';	// WpssoAddOn class.
}

if ( ! class_exists( 'WpssoGmf' ) ) {

	class WpssoGmf extends WpssoAddOn {

		public $filters;	// WpssoGmfFilters class object.
		public $rewrite;	// WpssoGmfRewrite class object.

		protected $p;		// Wpsso class object.

		private static $instance = null;	// WpssoGmf class object.

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-google-merchant-feed', false, 'wpsso-google-merchant-feed/languages/' );
		}

		public function init_objects_std() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$doing_ajax = defined( 'DOING_AJAX' ) ? DOING_AJAX : false;

			if ( $doing_ajax ) {

				return;
			}

			$is_admin   = is_admin();
			$info       = $this->cf[ 'plugin' ][ $this->ext ];
			$req_info   = $info[ 'req' ][ 'wpsso' ];
			$notice_msg = $this->get_requires_plugin_notice( $info, $req_info );

			if ( $is_admin ) {
			
				$this->p->notice->err( $notice_msg );

				SucomUtil::safe_error_log( __METHOD__ . ' error: ' . $notice_msg, $strip_html = true );
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( strtolower( $notice_msg ) );
			}
		}

		public function init_objects_pro() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$this->filters = new WpssoGmfFilters( $this->p, $this );
			$this->rewrite = new WpssoGmfRewrite( $this->p, $this );
		}
	}

	WpssoGmf::get_instance();
}
