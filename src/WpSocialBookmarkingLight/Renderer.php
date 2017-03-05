<?php

namespace WpSocialBookmarkingLight;

/**
 * Class Renderer
 * @package WpSocialBookmarkingLight
 */
class Renderer
{
    /** @var \Twig_Environment */
    private $twig;

    /**
     * Renderer constructor.
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem();
        $loader->addPath(WP_SOCIAL_BOOKMARKING_LIGHT_DIR."/src/WpSocialBookmarkingLight/Resources/views/Builder", "builder");
        $this->twig = new \Twig_Environment($loader, array('debug' => true));
    }

    /**
     * Render template
     *
     * @param $name string
     * @param array $context
     * @return string
     */
    public function render($name, array $context)
    {
        $template = $this->twig->load($name);
        return $template->render($context);
    }
}
