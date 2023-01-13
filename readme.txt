=== WPSSO Google Merchant Feed XML ===
Plugin Name: WPSSO Google Merchant Feed XML
Plugin Slug: wpsso-google-merchant-feed
Text Domain: wpsso-google-merchant-feed
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-google-merchant-feed/assets/
Tags: google merchant center, woocommerce, wpml, polylang, multilingual, xml, edd, easy digital downloads
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.2
Requires At Least: 5.2
Tested Up To: 6.1.1
WC Tested Up To: 7.3.0
Stable Tag: 5.0.0

Google Merchant Feed XMLs for WooCommerce, Easy Digital Downloads, and Custom Product Pages.

== Description ==

<!-- about -->

<h3>E-Commerce Plugin Not Required</h3>

A supported e-commerce plugin, like WooCommerce or Easy Digital Downloads, <em>is suggested but not required</em> &mdash; the WPSSO Google Merchant Feed XML add-on also retrieves custom product information entered in the Document SSO metabox when editing a product page.

<h3>Complete WooCommerce Support</h3>

WooCommerce product variations, attributes, meta data, and custom fields are all fully supported.

<h3>No Add-on Settings to Configure</h3>

The WPSSO Google Merchant Feed XML add-on automatically retrieves all available product information in the language (aka locale) requested.

<h3>Multilingual</h3>

The Google merchant feed XMLs are automatically created in your site's language(s) from Polylang, WPML, or the installed WordPress languages.

After activating the WPSSO Google Merchant Feed XML add-on, see the SSO &gt; Google Merchant settings page for your feed URLs.

<!-- /about -->

<h3>Google Merchant Feed XML Attributes</h3>

The following XML product attributes are automatically created based on your WooCommerce, Easy Digital Downloads, and custom products (including WooCommerce product variations):

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

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

**Version 5.1.0-b.1 (2023/01/13)**

* **New Features**
	* None.
* **Improvements**
	* Removed the "Save Plugin Settings" and "Change to View" buttons from the Google Merchant settings page.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.5.0-b.1.

**Version 5.0.0 (2023/01/11)**

* **New Features**
	* None.
* **Improvements**
	* Changed the default feed URL from '/merchant-feed/' to '/google-merchant/feed/rss2/' (the old default feed URLs continue to be supported).
	* Renamed the SSO settings page from "Merchant Feed" to "Google Merchant".
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new filters-advanced.php library file.
	* Added a new filters-edit.php library file.
	* Updated the 'vitalybaev/google-merchant-feed' library to v2.6.0.
	* Renamed the 'gmflang' query variable to 'locale'.
	* Refactored the `WpssoGmfRewrite::add_rules()` static method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.4.0.

**Version 4.2.0 (2023/01/03)**

* **New Features**
	* None.
* **Improvements**
	* Added support for a second 'size_type' value.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new `WpssoGmfXml::sanitize_mt_value()` method.
	* Refactored the `WpssoGmfXml::add_product_data()` method.
	* Refactored the `WpssoGmfXml::sanitize_mt_array()` method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.2.0.

**Version 4.1.0 (2022/12/29)**

* **New Features**
	* None.
* **Improvements**
	* Updated the WP posts query to exclude noindex and redirected post meta as part of the query (instead of checking post metadata afterwards).
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a private `WpssoGmfXml::get_meta_query()` static method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.1.0.

**Version 4.0.0 (2022/12/28)**

* **New Features**
	* None.
* **Improvements**
	* Added new XML product attributes:
		* "energy_efficiency_class"
		* "min_energy_efficiency_class"
		* "max_energy_efficiency_class"
		* "product_length"
		* "product_height"
		* "product_weight"
		* "product_width"
		* "shipping_length"
		* "shipping_height"
		* "shipping_weight"
		* "shipping_width"
		* "size_system"
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.0.0.

**Version 3.4.0 (2022/12/07)**

* **New Features**
	* None.
* **Improvements**
	* Added an action hook to clear the locale feed XML when a product is updated.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new `WpssoGmfActions->action_refresh_post_cache()` method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.13.0.

**Version 3.3.2 (2022/11/04)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a check for `WpssoUtilRobots->is_enabled()` before checking for noindex.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.8.0.

**Version 3.3.1 (2022/10/04)**

* **New Features**
	* None.
* **Improvements**
	* Minor message string updates.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.5.0.

**Version 3.3.0 (2022/09/25)**

* **New Features**
	* None.
* **Improvements**
	* Added support for Age group <code>&#91;age_group&#93;</code> and Adult oriented <code>&#91;adult&#93;</code>.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.4.0.

**Version 3.2.1 (2022/09/16)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed XML markup by removing `esc_xml()` which creates invalid XML.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.3.0.

**Version 3.2.0 (2022/09/15)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for the new 'product:size:type' enumeration values from WPSSO Core v13.3.0.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.3.0.

**Version 3.1.0 (2022/08/26)**

* **New Features**
	* None.
* **Improvements**
	* French language translation updates.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.1.0.

**Version 3.0.1 (2022/08/24)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added `esc_xml()` and `esc_html()` to the feed XML content.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.0.0.

**Version 3.0.0 (2022/08/24)**

* **New Features**
	* Removed dependency on the WPSSO Core Premium edition plugin.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v13.0.0.

== Upgrade Notice ==

= 5.1.0-b.1 =

(2023/01/13) Removed the "Save Plugin Settings" and "Change to View" buttons from the Google Merchant settings page.

= 5.0.0 =

(2023/01/11) Changed the default feed URL from '/merchant-feed/' to '/google-merchant/feed/rss2/'. Renamed the SSO settings page from "Merchant Feed" to "Google Merchant".

= 4.2.0 =

(2023/01/03) Added support for a second 'size_type' value.

= 4.1.0 =

(2022/12/29) Updated the WP posts query to exclude noindex and redirected post meta as part of the query.

= 4.0.0 =

(2022/12/28) Added new XML product attributes.

= 3.4.0 =

(2022/12/07) Added an action hook to clear the locale feed XML when a product is updated.

= 3.3.2 =

(2022/11/04) Added a check for `WpssoUtilRobots->is_enabled()` before checking for noindex.

= 3.3.1 =

(2022/10/04) Minor message string updates.

= 3.3.0 =

(2022/09/25) Added support for Age group <code>&#91;age_group&#93;</code> and Adult oriented <code>&#91;adult&#93;</code>.

= 3.2.1 =

(2022/09/16) Fixed XML markup by removing `esc_xml()` which creates invalid XML.

= 3.2.0 =

(2022/09/15) Added support for the new 'product:size:type' enumeration values from WPSSO Core v13.3.0.

= 3.1.0 =

(2022/08/26) French language translation updates.

= 3.0.1 =

(2022/08/24) Added `esc_xml()` and `esc_html()` to the feed XML content.

= 3.0.0 =

(2022/08/24) Removed dependency on the WPSSO Core Premium edition plugin.

