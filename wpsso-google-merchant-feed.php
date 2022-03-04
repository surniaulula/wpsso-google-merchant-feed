<?php
/**
 * Plugin Name: WPSSO Google Merchant Feeds XML
 * Plugin Slug: wpsso-google-merchant-feed
 * Text Domain: wpsso-google-merchant-feed
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-google-merchant-feed/
 * Assets URI: https://surniaulula.github.io/wpsso-google-merchant-feed/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Google Merchant Feeds for WooCommerce, Easy Digital Downloads, and Custom Products (WPSSO Core Premium Required).
 * Requires PHP: 7.2
 * Requires At Least: 5.2
 * Tested Up To: 5.9.1
 * WC Tested Up To: 6.2.1
 * Version: 2.1.0-dev.7
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoGmf' ) ) {

	class WpssoGmf extends WpssoAbstractAddOn {

		public $actions;	// WpssoGmfActions class object.
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

			$this->actions = new WpssoGmfActions( $this->p, $this );
			$this->filters = new WpssoGmfFilters( $this->p, $this );
			$this->rewrite = new WpssoGmfRewrite( $this->p, $this );
		}
	}

	WpssoGmf::get_instance();
}
