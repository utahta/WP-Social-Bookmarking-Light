<?php
/*
Plugin Name: WP Social Bookmarking Light
Plugin URI: https://github.com/utahta/WP-Social-Bookmarking-Light
Description: This plugin inserts social share links at the top or bottom of each post.
Author: utahta
Author URI: https://github.com/utahta/WP-Social-Bookmarking-Light
Version: 1.9.0
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

/**
 * returns plugin url
 *
 * @param $path
 * @return string
 */
function wp_social_bookmarking_light_url($path = "")
{
    if ($path && is_string($path)) {
        $path = "/".ltrim($path, "/");
    }
    return plugins_url("wp-social-bookmarking-light".$path);
}

/**
 * returns plugin images url
 *
 * @param $path
 * @return string
 */
function wp_social_bookmarking_light_images_url($path = "")
{
    if ($path && is_string($path)) {
        $path = "/".ltrim($path, "/");
    }
    return wp_social_bookmarking_light_url("images".$path);
}

/**
 * _e() local domain
 *
 * @param $val
 */
function _el($val){
    _e($val, WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN);
}

/**
 * __() local domain
 *
 * @param $val
 */
function __l($val){
    return __($val, WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN);
}

// load modules
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/options.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/services.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/admin.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/content.php';

// multilingualization
load_plugin_textdomain( WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN, false,
                        "wp-social-bookmarking-light/po" );

/**
 * initialize
 */
function wp_social_bookmarking_light_init()
{
    add_action('wp_head', 'wp_social_bookmarking_light_wp_head');
    add_action('wp_footer', 'wp_social_bookmarking_light_wp_footer');
    add_filter('the_content', 'wp_social_bookmarking_light_the_content');
    add_action('admin_menu', 'wp_social_bookmarking_light_admin_menu');
}
add_action( 'init', 'wp_social_bookmarking_light_init' );

