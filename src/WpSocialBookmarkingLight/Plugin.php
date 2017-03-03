<?php

namespace WpSocialBookmarkingLight;

/**
 * Class Plugin
 * WordPress Plugin
 * @package WpSocialBookmarkingLight
 */
class Plugin
{
    /** @var  Content */
    private $content;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $option = new Option();
        $this->content = new Content($option);
    }

    /**
     * Initialize wp actions
     */
    public function init()
    {
        add_action('init', function() {
            add_action('wp_head', array($this, 'head'));
            add_action('wp_footer', array($this, 'footer'));
            add_filter('the_content', 'wp_social_bookmarking_light_the_content');
            add_action('admin_menu', 'wp_social_bookmarking_light_admin_menu');
        });
    }

    /**
     * called by wp_head
     */
    public function head()
    {
        echo $this->content->head();
    }

    /**
     * called by wp_footer
     */
    public function footer()
    {
        echo $this->content->footer();
    }
}