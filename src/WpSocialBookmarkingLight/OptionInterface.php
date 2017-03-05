<?php

namespace WpSocialBookmarkingLight;

/**
 * Interface OptionInterface
 * @package WpSocialBookmarkingLight
 */
interface OptionInterface
{
    /**
     * @return array
     */
    public function getAll();

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data);

    /**
     * @return array
     */
    public function restoreDefaultOption();
}
