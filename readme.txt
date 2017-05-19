=== Auto Terms of Service and Privacy Policy ===
Contributors: cliffpaulick, freemius
Tags: comments, legal, terms and conditions, privacy policy, Facebook, Google, cookies, compliance, affiliate
Requires at least: 3.1
Tested up to: 4.7.4
Stable tag: 1.8.1
License: GPL version 3 or any later version
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Put your own details into a modified version of Automattic's "Terms of Service" and "Privacy Policy". 3 shortcodes available.

== Description ==

Puts your own information into a version of Automattic's <a href="http://en.wordpress.com/tos/">Terms of Service</a> and <a href="http://automattic.com/privacy/">Privacy Policy</a>, both available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Sharealike</a> license, that have been modified to exclude specifics to Automattic (like mentions of "JetPack", "WordPress.com", and "VIP") and have more generic language that can apply to most any site or service provider, including single sites, subscription sites, blog networks, and others.

<strong>Enter your organization's information in the plugin's settings page. Then you can add your own page and use one of the 3 available shortcodes: *[my_terms_of_service_and_privacy_policy]*, *[my_terms_of_service]*, and/or *[my_privacy_policy]*</strong>

= Highlights =
* Quick and easy setup in *wp-admin -> Settings -> Auto TOS & PP*
* Just 3 simple shortcodes
* No WP_DEBUG messages
* Shortcode output is valid HTML 4.01 Strict and HTML 5 Experimental
* Responsive plugin developer

= Notes =
* This plugin generates output that is usable throughout your site via 3 shortcodes, such as in a Page that you manually create. It does NOT create this page for you and does not add any links to wherever you put one or more of these shortcodes (e.g. yoursite-dot-com/terms). Such functionality may be available in the future, possibly for a fee.
* This plugin's output is not translatable, as it is a legal document specific to the United States. However, if you want to create your own version, you may want to reference <a href="https://github.com/Automattic/legalmattic">Legalmattic on Github</a>.
* This plugin utilizes Freemius. All data collected via Freemius will be available to both Freemius and this plugin's author to be used in responsible ways. By opting-in to Freemius, you'll help us learn how I can make this plugin better and possibly communicate with you regarding the plugin's development.
* <strong>This plugin will eventually offer paid add-ons. I have some ideas of what I think you will want and benefit from, but I'd really love to hear your ideas!</strong> (If shared with me, they become all mine without attribution or compensation required, so thanks in advance!)
* This plugin and its author are not affiliated with or endorsed by Automattic Inc.

Disclaimer: <a href="http://wpautoterms.com/">Clifford Paulick of TourKick.com</a> is not an attorney. Additional disclaimer text within the plugin file(s).

If you are a coder, you are welcome to view the plugin's code and submit pull requests: <a href="https://github.com/cliffordp/Auto-Terms-of-Service-and-Privacy-Policy">https://github.com/cliffordp/Auto-Terms-of-Service-and-Privacy-Policy</a>

== Installation ==

After automatically installing to wp-content/plugins/ or manually installing to wp-content/mu-plugins/ (Must Use, i.e. Always Active), enter your organization's information in the plugin's settings page.

== Frequently Asked Questions ==

Why?

Because Terms and Conditions and Privacy Policies are important to have and because some businesses don't understand their importance or are unable or unwilling to afford an attorney to draft custom T&C and Privacy Policy documents. This plugin allows you to create your own, based on Automattic's. Thanks to Automattic for telling us we can use and abuse theirs.

How?

Go to the plugin's Settings page and complete all required fields. Then change shortcode output setting from Off to On and the shortcodes will work.

Language other than English?

As this plugin generates Terms of Service and Privacy Policy documents, which is worded specifically for the United States of America, I'd say it's not smart to translate the output text into another language. If you have a legal document in another language that you want to use (and have permission to use), just put its full text in a WordPress page and link to it where needed (i.e. put your full text in a page instead of using this plugin).

MultiSite?

There aren't any multisite network settings. Must be activated and setup on each site individually.

How can I give back?

