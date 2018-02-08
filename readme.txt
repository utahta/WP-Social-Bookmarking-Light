=== WP Social Bookmarking Light ===
Contributors: utahvich
Donate link: https://gumroad.com/l/rWLrL
Tags: social, bookmarks, bookmarking, Hatena, Twitter, Facebook, Tumblr, Google Bookmark, Delicious, Digg, reddit, LinkedIn, Instapaper, StumbleUpon, mixi, gree, atode, toread, line, pocket, Pinterest
Requires at least: 4.0.0
Tested up to: 4.7
Stable tag: 2.0.7

This plugin inserts social share links at the top or bottom of each post.

== Description ==

This plugin inserts social share links at the top or bottom of each post. 
For theme developers, social share links can be added by PHP code or by using shortcode. Check documentation for this use-case.

This plugin documentation can be found [here](https://github.com/utahta/WP-Social-Bookmarking-Light/wiki).

This is the list of used social sites:

*  Hatena
*  Facebook Like Button
*  Facebook Share Button
*  Facebook Send Button
*  Twitter
*  Tumblr
*  Google Bookmark
*  Google +1
*  Delicious
*  Digg
*  reddit
*  LinkedIn
*  Instapaper
*  StumbleUpon
*  mixi Check
*  mixi Like
*  GREE Social Feedback
*  atode (toread)
*  LINE
*  Pocket
*  Pinterest

== Installation ==

1. Upload this directory to the 'wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open 'WP Social Bookmarking Light Options' control panel through the 'Settings' menu and change configurations (This step is not required, the plugin has default settings)

== Screenshots ==

1. Embed share buttons in your website.
2. Admin settings page.

== Changelog ==

= 2.0.7 =
* Fixed: google plus option [#60](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/60)

= 2.0.6 =
* Fixed: pinterest behavior [#59](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/59)

= 2.0.5 =
* Added: wsbl_embed shortcode [#56](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/56)

= 2.0.4 =
* minimum fix

= 2.0.3 =
* Added: a new line button design [#53](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/53)

= 2.0.2 =
* Added: iframe option to twitter [#52](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/52)

= 2.0.1 =
* Fixed: for PHP 5.3 [#50](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/50)
* Note: We don't intend to actively support on PHP 5.5 or lower, but if you get some problems, please report it. We will fix it.

= 2.0.0 =
* Breaking Changes: Drop support for PHP 5.5 or lower, Require PHP 5.6 or higher [#46](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/46)
* Breaking Changes: Remove terminated some services
* Important: If your WordPress site working on PHP 5.5 or lower, please continue to use v1.9.2 version

= 1.9.2 =
* Workaround: disable social buttons on AMP [#47](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/47)

= 1.9.1 =
* Fixed: deprecated in php7. [#44](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/44)

= 1.9.0 =
* Fixed: mixi button. [#41](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/41)
* Fixed: replaced WP_PLUGIN_URL with plugins_url [#42](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/42)

= 1.8.8 =
* Fixed: WP_SOCIAL_BOOKMARKING_LIGHT_DIR for docker based PaaS. [#38](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/38)

= 1.8.7 =
* Fixed: Hatena button deleted protocol to work on https site. [#37](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/37)

= 1.8.6 =
* Updated: facebook sdk to 2.7 from 2.0 [#35](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/35)

= 1.8.5 =
* Fixed: notice warning message "is_comment_popup" [#33](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/33)

= 1.8.4 =
* Fixed: Included unnecessary string "twitter{count}" [#32](https://github.com/utahta/WP-Social-Bookmarking-Light/pull/32)

= 1.8.3 =
* Fixed: Included unnecessary string (like Tweet, Pocket) in the excerpt

= 1.8.2 =
* Fixed: Undefined index: dnt (Twitter Button)

= 1.8.1 =
* Updated: Google +1 Button
* Added: Donate

= 1.8.0 =
* Added: Pinterest
* Fixed: Twitter Button
* Removed: twib, tweetmeme, buzzurl

= 1.7.10 =
* Fixed: XSS Vulnerability

= 1.7.9 =
* Updated: Added line option to select protocol

= 1.7.8 =
* Updated: Facebook js sdk version 2.0

= 1.7.7 =
* Fixed: https security warning in IE. (Hatena, Facebook and Twitter Button)
* Improved: Hatena Button layout

= 1.7.6 =
* Improved: Facebook Like, Share and Send Button
* Improved: CSS
* Bug fixed: default option

= 1.7.5 =
* Added: Pocket Button

= 1.7.4 =
* Fixed: Google +1
* Added: both option

= 1.7.3 =
* Added: LINE Button
* Improved: Custom CSS
* Bug fixed: Facebook Like Button (iframe)
* Removed: Grow! Button

= 1.7.2 =
* Bug fixed: Grow! Button.

= 1.7.1 =
* Updated: replaced script of hatena to no tracking version.

= 1.7.0 =
* Added: Grow! Button.
* Fixed: Validation fails.

= 1.6.9 =
* Added: mixi Like Button.
* Improved: Some options.

= 1.6.8 =
* Added: Google +1 Button

= 1.6.7 =
* Bug fixed: Facebook like button did not work on IE.

= 1.6.6 =
* Added: atode(toread) Button.
* Added: Padding option.
* Improved: Instapaper and evernote.

= 1.6.5 =
* Added: Facebook Send Button.
* Added: Options for gree.
* Updated: Options for Facebook Like Button.

= 1.6.4 =
* Updated: Administration Page.

= 1.6.3 =
* Bug fixed: The style was broken on some themes.

= 1.6.2 =
* Bug fixed: Parser error on PHP4. milligramme++ and rik_wuts++.

= 1.6.1 =
* Added: Service code check function.

= 1.6.0 =
* Added: Hatena Bookmark Button.
* Updated: Administration Page.
* Bug fixed: The twitter button did not work on the top page. [yuya-takeyama](https://gist.github.com/675159)++
* Bug fixed: The mixi button did not work on the top page.

= 1.5.2 =
* Added Facebook Like Button.

= 1.5.1 =
* Alter &lt;style&gt; to &lt;style type="text/css"&gt;

= 1.5.0 =
* Added mixi Check, GREE Social Feedback.

= 1.4.3 =
* Bug fix: img style.

= 1.4.2 =
* Bug fix: the icon for evernote not displayed on IE.

= 1.4.1 =
* Bug fix: some unable to clip to Evernote.

= 1.4.0 =
* Added Evernote, Instapaper and StumbleUpon.

= 1.3.0 =
* Added Yahoo!Buzz, reddit, LinkedIn and TwitterButton. 

= 1.2.0 =
* Added "Is Page" option.
* The name of the option was changed from "Single Page" to "Is Singular". 

= 1.1.0 =
* Bug fix: li style.
* Added wp_social_bookmarking_light_output_e function. It can position the social-links manually inside your template.

= 1.0.1 =
* Bug fix: img style.

= 1.0.0 =
* First Release

