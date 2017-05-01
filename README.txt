=== Audience Segments ===
Contributors: Alquemie
Donate link: https://www.alquemie.net
Tags: marketing, audience, buyer journey, google analytics, reporting
Requires at least: 4.0
Tested up to: 4.7.4
Stable tag: 1.1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Custom taxonomies based on target audience segments and phases of the buyers journey.

== Description ==

This plugin adds custom taxonomies to builtin and custom post types that identify the target audience and phase of the buyers journey.  The data is also stored in the dataLayer for Google Tag Manager in order to allow for custom tracking.

The added taxonomies are:
* Division (Business Unit responsible for the content)
* Subject Matter (Product or Service)
* Buying State
* Primary Audience
* Secondary Audience

The plugin expands upon the built in WordPress functionality so it is possible to display the content using menus, widgets and related posts plugins.

== Installation ==

1. Upload `audience` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Audience Segments" settings to configure

== Screenshots ==

1. The settings screen allows you to select post types where the fields are available and set default values for each taxonomy

== Changelog ==

= 1.1.0 =
* Renamed fields to better align with enterprise needs
* Bug fixes
* Code Optimization
* File structure updates for WordPress.org hosting

= 1.0 =
* Initial public release
