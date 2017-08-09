<?php

namespace WpSocialBookmarkingLight\Tests;

use PHPUnit\Framework\TestCase;
use WpSocialBookmarkingLight\Builder;
use WpSocialBookmarkingLight\OptionInterface;
use WpSocialBookmarkingLight\Renderer;

require_once __DIR__.'/Helper/setting.php';
require_once __DIR__.'/Helper/Builder/global.php';

/**
 * Class BuilderTest
 * @package WpSocialBookmarkingLight\Tests
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider headContainsTestCase
     *
     * @param array $option
     * @param $expectHtml
     */
    public function headContains(array $option, $expectHtml)
    {
        $stub = $this->createMock(OptionInterface::class);
        $stub->method('getAll')->willReturn($option);

        $builder = new Builder(new Renderer(), $stub);
        $this->assertContains($expectHtml, $builder->head());
    }

    /**
     * @return array
     */
    public function headContainsTestCase()
    {
        return [
            [
                ['services' => 'mixi', 'mixi' => ['check_robots' => 'test'], 'styles' => ''],
                '<meta name="mixi-check-robots" content="test"/>'
            ],
            [
                ['services' => 'tumblr', 'styles' => ''],
                '<script type="text/javascript" src="//platform.tumblr.com/v1/share.js"></script>'
            ],
            [
                ['services' => 'facebook_like', 'facebook' => ['version' => 'html5', 'locale' => 'en'], 'styles' => ''],
                '//connect.facebook.net/en/sdk.js#xfbml=1&version=v2.7'
            ],
            [
                ['services' => 'facebook_share', 'facebook' => ['version' => 'html5', 'locale' => 'ja'], 'styles' => ''],
                '//connect.facebook.net/ja/sdk.js#xfbml=1&version=v2.7'
            ],
            [
                ['services' => 'facebook_send', 'facebook' => ['version' => 'html5', 'locale' => 'de'], 'styles' => ''],
                '//connect.facebook.net/de/sdk.js#xfbml=1&version=v2.7'
            ],
            [
                ['services' => '', 'styles' => 'dummy css'],
                'dummy css'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider footerContainsTestCase
     *
     * @param array $option
     * @param $expectHtml
     */
    public function footerContains(array $option, $expectHtml)
    {
        $stub = $this->createMock(OptionInterface::class);
        $stub->method('getAll')->willReturn($option);

        $builder = new Builder(new Renderer(), $stub);
        $this->assertContains($expectHtml, $builder->footer());
    }

    /**
     * @return array
     */
    public function footerContainsTestCase()
    {
        return [
            [
                ['services' => 'twitter'],
                '//platform.twitter.com/widgets.js'
            ],
            [
                ['services' => 'google_plus_one', 'google_plus_one' => ['lang' => 'ja']],
                'https://apis.google.com/js/platform.js'
            ],
            [
                ['services' => 'pinterest', 'pinterest' => ['type' => 'all']],
                '//assets.pinterest.com/js/pinit.js'
            ]
        ];
    }

    /**
     * @test
     */
    public function contentContains()
    {
        $stub = $this->createMock(OptionInterface::class);
        $stub->method('getAll')->willReturn([
            "hatena_button" => ['layout' => 'simple-balloon'],
            "twitter" => [
                'via' => "a",
                'lang' => "ja",
                'size' => "large",
                'related' => "b",
                'hashtags' => "c",
                "dnt" => true,
                "version" => "iframe"
            ],
            'facebook' => [
                'locale' => 'en_US',
                'version' => 'xfbml',
                'fb_root' => true
            ],
            'facebook_like' => [
                'layout' => 'button_count',
                'action' => 'like',
                'share' => false,
                'width' => '100'
            ],
            'pocket' => ['button_type' => 'none']
        ]);
        $builder = new Builder(new Renderer(), $stub);
        $services = 'hatena_button,twitter,facebook_like,pocket';

        $this->assertContains('wsbl_hatena_button', $builder->content($services, '', ''));
        $this->assertContains('wsbl_twitter', $builder->content($services, '', ''));
        $this->assertContains('wsbl_facebook_like', $builder->content($services, '', ''));
        $this->assertContains('wsbl_pocket', $builder->content($services, '', ''));
        $this->assertContains('[`dummy` not found]', $builder->content('dummy', '', ''));
    }
}
