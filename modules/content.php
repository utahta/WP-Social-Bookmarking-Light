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

/**
 * html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 * @return string
 */
function wp_social_bookmarking_light_output($services, $link, $title)
{
    $wp = new WpSocialBookmarkingLight($link, $title, get_bloginfo('name'));
    $class_methods = wp_social_bookmarking_light_get_class_methods();
    $out = '';
    foreach (explode(",", $services) as $service) {
        $service = trim($service);
        if ($service === '') {
            continue;
        }

        if (in_array($service, $class_methods)) {
            $out .= '<div class="wsbl_' . $service . '">' . call_user_func(array(
                    $wp,
                    $service
                )) . '</div>'; // WpSocialBookmarkingLight method
        } else {
            $out .= "<div>[`$service` not found]</div>";
        }
    }

    if ($out == '') {
        return $out;
    }
    return "<div class='wp_social_bookmarking_light'>{$out}</div><br class='wp_social_bookmarking_light_clear' />";
}

/**
 * echo html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 */
function wp_social_bookmarking_light_output_e($services = null, $link = null, $title = null)
{
    if ($services == null) {
        $options = wp_social_bookmarking_light_options();
        $services = $options['services'];
    }
    echo wp_social_bookmarking_light_output($services, $link, $title);
}

/**
 * add_action wp_head
 */
function wp_social_bookmarking_light_wp_head()
{
    $out = "<!-- BEGIN: WP Social Bookmarking Light -->\n";

    // Load options
    $options = wp_social_bookmarking_light_options();
    $services = explode(",", $options['services']);

    // mixi-check-robots
    if (in_array('mixi', $services)) {
        $out .= '<meta name="mixi-check-robots" content="' . $options['mixi']['check_robots'] . '"/>' . "\n";
    }

    // tumblr
    if (in_array('tumblr', $services)) {
        $out .= '<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>';
    }

    // facebook
    if (in_array('facebook_like', $services) ||
        in_array('facebook_share', $services) ||
        in_array('facebook_send', $services)
    ) {
        $version = $options['facebook']['version'];
        if ($version == "html5" || $version == "xfbml") {
            $locale = $options['facebook']['locale'];
            $locale = ($locale == '' ? 'en_US' : $locale);
            $out .= <<<HTML
<script>(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/$locale/sdk.js#xfbml=1&version=v2.7";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

HTML;
        }
    }

    // css
    $out .= <<<HTML
<style type="text/css">
    ${options['styles']}
</style>

HTML;

    $out .= "<!-- END: WP Social Bookmarking Light -->\n";
    echo $out;
}

/**
 * true if can be displayed, false if not
 *
 * @return bool
 */
function wp_social_bookmarking_light_is_enabled()
{
    if (is_feed() || is_404() || is_robots() || (function_exists('is_ktai') && is_ktai())) {
        return false;
    }

    $options = wp_social_bookmarking_light_options();
    if ($options['single_page'] && !is_singular()) {
        return false;
    }
    if (!$options['is_page'] && is_page()) {
        return false;
    }

    global $wp_current_filter;
    if (in_array('get_the_excerpt', (array)$wp_current_filter)) {
        return false;
    }

    if (get_query_var('amp', false) !== false) {
        return false;
    }

    return true;
}

/**
 * Add the Share Buttons to the content.
 * add_filter "the_content"
 *
 * @param string $content
 * @return string
 */
function wp_social_bookmarking_light_the_content($content)
{
    if (!wp_social_bookmarking_light_is_enabled()) {
        return $content;
    }

    $options = wp_social_bookmarking_light_options();
    $out = wp_social_bookmarking_light_output($options['services'], get_permalink(), get_the_title());
    if ($out == '') {
        return $content;
    }
    if ($options['position'] === 'top') {
        return "{$out}{$content}";
    } elseif ($options['position'] === 'bottom') {
        return "{$content}{$out}";
    } elseif ($options['position'] === 'both') {
        return "{$out}{$content}{$out}";
    }
    return $content;
}

/**
 * wp_footer function
 */
function wp_social_bookmarking_light_wp_footer()
{
    $out = "<!-- BEGIN: WP Social Bookmarking Light -->\n";

    // load options
    $options = wp_social_bookmarking_light_options();
    $services = explode(",", $options['services']);

    /*
     * load javascript
     */
    // twitter
    if (in_array('twitter', $services)) {
        $out .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>\n";
    }

    // evernote
    if (in_array('evernote', $services)) {
        $out .= '<script type="text/javascript" src="http://static.evernote.com/noteit.js"></script>' . "\n";
    }

    // Google +1
    if (in_array('google_plus_one', $services)) {
        $lang = $options['google_plus_one']['lang'];
        $out .= '<script src="https://apis.google.com/js/platform.js" async defer>'
            . '{lang: "' . $lang . '"}'
            . "</script>\n";
    }

    // pinterest
    if (in_array('pinterest', $services)) {
        if ($options['pinterest']['type'] === 'all') {
            $data_pin_hover = $data_pin_shape = $data_pin_color = $data_pin_lang = $data_pin_height = '';
        } else {
            $data_pin_hover = 'data-pin-hover="true"';
            $shape = $options['pinterest']['shape'];
            $data_pin_shape = $shape === 'round' ? 'data-pin-shape="round"' : '';
            $data_pin_color = 'data-pin-color="' . $options['pinterest']['color'];
            $data_pin_lang = 'data-pin-lang="' . $options['pinterest']['lang'];
            $data_pin_height = '';
            if ($options['pinterest']['size'] === 'large') {
                $data_pin_height = $shape === 'round' ? 'data-pin-height="32"' : 'data-pin-height="28"';
            }
        }
        $out .= '<script type="text/javascript" async defer  '
            . $data_pin_shape . ' '
            . $data_pin_color . ' '
            . $data_pin_lang . ' '
            . $data_pin_height . ' '
            . $data_pin_hover . ' '
            . 'src="//assets.pinterest.com/js/pinit.js"></script>' . "\n";
    }

    $out .= "<!-- END: WP Social Bookmarking Light -->\n";
    echo $out;
}
