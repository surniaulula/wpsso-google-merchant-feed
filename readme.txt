=== WPSSO Google Merchant Feed XML ===
Plugin Name: WPSSO Google Merchant Feed XML
Plugin Slug: wpsso-google-merchant-feed
Text Domain: wpsso-google-merchant-feed
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-google-merchant-feed/assets/
Tags: google shopping feed, google merchant center, woocommerce product feed, google shopping, woocommerce, wpml, polylang, easy digital downloads
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.2.34
Requires At Least: 5.5
Tested Up To: 6.4.0
WC Tested Up To: 8.2.1
Stable Tag: 6.8.0

Google Merchant Feed XMLs for WooCommerce and custom product pages, with multilingual support.

== Description ==

<!-- about -->

**Google Merchant Feed XMLs for WooCommerce and custom product pages, with multilingual support.**

**E-Commerce Plugin Optional:**

WooCommerce is suggested but not required - the WPSSO Google Merchant Feed XML add-on can also use product information from custom product pages.

**Complete WooCommerce Support:**

WooCommerce simple products, product variations, product attributes, product meta data, and custom fields are all fully supported.

<!-- /about -->

**Automatic Multilingual Support:**

The Google merchant product feed XMLs are automatically created in your site's language(s) from Polylang, WPML, or the installed WordPress languages.

After activating the WPSSO Google Merchant Feed XML add-on, see the SSO &gt; Google Merchant settings page for your feed URLs.

**Google Merchant Feed XML Attributes:**

The following XML product attributes are automatically created from your WooCommerce and custom products:

* Additional image link <code>&#91;additional_image_link&#93;</code>
* Adult oriented <code>&#91;adult&#93;</code>
* Age group <code>&#91;age_group&#93;</code>
* Availability <code>&#91;availability&#93;</code>
* Brand <code>&#91;brand&#93;</code>
* Condition <code>&#91;condition&#93;</code>
* Color <code>&#91;color&#93;</code>
* Description <code>&#91;description&#93;</code>
* Energy efficiency class <code>&#91;energy_efficiency_class&#93;</code>
* Gender <code>&#91;gender&#93;</code>
* Google product category <code>&#91;google_product_category&#93;</code>
* Google search index <code>&#91;canonical_link&#93;</code>
* GTIN <code>&#91;gtin&#93;</code> (inluding UPC, EAN, and ISBN)
* ID <code>&#91;id&#93;</code>
* Image link <code>&#91;image_link&#93;</code>
* Item group ID <code>&#91;item_group_id&#93;</code>
* Link <code>&#91;link&#93;</code>
* Material <code>&#91;material&#93;</code>
* Maximum energy efficiency class <code>&#91;max_energy_efficiency_class&#93;</code>
* Minimum energy efficiency class <code>&#91;min_energy_efficiency_class&#93;</code>
* MPN <code>&#91;mpn&#93;</code>
* Pattern <code>&#91;pattern&#93;</code>
* Price <code>&#91;price&#93;</code>
* Product length <code>&#91;product_length&#93;</code>
* Product height <code>&#91;product_height&#93;</code>
* Product type <code>&#91;product_type&#93;</code>
* Product weight <code>&#91;product_weight&#93;</code>
* Product width <code>&#91;product_width&#93;</code>
* Sale price <code>&#91;sale_price&#93;</code>
* Sale price effective date <code>&#91;sale_price_effective_date&#93;</code>
* Shipping length <code>&#91;shipping_length&#93;</code>
* Shipping height <code>&#91;shipping_height&#93;</code>
* Shipping weight <code>&#91;shipping_weight&#93;</code>
* Shipping width <code>&#91;shipping_width&#93;</code>
* Size <code>&#91;size&#93;</code>
* Size system <code>&#91;size_system&#93;</code>
* Size type <code>&#91;size_type&#93;</code>
* Title <code>&#91;title&#93;</code>

<h3>WPSSO Core Required</h3>

WPSSO Google Merchant Feed XML (WPSSO GMF) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which provides complete structured data for WordPress to present your content at its best on social sites and in search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Google Merchant Feed XML add-on](https://wpsso.com/docs/plugins/wpsso-google-merchant-feed/installation/install-the-plugin/).
* [Uninstall the WPSSO Google Merchant Feed XML add-on](https://wpsso.com/docs/plugins/wpsso-google-merchant-feed/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. The WPSSO GMF settings page shows a complete list of available XML feed URLs.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-google-merchant-feed/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-google-merchant-feed/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium edition customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<p><strong>WPSSO Core Standard edition users (ie. the plugin hosted on WordPress.org) have access to <a href="https://wordpress.org/plugins/wpsso-google-merchant-feed/advanced/">the latest development version under the Advanced Options section</a>.</strong></p>

<h3>Changelog / Release Notes</h3>

**Version 7.0.0-rc.2 (2021/11/08)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored the settings page and metabox load process for WPSSO Core v17.0.0.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v17.0.0-rc.2.

**Version 6.8.0 (2023/10/28)**

* **New Features**
	* None.
* **Improvements**
	* Update for deprecated method.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Changed deprecated `WpssoUtilCache->doing_task()` call for `WpssoUtilCache->is_refresh_running()`.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v16.5.0.

== Upgrade Notice ==

= 7.0.0-rc.2 =

(2021/11/08) Refactored the settings page and metabox load process for WPSSO Core v17.0.0.

= 6.8.0 =

(2023/10/28) Update for deprecated method.

