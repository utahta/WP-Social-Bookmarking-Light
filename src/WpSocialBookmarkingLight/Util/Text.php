<?php

namespace WpSocialBookmarkingLight\Util;

/**
 * Class Text
 * @package WpSocialBookmarkingLight\Util
 */
class Text
{
    /**
     * text localize
     *
     * @param $val
     * @return string
     */
    public static function locale($val)
    {
        return __($val, WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN);
    }
}
