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
 * @deprecated
 *
 * @param string $services
 * @param string $link
 * @param string $title
 * @return string
 */
function wp_social_bookmarking_light_output($services, $link, $title)
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    return $plugin->getBuilder()->content($services, $link, $title);
}

/**
 * echo html format
 *
 * @deprecated
 *
 * @param string $services
 * @param string $link
 * @param string $title
 */
function wp_social_bookmarking_light_output_e($services = null, $link = null, $title = null)
{
    if ($services == null) {
        $o = new \WpSocialBookmarkingLight\Option();
        $options = $o->getAll();
        $services = $options['services'];
    }
    echo wp_social_bookmarking_light_output($services, $link, $title);
}

/**
 * add_action wp_head
 * @deprecated
 */
function wp_social_bookmarking_light_wp_head()
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    $plugin->head();
}

/**
 * Add the Share Buttons to the content.
 * add_filter "the_content"
 *
 * @deprecated
 *
 * @param string $content
 * @return string
 */
function wp_social_bookmarking_light_the_content($content)
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    return $plugin->theContent($content);
}

/**
 * wp_footer function
 * @deprecated
 */
function wp_social_bookmarking_light_wp_footer()
{
    $plugin = new \WpSocialBookmarkingLight\Plugin();
    $plugin->footer();
}
