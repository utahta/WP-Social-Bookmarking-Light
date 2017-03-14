<?php

namespace WpSocialBookmarkingLight;

use WpSocialBookmarkingLight\Util\Text;
use WpSocialBookmarkingLight\Util\Url;

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
        $loader->addPath(WP_SOCIAL_BOOKMARKING_LIGHT_DIR . "/src/WpSocialBookmarkingLight/Resources/views/Builder", "builder");
        $loader->addPath(WP_SOCIAL_BOOKMARKING_LIGHT_DIR . "/src/WpSocialBookmarkingLight/Resources/views/Admin", "admin");
        $this->twig = new \Twig_Environment($loader, array('debug' => false));

        $this->twig->addFilter(new \Twig_SimpleFilter("__", function ($val) {
            return Text::locale($val);
        }));

        $this->twig->addFilter(new \Twig_SimpleFilter("images", function ($path) {
            return Url::images($path);
        }));
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
