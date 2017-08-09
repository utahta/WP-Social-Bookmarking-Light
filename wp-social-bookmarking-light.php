<?php
/*
Plugin Name: WP Social Bookmarking Light
Plugin URI: https://github.com/utahta/WP-Social-Bookmarking-Light
Description: This plugin inserts social share links at the top or bottom of each post.
Author: utahta
Author URI: https://github.com/utahta/WP-Social-Bookmarking-Light
Version: 2.0.2
*/
/*
Copyright 2010 utahta (email : labs.ninxit@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// settings
define("WP_SOCIAL_BOOKMARKING_LIGHT_DIR", dirname(__FILE__));
define("WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN", "wp-social-bookmarking-light");

require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR . '/vendor/autoload.php';

// Load modules (deprecated and will be removed on the next minor update)
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR . '/modules/options.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR . '/modules/content.php';

// multi language
load_plugin_textdomain(WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN, false, "wp-social-bookmarking-light/po");

/**
 * initialize
 */
\WpSocialBookmarkingLight\Plugin::init();
