<?php

/**
 * stub get_bloginfo
 *
 * @param $key
 * @return string
 */
function get_bloginfo($key)
{
    return 'blog';
}

/**
 * stub get_option
 *
 * @param $key
 * @return mixed
 */
function get_option($key)
{
    $o = [
        'blog_charset' => 'UTF-8'
    ];
    return $o[$key];
}

/**
 * stub __
 *
 * @param $val
 * @param $domain
 * @return mixed
 */
function __($val, $domain)
{
    return $val;
}
