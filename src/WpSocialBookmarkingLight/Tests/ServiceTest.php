<?php

namespace WpSocialBookmarkingLight\Tests;

use PHPUnit\Framework\TestCase;
use WpSocialBookmarkingLight\Service;

require_once __DIR__.'/Helper/setting.php';

/**
 * Class ServiceTest
 * @package WpSocialBookmarkingLight\Tests
 */
class ServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider testCaseServiceType
     *
     * @param $service
     * @param $expectedFlag
     */
    public function testGetServiceTypes($service, $expectedFlag)
    {
        $serviceTypes = Service::getServiceTypes();
        $this->assertEquals($expectedFlag, in_array($service, $serviceTypes));
    }

    /**
     * @return array
     */
    public function testCaseServiceType()
    {
        return [
            ['hatena', true],
            ['hatena_users', true],
            ['hatena_button', true],
            ['twitter', true],
            ['tumblr', true],
            ['google', true],
            ['google_plus_one', true],
            ['delicious', true],
            ['digg', true],
            ['facebook', true],
            ['facebook_like', true],
            ['facebook_share', true],
            ['facebook_send', true],
            ['reddit', true],
            ['linkedin', true],
            ['instapaper', true],
            ['stumbleupon', true],
            ['mixi', true],
            ['mixi_like', true],
            ['gree', true],
            ['atode', true],
            ['line', true],
            ['pocket', true],
            ['pinterest', true],
            ['dummy', false],
        ];
    }
}
