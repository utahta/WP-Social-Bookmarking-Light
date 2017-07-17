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

    /** @var Admin */
    private $admin;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $renderer = new Renderer();
        $this->option = new Option();
        $this->builder = new Builder($renderer, $this->option);
        $this->admin = new Admin($this->option);
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
    public static function init()
    {
        add_action('init', function() {
            $plugin = new Plugin();
            add_action('wp_head', array($plugin, 'head'));
            add_action('wp_footer', array($plugin, 'footer'));
            add_filter('the_content', array($plugin, 'theContent'));
            add_action('admin_menu', array($plugin, 'adminMenu'));
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
     * called by admin_menu action
     */
    public function adminMenu()
    {
        if( function_exists('add_options_page') ){
            $admin = $this->admin;
            $page = add_options_page('WP Social Bookmarking Light',
                'WP Social Bookmarking Light',
                'manage_options',
                __FILE__,
                function () use($admin) {
                    echo $admin->page();
                });
            add_action('admin_print_styles-' . $page, array($this->admin, 'enqueueStyles'));
            add_action('admin_print_scripts-' . $page, array($this->admin, 'enqueueScripts'));
            add_action('admin_head-' . $page, function () use($admin) {
                echo $admin->head();
            });
        }
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

        if (get_query_var('amp', false) !== false) {
            return false;
        }

        return true;
    }
}
