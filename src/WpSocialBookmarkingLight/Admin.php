<?php

namespace WpSocialBookmarkingLight;

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
