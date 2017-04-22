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
    /** @var Renderer */
    private $renderer;

    /** @var Option */
    private $option;

    /**
     * Builder constructor.
     * @param Renderer $renderer
     * @param OptionInterface $option
     */
    public function __construct(Renderer $renderer, OptionInterface $option)
    {
        $this->renderer = $renderer;
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

        $context = array();
        $context['mixi'] = in_array('mixi', $services) ? $options['mixi'] : null;
        $context['tumblr'] = in_array('tumblr', $services);

        $context['facebook'] = null;
        if (in_array('facebook_like', $services) ||
            in_array('facebook_share', $services) ||
            in_array('facebook_send', $services)
        ) {
            $version = $options['facebook']['version'];
            if ($version == "html5" || $version == "xfbml") {
                $context['facebook'] = $options['facebook'];
            }
        }

        $context['styles'] = $options['styles'];

        return $this->renderer->render("@builder/head.html.twig", $context);
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

        $context = array();
        $context['twitter'] = in_array('twitter', $services);
        $context['google_plus_one'] = in_array('google_plus_one', $services) ? $options['google_plus_one'] : null;
        $context['pinterest'] = in_array('pinterest', $services) ? $options['pinterest'] : null;

        return $this->renderer->render("@builder/footer.html.twig", $context);
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

        $context = array();
        foreach (explode(",", $services) as $service) {
            $service = trim($service);
            if ($service === '') {
                continue;
            }

            $context['services'][] = array(
                'name' => $service,
                'content' => in_array($service, $service_types) ? $wp->invokeService($service) : "[`$service` not found]"
            );
        }

        return $this->renderer->render("@builder/content.html.twig", $context);
    }
}
