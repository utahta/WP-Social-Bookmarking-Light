<?php

namespace WpSocialBookmarkingLight\Util;

/**
 * Class Url
 * @package WpSocialBookmarkingLight\Util
 */
class Url
{
    /**
     * Returns base url
     * (in public directory)
     *
     * @param string $path
     * @return string
     */
    public static function base($path = "")
    {
        return plugins_url("wp-social-bookmarking-light/public" . self::lslash($path));
    }

    /**
     * Returns images url
     *
     * @param string $path
     * @return string
     */
    public static function images($path = "")
    {
        return self::base("images" . self::lslash($path));
    }

    /**
     * Returns css url
     *
     * @param string $path
     * @return string
     */
    public static function css($path = "")
    {
        return self::base("css" . self::lslash($path));
    }

    /**
     * @param $path
     * @return string
     */
    private static function lslash($path)
    {
        if ($path && is_string($path)) {
            $path = "/" . ltrim($path, "/");
        }
        return $path;
    }
}
