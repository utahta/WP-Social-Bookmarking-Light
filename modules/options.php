<?php
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

function wp_social_bookmarking_light_default_options()
{
    return array( "services" => "hatena, hatena_users, facebook, google_buzz, yahoo, livedoor, friendfeed, tweetmeme",
                  "position" => "top",
                  "single_page" => true,
                  "is_page" => true,
                  "mixi" => array('check_key' => '',
                                   'check_robots' => 'noimage',
                                   'button' => 'button-3'),
                  "twitter" => array('via' => "",
                                      'lang' => "en",
                                      'count' => 'horizontal',
                                      'width' => '130',
                                      'height' => '20'),
                  "hatena_button" => array('layout' => 'standard'),
                  'facebook_like' => array('action' => 'like',
                                            'colorscheme' => 'light'),
    );
}

function wp_social_bookmarking_light_options()
{
    $options = get_option("wp_social_bookmarking_light_options", array());
    
    // array merge recursive overwrite (1 depth)
    $default_options = wp_social_bookmarking_light_default_options();
    foreach( $default_options as $key => $val ){
        if(is_array($default_options[$key])){
            if(!is_array($options[$key])){
                $options[$key] = array();
            }
            $options[$key] = array_merge($default_options[$key], $options[$key]);
        }
    }
    return array_merge( wp_social_bookmarking_light_default_options(), $options );
}
