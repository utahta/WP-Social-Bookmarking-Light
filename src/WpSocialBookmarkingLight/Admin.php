<?php

namespace WpSocialBookmarkingLight;

use WpSocialBookmarkingLight\Util\Url;

/**
 * Class Admin
 * @package WpSocialBookmarkingLight
 */
class Admin
{
    /** @var Renderer */
    private $renderer;

    /** @var OptionInterface */
    private $option;

    /**
     * Admin constructor.
     * @param OptionInterface $option
     */
    public function __construct(OptionInterface $option)
    {
        $this->renderer = new Renderer();
        $this->option = $option;
    }

    /**
     * enqueue admin styles
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('wsbl-admin', Url::css("admin/style.css"));
        wp_enqueue_style('wsbl-admin-jquery-ui-tabs', Url::css("admin/pepper-grinder/jquery-ui-1.8.6.custom.css"));
    }

    /**
     * enqueue admin scripts
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
    }

    /**
     * admin header
     *
     * @return string
     */
    public function head()
    {
        return $this->renderer->render("@admin/head.html.twig", array());
    }

    /**
     * admin page content
     *
     * @return string
     */
    public function page()
    {
        $context = array(
            'saved' => isset($_POST['save']),
            'restored' => isset($_POST['restore']),
            'request_uri' => $_SERVER['REQUEST_URI'],
            'service_types' => Service::getServiceTypes(),
        );

        if ($context['saved']) {
            $context['option'] = $this->option->save($_POST);
        } elseif ($context['restored']) {
            $context['option'] = $this->option->restoreDefaultOption();
        } else {
            $context['option'] =  $this->option->getAll();
        }

        return $this->renderer->render("@admin/page.html.twig", $context);
    }
}
