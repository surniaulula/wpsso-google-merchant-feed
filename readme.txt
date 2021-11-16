=== WPSSO Google Merchant Feeds XML ===
Plugin Name: WPSSO Google Merchant Feeds XML
Plugin Slug: wpsso-google-merchant-feed
Text Domain: wpsso-google-merchant-feed
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-google-merchant-feed/assets/
Tags: merchant feed, xml, wpml, polylang, woocommerce, edd, easy digital downloads
Contributors: jsmoriss
Requires PHP: 7.2
Requires At Least: 5.2
Tested Up To: 5.8.2
WC Tested Up To: 5.9.0
Stable Tag: 1.1.0

Google Merchant Feeds for WooCommerce, Easy Digital Downloads, and Custom Products (WPSSO Core Premium Required).

== Description ==

<!-- about -->

The WPSSO Google Merchant Feeds XML add-on can retrieve product information from WPSSO Core Premium and provide maintenance free XML feeds for each available language (as dictated by Polylang, WPLM, or the installed WordPress languages).

There are no plugin or add-on settings to configure &mdash; the WPSSO Google Merchant Feeds XML add-on automatically retrieves all available product information in the language (aka locale) requested.

A supported e-commerce plugin, like WooCommerce or Easy Digital Downloads, is suggested but not required &mdash; the WPSSO Google Merchant Feeds XML add-on can also retrieve custom product information entered in the WordPress editor Document SSO metabox. WooCommerce product variations and additional WooCommerce product attributes are fully supported.

<h3>An XML Feed for Each Language</h3>

Google merchant XML feeds for WooCommerce, Easy Digital Downloads, and custom products are automatically created for each available language (as dictated by Polylang, WPLM, or the installed WordPress languages).

After activating the WPSSO Google Merchant Feeds XML add-on, see the SSO &gt; Google Merchant Feeds settings page for a complete list of available feed URLs.

<!-- /about -->

<h3>Google Merchant Feed Attributes</h3>

The following XML feed attributes are automatically generated from your WooCommerce, Easy Digital Downloads, and custom products, including WooCommerce product variations:

* ID <code>&#91;id&#93;</code>
* Title <code>&#91;title&#93;</code>
* Description <code>&#91;description&#93;</code>
* Link <code>&#91;link&#93;</code>
* Image link <code>&#91;image_link&#93;</code>
* Additional image link <code>&#91;additional_image_link&#93;</code>
* Availability <code>&#91;availability&#93;</code>
* Price <code>&#91;price&#93;</code>
* Sale price <code>&#91;sale_price&#93;</code>
* Sale price effective date <code>&#91;sale_price_effective_date&#93;</code>
* Google product category <code>&#91;google_product_category&#93;</code>
* Product type <code>&#91;product_type&#93;</code>
* Google search index <code>&#91;canonical_link&#93;</code>
* Brand <code>&#91;brand&#93;</code>
* GTIN <code>&#91;gtin&#93;</code> (inluding UPC, EAN, and ISBN)
* MPN <code>&#91;mpn&#93;</code>
* Condition <code>&#91;condition&#93;</code>
* Color <code>&#91;color&#93;</code>
* Material <code>&#91;material&#93;</code>
* Pattern <code>&#91;pattern&#93;</code>
* Gender <code>&#91;gender&#93;</code>
* Size <code>&#91;size&#93;</code>
* Size type <code>&#91;size_type&#93;</code>
* Item group ID <code>&#91;item_group_id&#93;</code>

<h3>WPSSO Core Premium Required</h3>

WPSSO Google Merchant Feeds XML (WPSSO GMF) is an add-on for the [WPSSO Core Premium plugin](https://wpsso.com/extend/plugins/wpsso/).

**The WPSSO Core Premium plugin provides:**

* An integration module for the WooCommerce plugin.
* An integration module for the Easy Digital Downloads plugin.
* Custom product options in the Document SSO metabox.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Google Merchant Feeds XML add-on](https://wpsso.com/docs/plugins/wpsso-google-merchant-feed/installation/install-the-plugin/).
* [Uninstall the WPSSO Google Merchant Feeds XML add-on](https://wpsso.com/docs/plugins/wpsso-google-merchant-feed/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

<h3 class="top">Frequently Asked Questions</h3>

* None.

<h3>Notes and Documentation</h3>

* None.

== Screenshots ==

01. The Google Merchant Feeds settings page shows a complete list of available XML feed URLs.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-google-merchant-feed/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. Save the plugin settings and click the "Check for Plugin Updates" button to fetch the latest version information. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can always reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

**Version 1.1.0 (2021/11/16)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new 'wpsso_google_merchant_feed_url' filter.
	* Used the new `WpssoUtil->get_available_feed_locale_names()` method (filtered by the Polylang and WPML integration modules).
	* Refactored the `SucomAddOn->get_missing_requirements()` method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.8.0.

**Version 1.0.0 (2021/11/10)**

* **New Features**
	* Initial release.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.7.0.

== Upgrade Notice ==

= 1.1.0 =

(2021/11/16) Used the new `WpssoUtil->get_available_feed_locale_names()` method (filtered by the Polylang and WPML integration modules).

= 1.0.0 =

(2021/11/10) Initial release.

