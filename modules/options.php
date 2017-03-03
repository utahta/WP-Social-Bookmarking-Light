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
 * current options
 *
 * @deprecated
 *
 * @return array
 */
function wp_social_bookmarking_light_options()
{
    $o = new \WpSocialBookmarkingLight\Option();
    return $o->getAll();
}

/**
 * save options
 *
 * @deprecated
 *
 * @param array $data ($_POST)
 * @return array
 */
function wp_social_bookmarking_light_save_options($data)
{
    $o = new \WpSocialBookmarkingLight\Option();
    return $o->save($data);
}

/**
 * @deprecated
 *
 * restore default options
 */
function wp_social_bookmarking_light_restore_default_options()
{
    $o = new \WpSocialBookmarkingLight\Option();
    return $o->restoreDefaultOption();
}