Please rate the plugin, Tweet about it (mention @TourKick if you'd like to give me warm fuzzies), contribute code enhancements, etc. I plan to release a premium version or add-ons. Once those become available, you can support this project by buying the ones useful to you.

How do I add a "to the top" link?

One of these <a href="https://wordpress.org/plugins/tags/to-top/" target="_blank">"To Top" Plugins</a> may work for you. They're site-wide plugins, not anything specific to this Auto TOS & PP plugin.

== Screenshots ==
1. Administrator-only view of shortcode output before plugin has been customized or if On/Off setting is Off. Screenshot shows output of [my_terms_of_service_and_privacy_policy], [my_terms_of_service], and [my_privacy_policy]

2. View of editing the plugin's settings

3. Page/Post Editor screen, using this plugin's shortcode: [my_terms_of_service]

4. Output of [my_terms_of_service] after customizing this plugin

5. Page/Post Editor screen, using this plugin's shortcode: [my_privacy_policy]

6. Output of [my_privacy_policy] after customizing this plugin

7. Page/Post Editor screen, using this plugin's shortcode: [my_terms_of_service_and_privacy_policy]

8. Output of [my_terms_of_service_and_privacy_policy]. Creates in-page links for quick access.

9. Scroll or Click to Privacy Policy when using [my_terms_of_service_and_privacy_policy] shortcode, further down the same page as the previous screenshot. Separated by horizontal line with class of "auto-tos-pp".

== Changelog ==
* Changelog DIFFs for all versions are available at <a href="http://plugins.trac.wordpress.org/browser/auto-terms-of-service-and-privacy-policy/trunk" target="_blank">WordPress SVN</a>.

= Version 1.8.1 =
* May 15, 2017
* Update Freemius from version 1.2.1.5 to 1.2.1.6.1
* Changed license from "GPL version 2 or any later version" to "GPL version 3 or any later version". See <a href="https://www.gnu.org/licenses/rms-why-gplv3.html">GNU.org's Why Upgrade to GPLv3</a> and <a href="https://creativecommons.org/share-your-work/licensing-considerations/compatible-licenses/">Creative Commons' Compatible Licenses</a> for details.

= Version 1.8 =
* December 19, 2016
* Implemented Freemius integration (version 1.2.1.5)
* Updated plugin compatibility to WordPress 4.7

= Version 1.7 =
* September 30, 2015
* Now all plugin settings page fields can include apostrophes ('). Just don't use double-quotes (").
* Added Text Domain to plugin's header
* Updated plugin compatibility from WordPress 4.3 to 4.3.1

= No Version Update =
* September 14, 2015
* Moved screenshot files from trunk to assets so .zip is smaller (i.e. faster updates and no actual plugin changes).

= Version 1.6 =
* April 27, 2015
* Added filters: before and after TOS heading and PP heading; end of TOS and end of PP. Allows you to add custom text via Child Theme functions.php or a Functionality Plugin.
* Refactored code (more OOP / use of functions).
* Moved 'Back To Top' links to end of TOS and PP instead of immediately after each heading.
* Changed TOS and PP main headings from 'h3' to 'h2'.
* Replaced 'hr' with formatted 'div'. Override color and height of 1px black line by adding more specific CSS. For example, div.auto-tos-pp-separator{ border-bottom-color: green !important; border-bottom-width: 10px !important; }
* Added HTML comment with plugin version number and which shortcode is used to assist with potential support requests.
* Added protection from calling plugin PHP file directly.
* WordPress compatibility version bump. Tested with WordPress version 4.3-alpha-32297
* Added 'Upgrade Notice' section to plugin description.

= Version 1.5 =
* April 26, 2015
* Shortcode output is now valid for both HTML 4.01 Strict and HTML 5. Changed anchor links from 'a name' to 'h3 id' to validate as HTML 5.

= Version 1.4.4 =
* April 20, 2015
* WordPress compatibility version bump. Tested with WP version 4.2-RC1-32175.
* Added greeting box to top of wp-admin Settings page.
* Updated screenshot for the "coming soon" text.

= Version 1.4.3 =
* April 28, 2014
* Fix for "Possessive Name" not displaying accurately in settings page due to apostrophe. However, the value with the apostrophe was saved properly, and the Possessive Name was displayed properly for users on the front-end.
* Added direct link to Settings page from Plugins page

= Version 1.4.2 =
* February 13, 2014
* Added class='auto-tos-pp' (and additional classes) to main headings, horizontal lines, and Back to Top links -- could use .auto-tos-pp { display:none; } to not show things

= Version 1.4.1 =
* February 13, 2014
* Updated plugin's description
* Hyperlinked to plugin's settings page for shortcode output (when appropriate)

= Version 1.4 =
* February 12, 2014
* Added plugin settings page so you don't have to edit the plugin's actual code.
* Added link back to the table of contents (to the top) for the [my_terms_of_service_and_privacy_policy] shortcode.
* Privacy Policy edited in line with Automattic's --> September 18, 2013: Added that blog commenter email addresses are disclosed to administrators of the blog where the comment was left.

= Version: 1.3.2012.12.29 =
* Fixed 2 more hard-coded references to "WordPress" within that same paragraph. That should be all of them now.

= Version: 1.2.2012.12.29 =
* Fixed a <a href="http://wordpress.org/support/topic/update-required?replies=1#post-3439913" target="_blank">hard-coded reference to "WordPress"</a>, reported by kc22033. Thanks!
* Checked WordPress' Terms of Service and Privacy Policy for updates, and there weren't any changes since this plugin's creation.

= Version: 1.1.2012.12.28 =
* Changed shortcodes to return instead of echo, to fix the shortcode display issue. Thanks to <a href="http://profiles.wordpress.org/birgire" target="_blank">birgire</a>.
* Fixed link anchor text to use the name specified in the settings, to fix the issue of displaying hard coded "Terms of Use" and "Privacy Policy" anchor text in the table of contents for the [my_terms_of_service_and_privacy_policy] shortcode.

= Version: 1.0.2012.09.12 =
* Initial release.

== Upgrade Notice ==
* WARNING: All direct plugin file edits (required in versions prior to v1.4) will be lost and will need to be entered via the plugin's settings page. Make sure you backup your information BEFORE UPDATING to a new version.