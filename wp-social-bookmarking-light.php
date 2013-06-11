<?php
/*
Plugin Name: WP Social Bookmarking Light
Plugin URI: http://www.ninxit.com/blog/2010/06/13/wp-social-bookmarking-light/
Description: This plugin inserts social share links at the top or bottom of each post.
Author: utahta
Author URI: http://www.ninxit.com/blog/
Version: 1.7.5
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
define( "WP_SOCIAL_BOOKMARKING_LIGHT_DIR", WP_PLUGIN_DIR."/wp-social-bookmarking-light" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_URL", WP_PLUGIN_URL."/wp-social-bookmarking-light" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL", WP_SOCIAL_BOOKMARKING_LIGHT_URL."/images" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN", "wp-social-bookmarking-light" );

// _e() local domain
function _el($val){
    _e($val, WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN);
}
function __l($val){
    __($val, WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN);
}

// load modules
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/options.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/services.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/admin.php';
require_once WP_SOCIAL_BOOKMARKING_LIGHT_DIR.'/modules/content.php';

// multilingualization
load_plugin_textdomain( WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN, false,
                        "wp-social-bookmarking-light/po" );

// initialize
function wp_social_bookmarking_light_init()
{
    add_action('wp_head', 'wp_social_bookmarking_light_wp_head');
    add_action('wp_footer', 'wp_social_bookmarking_light_wp_footer');
    add_filter('the_content', 'wp_social_bookmarking_light_the_content');
    add_action('admin_menu', 'wp_social_bookmarking_light_admin_menu');
}
add_action( 'init', 'wp_social_bookmarking_light_init' );

?>
