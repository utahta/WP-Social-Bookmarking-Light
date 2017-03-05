<?php

namespace WpSocialBookmarkingLight;

/**
 * Class Plugin
 * WordPress Plugin
 * @package WpSocialBookmarkingLight
 */
class Plugin
{
    /** @var Option */
    private $option;

    /** @var Builder */
    private $builder;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $renderer = new Renderer();
        $this->option = new Option();
        $this->builder = new Builder($renderer, $this->option);
    }

    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Initialize wp actions
     */
    public function init()
    {
        add_action('init', function() {
            add_action('wp_head', array($this, 'head'));
            add_action('wp_footer', array($this, 'footer'));
            add_filter('the_content', array($this, 'theContent'));
            add_action('admin_menu', 'wp_social_bookmarking_light_admin_menu');
        });
    }

    /**
     * called by wp_head action
     */
    public function head()
    {
        echo $this->builder->head();
    }

    /**
     * called by wp_footer action
     */
    public function footer()
    {
        echo $this->builder->footer();
    }

    /**
     * Embed the share buttons to the content.
     * called by the_content filter
     *
     * @param string $content
     * @return string
     */
    public function theContent($content)
    {
        if (!$this->isEnabled()) {
            return $content;
        }

        $options = $this->option->getAll();
        $out = $this->builder->content($options['services'], get_permalink(), get_the_title());
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
     * true if can be displayed, false if not
     *
     * @return bool
     */
    private function isEnabled()
    {
        if (is_feed() || is_404() || is_robots() || (function_exists('is_ktai') && is_ktai())) {
            return false;
        }

        $options = $this->option->getAll();
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
        return true;
    }
}
