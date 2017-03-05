<?php

namespace WpSocialBookmarkingLight;

/**
 * Class Builder
 *
 * build up html content
 *
 * @package WpSocialBookmarkingLight
 */
class Builder
{
    /** @var Option */
    private $option;

    /**
     * @param Option $option
     */
    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    /**
     * wp_head HTML
     *
     * @return string
     */
    public function head()
    {
        // Load options
        $options = $this->option->getAll();
        $services = explode(",", $options['services']);
        $out = "";

        // mixi-check-robots
        if (in_array('mixi', $services)) {
            $out .= '<meta name="mixi-check-robots" content="' . $options['mixi']['check_robots'] . '"/>' . "\n";
        }

        // tumblr
        if (in_array('tumblr', $services)) {
            $out .= '<script type="text/javascript" src="//platform.tumblr.com/v1/share.js"></script>';
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
                $out .= "\n";
            }
        }

        // css
        $out .= <<<HTML
<style type="text/css">
    ${options['styles']}
</style>
HTML;
        $out .= "\n";

        return "<!-- BEGIN: WP Social Bookmarking Light HEAD -->\n"
            . $out
            . "<!-- END: WP Social Bookmarking Light HEAD -->\n";
    }

    /**
     * wp_footer HTML
     *
     * @return string
     */
    public function footer()
    {
        // Load options
        $options = $this->option->getAll();
        $services = explode(",", $options['services']);
        $out = "";

        // Twitter
        if (in_array('twitter', $services)) {
            $out .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>\n";
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

        return "<!-- BEGIN: WP Social Bookmarking Light FOOTER -->\n"
            . $out
            . "<!-- END: WP Social Bookmarking Light FOOTER -->\n";
    }

    /**
     * the_content HTML
     *
     * @param $services string separated like `aaa,bbb,ccc`
     * @param $link string
     * @param $title string
     * @return string
     */
    public function content($services, $link, $title)
    {
        $wp = new Service($this->option, $link, $title);
        $service_types = Service::getServiceTypes();
        $out = '';
        foreach (explode(",", $services) as $service) {
            $service = trim($service);
            if ($service === '') {
                continue;
            }

            if (in_array($service, $service_types)) {
                $out .= '<div class="wsbl_' . $service . '">'
                    . $wp->invokeService($service) // invoke Service method
                    . '</div>';
            } else {
                $out .= "<div>[`$service` not found]</div>";
            }
        }

        if ($out == '') {
            return $out;
        }
        return "<div class='wp_social_bookmarking_light'>{$out}</div><br class='wp_social_bookmarking_light_clear' />";
    }
}
