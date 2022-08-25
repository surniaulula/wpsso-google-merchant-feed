=== WPSSO Google Merchant Feed XML ===
Plugin Name: WPSSO Google Merchant Feed XML
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
Tested Up To: 6.0.2
WC Tested Up To: 6.8.2
Stable Tag: 3.0.1

Google Merchant Feed XMLs for WooCommerce, Easy Digital Downloads, and Custom Products.

== Description ==

<!-- about -->

**WPSSO Google Merchant Feed XML add-on provides XML product feeds for Google Merchant Center in your site's language(s).**

There are no plugin or add-on settings to configure &mdash; the WPSSO Google Merchant Feed XML add-on automatically retrieves all available product information in the language (aka locale) requested.

A supported e-commerce plugin, like WooCommerce or Easy Digital Downloads, is suggested but not required &mdash; the WPSSO Google Merchant Feed XML add-on can retrieve custom product information entered in the Document SSO metabox (WPSSO Core Premium required). WooCommerce product variations and additional product attributes are fully supported.

<h3>Multilingual</h3>

Google merchant feed XMLs for WooCommerce, Easy Digital Downloads, and custom products are automatically created in your site\'s language(s) from Polylang, WPML, or the installed WordPress languages. After activating the WPSSO Google Merchant Feed XML add-on, see the SSO &gt; Merchant Feeds settings page for the your feed URLs.

<!-- /about -->

<h3>Google Merchant Feed XML Attributes</h3>

The following XML product attributes are automatically created based on your WooCommerce, Easy Digital Downloads, and custom products (including WooCommerce product variations):

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

<h3>WPSSO Core Required</h3>

WPSSO Google Merchant Feed XML (WPSSO GMF) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

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

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. Save the plugin settings and click the "Check for Plugin Updates" button to fetch the latest version information. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can always reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

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

**Version 2.2.4 (2022/04/04)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added more debugging messages for skipped post IDs (ie. noindex or redirected).
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v12.1.0.

**Version 2.2.2 (2022/03/26)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed missing support for the custom Google Merchant Feed XML image ID or URL.
* **Developer Notes**
	* Added `$md_pre` to the `WpssoOpengraph->get_array()` method arguments.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.8.0.

**Version 2.2.1 (2022/03/23)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added debug comments to XML output when the "Add HTML Debug Messages" option is enabled.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.7.2.

**Version 2.2.0 (2022/03/18)**

* **New Features**
	* None.
* **Improvements**
	* Added support for customized site name, site description, and site home URL values from WPSSO Core.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.7.1.

**Version 2.1.0 (2022/03/07)**

* **New Features**
	* None.
* **Improvements**
	* Added a check for a missing "image_link" in the Google merchant feeds when editing a product.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.5.0.

**Version 2.0.0 (2022/03/02)**

* **New Features**
	* Added a new "Google Merchant Feed XML" image size under the SSO &gt; Advanced Settings &gt; Plugin Settings &gt; Image Sizes tab.
* **Improvements**
	* Added new "Google Merchant Feed XML" section under the Document SSO &gt; Edit Media tab.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.4.0.

**Version 1.5.3 (2022/02/25)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed inclusion of archive pages, like the WooCommerce shop page, in the Google merchant feed XML.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.2.0.

**Version 1.5.2 (2022/02/25)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed a typo in the `setAvailability()` method name.
* **Developer Notes**
	* Renamed `WpssoOpenGraph->get_all_images()` to `WpssoMedia->get_all_images()`.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.2.0.

**Version 1.5.1 (2022/02/22)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added the `vendor/**/src/` folders to the GitHub repository.
	* Updated the composer autoloader suffix from "abc0b15c5128b916f65b9175a1fc5559" to "WpssoGmf".
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.1.1.

**Version 1.4.2 (2022/02/17)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated instantiation of `Feed` and `Product` classes with full class paths.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.0.0.

**Version 1.4.1 (2022/02/15)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed the XML rewrite rule to work with WordPress '/%category%/' permalinks.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v10.3.1.

