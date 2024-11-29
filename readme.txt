=== SureFeedback Client Site ===
Contributors: brainstormforce, 2winfactor
Donate link: https://surefeedback.com
Tags: project, huddle, child, feedback, design, approval
Requires at least: 4.7
Tested up to: 6.7
Stable tag: 1.2.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides a secure connection between your SureFeedback parent site and your client sites, syncing identities so clients can use their WordPress identities for commenting.

== Description ==

This is the Child plugin for [SureFeedback](https://surefeedback.com)

The SureFeedback plugin lets you collect sticky note-style feedback on page designs and web projects. It’s so easy to use. Clients can select specific areas of your design, point, click, and type constructive comments on top of your mockups and site designs.

Using SureFeedback, the client can show as well as tell, providing targeted feedback for a more efficient workflow. 

SureFeedback is a self-hosted client feedback system that allows you to get feedback on an endless amount of client sites from one central dashboard.

The [SureFeedback](https://surefeedback.com) Client Site plugin is used to securely sync multiple WordPress client identities with your SureFeedback parent site.

All you need to do is install the plugin on the site you want feedback on and it's ready to go.

**Features include:**

* Connect and sync your client’s identities with your SureFeedback projects.
* No login or registration is required if your client is logged into their own site.
* Choose which roles you want to allow for commenting.
* Allow non-users (guests) to leave comments.
* Optionally enable commenting on the WordPress admin.
* White label support

== Installation ==

1. Go to `Plugins -> Add New` and search for SureFeedback Client Site
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to `Settings -> SureFeedback` to configure the plugin options.

== Frequently Asked Questions ==

= What is the purpose of this plugin? =

The purpose of this plugin is to make it simple to get targeted feedback from clients on web designs. All you have to do is install the [SureFeedback](https://surefeedback.com) plugin and let your clients select areas of your design to add their own comments. Everything is tracked within the plugin. It's so easy to use!

== Changelog ==

= 1.2.6 =
* Improvement: Compatibility to WordPress 6.7

= 1.2.5 =
* Improvement: Improved code quality syntax and security checks for better coding standards and practices.

= 1.2.4 =
* Improvement: Compatibility to WordPress 6.6

= 1.2.3 =
* Improvement: Updated help document links.

= 1.2.2 =
* Improvement: Compatibility to WordPress 6.5

= 1.2.1 =
* Improvement: Compatibility to WordPress 6.4.1

= 1.2 =
* Updated banner images.

= 1.1 =
* Rebranding ProjectHuddle as SureFeedback.

= 1.0.35 = 
* Improvement: Implemented nonce verification and data sanitization to enhance security.

= 1.0.34 =
* Improvement: Compatibility to WordPress 6.3

= 1.0.33 =
* Improvement: Compatibility to WordPress 6.0.2

= 1.0.32 =
* Improvement: Compatibility to WordPress 6.0.
* Improvement: Help link added for detailed instructions of site connection.
* Improvement: Comment access settings description and label changes.

= 1.0.31 =
* Improvement: Added "Visit Dashboard Site" button on the connection page.
* Improvement: Allow Guests to Comment setting text changed as Allow Site Visitors to view and add comments.
* Improvement: Renamed settings menu title from "Feedback" to "SureFeedback" and added white label support.
* Improvement: Connection details input box disabled after connection is established.

= 1.0.30 =
* Improvement: Compatibility to WordPress 5.9.

= 1.0.29 =
* Improvement: Compatibility to WordPress 5.8.1.

= 1.0.28 =
* Fix issue with guest commenting sometimes not working.

= 1.0.27 =
* Remove unnecessary admin notices regarding caching for Flywheel and WPEngine.

= 1.0.26 =
* Use localstorage for access token to eliminate issues with client site caching.

= 1.0.25 =
* Fix issue with display names containing apostrophes.

= 1.0.24 =
* Add notices for WPEngine, Flywheel cache exclusions.

= 1.0.23 =
* Fix issue with visiting access links to subpages not storing cookie correctly on other pages.

= 1.0.22 =
* Fix compatibility issue with Elementor.

= 1.0.21 =
* Fix compatibility issue with Divi.

= 1.0.20 =
* Fix compatibility issue with Beaver Builder.

= 1.0.19 =
* Fix compatibility issue with Fusion Builder.

= 1.0.16 =
* Fix issue with author not applying in white label options.

= 1.0.15 =
* Make sure widget only loads once per page in case of duplicate wp_footer calls.

= 1.0.14 =
* Add admin check to gettext filter to scope to plugin page before running function.

= 1.0.13 =
* Fix issue for accounts who's emails contain a "+" sign.

= 1.0.12 =
* Hide on Oxygen builder pages.

= 1.0.11 =
* Scope gettext calls to plugins page only to prevent logging excessive functions.
* Use PH_HIDE_WHITE_LABEL to hide white label tab from plugin settings.

= 1.0.10 =
* Disable comment interface on elementor builder.

= 1.0.9 =
* Fix cookie expiration date

= 1.0.8 =
* Allow access links to load comment interface (must use SureFeedback 3.6.17+)

= 1.0.7 =
* Defer script to not interfere with html parser in older browsers

= 1.0.6 =
* Update minimum WordPress requirement
* Make sure it cannot be activated if Parent plugin is activated on same installation.

= 1.0.5 =
* Add white label options.

= 1.0.4 =
* Update readme description and plugin title.

= 1.0.3 =
* Update readme description and plugin title.

= 1.0.2 =
* Fix manual import.

= 1.0.1 =
* Add access token override.

= 1.0.0 =
* Initial release
