<?php

namespace WpSocialBookmarkingLight;

/**
 * Class Option
 * @package WpSocialBookmarkingLight
 */
class Option implements OptionInterface
{
    /** @var array */
    private $cache;

    /**
     * Option constructor.
     */
    public function __construct()
    {
        $this->cache = null;
    }

    /**
     * Returns all option
     *
     * @return array
     */
    public function getAll()
    {
        if (is_array($this->cache)) {
            return $this->cache;
        }
        $options = get_option("wp_social_bookmarking_light_options", array());

        // array merge recursive overwrite (1 depth)
        $defaultOption = $this->defaultOption();
        foreach ($defaultOption as $key => $val) {
            if (!is_array($defaultOption[$key])) {
                continue;
            }

            if (!array_key_exists($key, $options) || !is_array($options[$key])) {
                $options[$key] = array();
            }
            $options[$key] = array_merge($defaultOption[$key], $options[$key]);
        }
        $this->cache = array_merge($defaultOption, $options);

        return $this->cache;
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        $options = array(
            "services" => $data["services"],
            "styles" => $data["styles"],
            "position" => $data["position"],
            "single_page" => $data["single_page"] == 'true',
            "is_page" => $data["is_page"] == 'true',
            "mixi" => array(
                'check_key' => $data["mixi_check_key"],
                'check_robots' => $data["mixi_check_robots"],
                'button' => $data['mixi_button']
            ),
            'mixi_like' => array(
                'width' => $data["mixi_like_width"]
            ),
            "twitter" => array(
                'via' => $data['twitter_via'],
                'lang' => $data['twitter_lang'],
                'size' => $data['twitter_size'],
                'related' => $data['twitter_related'],
                'hashtags' => $data['twitter_hashtags'],
                'dnt' => $data['twitter_dnt'] == 'true',
                'version' => $data['twitter_version'],
            ),
            'hatena_button' => array(
                'layout' => $data['hatena_button_layout']
            ),
            'facebook' => array(
                'locale' => trim($data['facebook_locale']),
                'version' => $data['facebook_version'],
                'fb_root' => $data['facebook_fb_root'] == 'true'
            ),
            'facebook_like' => array(
                'layout' => $data['facebook_like_layout'],
                'action' => $data['facebook_like_action'],
                'share' => $data['facebook_like_share'] == 'true',
                'width' => $data['facebook_like_width']
            ),
            'facebook_share' => array(
                'type' => $data['facebook_share_type'],
                'width' => $data['facebook_share_width']
            ),
            'facebook_send' => array(
                'colorscheme' => $data['facebook_send_colorscheme'],
                'width' => $data['facebook_send_width'],
                'height' => $data['facebook_send_height']
            ),
            'gree' => array(
                'button_type' => $data['gree_button_type'],
                'button_size' => $data['gree_button_size']
            ),
            'tumblr' => array('button_type' => $data['tumblr_button_type']),
            'atode' => array('button_type' => $data['atode_button_type']),
            'google_plus_one' => array(
                'button_size' => $data['google_plus_one_button_size'],
                'lang' => $data['google_plus_one_lang'],
                'annotation' => $data['google_plus_one_annotation'],
                'inline_size' => $data['google_plus_one_inline_size']
            ),
            'line' => array(
                'button_type' => $data['line_button_type'],
                'protocol' => $data['line_protocol']
            ),
            'pocket' => array('button_type' => $data['pocket_button_type']),
            'pinterest' => array(
                'type' => $data['pinterest_type'],
                'shape' => $data['pinterest_shape'],
                'size' => $data['pinterest_size'],
                'lang' => $data['pinterest_lang'],
            ),
        );
        update_option('wp_social_bookmarking_light_options', $options);
        return $options;
    }

    /**
     * @return array
     */
    public function restoreDefaultOption()
    {
        $options = $this->defaultOption();
        update_option('wp_social_bookmarking_light_options', $options);
        return $options;
    }

    /**
     * Returns default option
     *
     * @return array
     */
    private function defaultOption()
    {
        $styles = <<<CSS
.wp_social_bookmarking_light{
    border: 0 !important;
    padding: 10px 0 20px 0 !important;
    margin: 0 !important;
}
.wp_social_bookmarking_light div{
    float: left !important;
    border: 0 !important;
    padding: 0 !important;
    margin: 0 5px 0px 0 !important;
    min-height: 30px !important;
    line-height: 18px !important;
    text-indent: 0 !important;
}
.wp_social_bookmarking_light img{
    border: 0 !important;
    padding: 0;
    margin: 0;
    vertical-align: top !important;
}
.wp_social_bookmarking_light_clear{
    clear: both !important;
}
#fb-root{
    display: none;
}
.wsbl_facebook_like iframe{
    max-width: none !important;
}
.wsbl_pinterest a{
    border: 0px !important;
}
CSS;
        return array(
            "services" => "hatena_button,facebook_like,twitter,pocket",
            "styles" => $styles,
            "position" => "top",
            "single_page" => true,
            "is_page" => true,
            "mixi" => array(
                'check_key' => '',
                'check_robots' => 'noimage',
                'button' => 'button-3'
            ),
            'mixi_like' => array('width' => '65'),
            "twitter" => array(
                'via' => "",
                'lang' => "",
                'size' => "",
                'related' => "",
                'hashtags' => "",
                "dnt" => false,
                'version' => "html"
            ),
            "hatena_button" => array('layout' => 'simple-balloon'),
            'facebook' => array(
                'locale' => 'en_US',
                'version' => 'xfbml',
                'fb_root' => true
            ),
            'facebook_like' => array(
                'layout' => 'button_count',
                'action' => 'like',
                'share' => false,
                'width' => '100'
            ),
            'facebook_share' => array(
                'type' => 'button_count',
                'width' => ''
            ),
            'facebook_send' => array(
                'colorscheme' => 'light',
                'width' => '',
                'height' => ''
            ),
            'gree' => array(
                'button_type' => '4',
                'button_size' => '16'
            ),
            'tumblr' => array('button_type' => '1'),
            'atode' => array('button_type' => 'iconsja'),
            'google_plus_one' => array(
                'button_size' => 'medium',
                'lang' => 'en-US',
                'annotation' => 'none',
                'inline_size' => '250'
            ),
            'line' => array(
                'button_type' => 'line88x20',
                'protocol' => 'http'
            ),
            'pocket' => array('button_type' => 'none'),
            'pinterest' => array(
                'type' => 'any',
                'shape' => 'rect',
                'size' => 'small',
                'lang' => 'en',
            ),
        );
    }
}