**Version 1.4.0 (2022/02/14)**

* **New Features**
	* None.
* **Improvements**
	* Added support for plain feed URLs when WordPress permalinks are disabled.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v10.3.0.

**Version 1.3.0 (2022/01/19)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed the lib/abstracts/ folder to lib/abstract/.
	* Renamed the `SucomAddOn` class to `SucomAbstractAddOn`.
	* Renamed the `WpssoAddOn` class to `WpssoAbstractAddOn`.
	* Renamed the `WpssoWpMeta` class to `WpssoAbstractWpMeta`.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.14.0.

**Version 1.2.2 (2021/11/27)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated `SucomForm::get_no_input_clipboard()` calls to `SucomForm->get_no_input_clipboard()`.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.9.0.

**Version 1.2.1 (2021/11/20)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed the `WP_Query->is_404` value before returning the XML file.
* **Developer Notes**
	* Closed the PHP output buffer before returning the XML content.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.8.1.

**Version 1.2.0 (2021/11/17)**

* **New Features**
	* None.
* **Improvements**
	* Changed the `WpssoGmfRewrite::add_rules()` action hook from 'init' to 'wp_loaded'.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.8.0.

**Version 1.1.0 (2021/11/16)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new 'wpsso_google_merchant_feed_url' filter.
	* Used the new `SucomUtil::get_available_feed_locale_names()` method (filtered by the Polylang and WPML integration modules).
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

= 3.0.1 =

(2022/08/24) Added `esc_xml()` and `esc_html()` to the feed XML content.

= 3.0.0 =

(2022/08/24) Removed dependency on the WPSSO Core Premium edition plugin.

= 2.2.4 =

(2022/04/04) Added more debugging messages for skipped post IDs (ie. noindex or redirected).

= 2.2.2 =

(2022/03/26) Fixed missing support for the custom Google Merchant Feed XML image ID or URL.

= 2.2.1 =

(2022/03/23) Added debug comments to XML output when the "Add HTML Debug Messages" option is enabled.

= 2.2.0 =

(2022/03/18) Added support for customized site name, site description, and site home URL values from WPSSO Core.

= 2.1.0 =

(2022/03/07) Added a check for a missing "image_link" in the Google merchant feeds when editing a product.

= 2.0.0 =

(2022/03/02) Added a new "Google Merchant Feed XML" image size.

= 1.5.3 =

(2022/02/25) Fixed inclusion of archive pages, like the WooCommerce shop page, in the Google merchant feed XML.

= 1.5.2 =

(2022/02/25) Fixed a typo in the `setAvailability()` method name. Renamed `WpssoOpenGraph->get_all_images()` to `WpssoMedia->get_all_images()`.

= 1.5.1 =

(2022/02/22) Updated the composer autoloader suffix from "abc0b15c5128b916f65b9175a1fc5559" to "WpssoGmf".

= 1.4.2 =

(2022/02/17) Updated instantiation of `Feed` and `Product` classes with full class paths.

= 1.4.1 =

(2022/02/15) Fixed the XML rewrite rule to work with WordPress '/%category%/' permalinks.

= 1.4.0 =

(2022/02/14) Added support for plain feed URLs when WordPress permalinks are disabled.

= 1.3.0 =

(2022/01/19) Renamed the lib/abstracts/ folder and its classes.

= 1.2.2 =

(2021/11/27) Updated `SucomForm::get_no_input_clipboard()` calls to `SucomForm->get_no_input_clipboard()`.

= 1.2.1 =

(2021/11/20) Fixed the `WP_Query->is_404` value before returning the XML file.

= 1.2.0 =

(2021/11/17) Changed the `WpssoGmfRewrite::add_rules()` action hook from 'init' to 'wp_loaded'.

= 1.1.0 =

(2021/11/16) Used the new `SucomUtil::get_available_feed_locale_names()` method (filtered by the Polylang and WPML integration modules).

= 1.0.0 =

(2021/11/10) Initial release.

