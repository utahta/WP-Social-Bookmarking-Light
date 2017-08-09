<?php

namespace WpSocialBookmarkingLight;

use WpSocialBookmarkingLight\Util\Text;
use WpSocialBookmarkingLight\Util\Url;

/**
 * Class Service
 *
 * public method name is service type
 * e.g.
 *   facebookLike() -> facebook_like
 *
 * @package WpSocialBookmarkingLight
 */
class Service
{
    /** @var  OptionInterface */
    private $option;

    /** @var  string */
    private $url;

    /** @var  string */
    private $title;

    /** @var  string */
    private $encode_url;

    /** @var  string */
    private $encode_title;

    /** @var  string */
    private $encode_blogname;

    /**
     * Service constructor.
     * @param OptionInterface $option
     * @param $url string
     * @param $title string
     */
    public function __construct(OptionInterface $option, $url, $title)
    {
        $this->option = $option;
        $this->blogname = $this->toUTF8(get_bloginfo('name'));
        $this->url = $url;
        $this->title = $this->toUTF8($title);
        $this->encode_url = rawurlencode($url);
        $this->encode_title = rawurlencode($this->title);
        $this->encode_blogname = rawurlencode($this->blogname);
    }

    /**
     * @param $str
     * @return string
     */
    private function toUTF8($str)
    {
        $charset = get_option('blog_charset');
        if (strcasecmp($charset, 'UTF-8') != 0 && function_exists('mb_convert_encoding')) {
            $str = mb_convert_encoding($str, 'UTF-8', $charset);
        }
        return $str;
    }

    /**
     * @param $url string
     * @return string
     */
    private function linkRaw($url)
    {
        return $url;
    }

    /**
     * build <a> and <img> tag
     *
     * @param $url
     * @param $alt
     * @param $icon
     * @param $width
     * @param $height
     * @param bool $blank
     * @return string
     */
    private function link($url, $alt, $icon, $width, $height, $blank = true)
    {
        $width = $width ? "width='$width'" : "";
        $height = $height ? "height='$height'" : "";
        $blank = $blank ? "target=_blank" : "";
        return "<a href='{$url}' title='{$alt}' rel=nofollow class='wp_social_bookmarking_light_a' $blank>"
            . "<img src='{$icon}' alt='{$alt}' title='{$alt}' $width $height class='wp_social_bookmarking_light_img' />"
            . "</a>";
    }

    /**
     * Invoke service method
     *
     * @param $service string
     * @return string
     */
    public function invokeService($service)
    {
        // snake_case to camelCase
        $method = str_replace('_', "\t", $service);
        $method = ucwords($method); // for php 5.3
        $method = str_replace("\t", '', $method);
        return $this->$method();
    }

    /**
     * Returns service types
     *
     * @return array
     */
    public static function getServiceTypes()
    {
        $class_methods = get_class_methods(__CLASS__);
        $excepts = array('__construct', 'toUTF8', 'linkRaw', 'link', 'invokeService', 'getServiceTypes'); // except not service type function.

        $methods = array();
        foreach ($class_methods as $method) {
            if (in_array($method, $excepts)) {
                continue;
            }
            $methods[] = strtolower(preg_replace('/[a-z]+(?=[A-Z])|[A-Z]+(?=[A-Z][a-z])/', '\0_', $method)); // camelCase to snake_case.
        }
        return $methods;
    }

    /**
     * Hatena bookmark button (old type)
     *
     * @return string
     */
    public function hatena()
    {
        $url = "//b.hatena.ne.jp/add?mode=confirm&url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Bookmark this on Hatena Bookmark");
        $icon = Url::images("hatena.gif");
        return $this->link($url, $alt, $icon, 16, 12);
    }

    /**
     * Hatena bookmark users (old type)
     *
     * @return string
     */
    public function hatenaUsers()
    {
        $url = "//b.hatena.ne.jp/entry/{$this->url}";
        $alt = sprintf(Text::locale("Hatena Bookmark - %s"), $this->title);
        $icon = "//b.hatena.ne.jp/entry/image/{$this->url}";
        return $this->link($url, $alt, $icon, null, null);
    }

    /**
     * Hatena bookmark button
     *
     * @return string
     */
    public function hatenaButton()
    {
        $options = $this->option->getAll();
        $url = "//b.hatena.ne.jp/entry/{$this->url}";
        $title = $this->title;
        $alt = Text::locale("Bookmark this on Hatena Bookmark");
        return $this->linkRaw('<a href="' . $url . '"'
            . ' class="hatena-bookmark-button"'
            . ' data-hatena-bookmark-title="' . $title . '"'
            . ' data-hatena-bookmark-layout="' . $options['hatena_button']['layout'] . '"'
            . ' title="' . $alt . '">'
            . ' <img src="//b.hatena.ne.jp/images/entry-button/button-only@2x.png"'
            . ' alt="' . $alt . '" width="20" height="20" style="border: none;" /></a>'
            . '<script type="text/javascript" src="//b.hatena.ne.jp/js/bookmark_button.js" charset="utf-8" async="async"></script>');
    }

    /**
     * Twitter button
     *
     * @return string
     */
    public function twitter()
    {
        $options = $this->option->getAll();;
        $twitter = $options['twitter'];
        $data_url = $this->url;
        $data_text = $this->title;

        if ($twitter['version'] === 'iframe') {
            // if you want to change width length, please edit wsbl_twitter style at Settings
            return $this->linkRaw(
                '<iframe '
                . 'src="https://platform.twitter.com/widgets/tweet_button.html?'
                . 'url='.$data_url
                . '&text='.$data_text
                . ($twitter['via'] !== '' ? '&via='.$twitter['via'] : '')
                . ($twitter['size'] === 'large' ? '&size=l' : '')
                . ($twitter['related'] !== '' ? '&related='.$twitter['related'] : '')
                . ($twitter['hashtags'] !== '' ? '&hashtags='.$twitter['hashtags'] : '')
                . ($twitter['dnt'] ? '&dnt=true' : '')
                . ($twitter['lang'] !== '' ? '&lang='.$twitter['lang'] : '')
                . '"'
                . ' width="140"'
                . ' height="28"'
                . ' title="Tweet"'
                . ' style="border: 0; overflow: hidden;"'
                . '></iframe>'
            );
        } else {
            return $this->linkRaw(
                '<a href="https://twitter.com/share" class="twitter-share-button"'
                . ' data-url="' . $data_url . '"'
                . ' data-text="' . $data_text . '"'
                . ($twitter['via'] !== '' ? ' data-via="' . $twitter['via'] . '"' : '')
                . ($twitter['size'] === 'large' ? ' data-size="large"' : '')
                . ($twitter['related'] !== '' ? ' data-related="' . $twitter['related'] . '"' : '')
                . ($twitter['hashtags'] !== '' ? ' data-hashtags="' . $twitter['hashtags'] . '"' : '')
                . ($twitter['dnt'] ? ' data-dnt="true"' : '')
                . ($twitter['lang'] !== '' ? ' data-lang="' . $twitter['lang'] . '"' : '')
                . '>Tweet</a>'
            );
        }
    }

    /**
     * Tumblr
     *
     * @deprecated
     */
    public function tumblr()
    {
        $options = $this->option->getAll();
        $type = $options['tumblr']['button_type'];
        $width = 'width:81px;';
        switch ($type) {
            case '1':
                $width = 'width:81px;';
                break;
            case '2':
                $width = 'width:61px;';
                break;
            case '3':
                $width = 'width:129px;';
                break;
            case '4':
                $width = 'width:20px;';
                break;
        }
        return $this->linkRaw('<a href="//www.tumblr.com/share?v=3&u=' . $this->encode_url . '&t=' . $this->encode_title . '" '
            . 'title="' . Text::locale("Share on Tumblr") . '" '
            . 'style="display:inline-block; text-indent:-9999px; overflow:hidden; '
            . $width . ' height:20px; '
            . 'background:url(\'//platform.tumblr.com/v1/share_' . $type . '.png\')'
            . ' top left no-repeat transparent;">'
            . Text::locale("Share on Tumblr")
            . '</a>');
    }

    /**
     * Google Bookmarks
     *
     * @return string
     */
    public function google()
    {
        $url = "http://www.google.com/bookmarks/mark?op=add&bkmk={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Bookmark this on Google Bookmarks");
        $icon = Url::images("google.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * Google +1
     *
     * @return string
     */
    public function googlePlusOne()
    {
        $options = $this->option->getAll();
        $button_size = $options['google_plus_one']['button_size'];
        $annotation = $options['google_plus_one']['annotation'];
        $width = $annotation == 'inline' ? 'width="' . $options['google_plus_one']['inline_size'] . '"' : "";
        $raw = '<g:plusone size="' . $button_size . '" annotation="' . $annotation . '" href="' . $this->url . '" ' . $width . '></g:plusone>';
        return $this->linkRaw($raw);
    }

    /**
     * Delicious
     *
     * @return string
     */
    public function delicious()
    {
        $url = "//del.icio.us/save/get_bookmarklet_save??url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Bookmark this on Delicious");
        $icon = Url::images("delicious.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * Digg
     *
     * @return string
     */
    public function digg()
    {
        $url = "//digg.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Bookmark this on Digg");
        $icon = Url::images("digg.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * Facebook
     *
     * @return string
     */
    public function facebook()
    {
        $url = "http://www.facebook.com/share.php?u={$this->encode_url}&t={$this->encode_title}";
        $alt = Text::locale("Share on Facebook");
        $icon = Url::images("facebook.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * Facebook Like Button
     *
     * @return string
     */
    public function facebookLike()
    {
        $options = $this->option->getAll();
        $layout = $options['facebook_like']['layout'];
        $action = $options['facebook_like']['action'];
        $share = $options['facebook_like']['share'] ? 'true' : 'false';
        $width = $options['facebook_like']['width'];
        $locale = $options['facebook']['locale'];
        $version = $options['facebook']['version'];
        $fb_root = $options['facebook']['fb_root'] ? '<div id="fb-root"></div>' : '';

        if ($version == "html5") {
            return $this->linkRaw($fb_root
                . '<div class="fb-like" '
                . 'data-href="' . $this->url . '" '
                . 'data-layout="' . $layout . '" '
                . 'data-action="' . $action . '" '
                . ($width != "" ? 'data-width="' . $width . '" ' : '')
                . 'data-share="' . $share . '" '
                . 'data-show_faces="false" >'
                . '</div>');
        } elseif ($version == "xfbml") {
            return $this->linkRaw($fb_root
                . '<fb:like '
                . 'href="' . $this->url . '" '
                . 'layout="' . $layout . '" '
                . 'action="' . $action . '" '
                . ($width != "" ? 'width="' . $width . '" ' : '')
                . 'share="' . $share . '" '
                . 'show_faces="false" >'
                . '</fb:like>');
        } else {
            return $this->linkRaw('<iframe src="//www.facebook.com/plugins/like.php?href=' . $this->encode_url
                . '&amp;layout=' . $layout
                . '&amp;show_faces=false'
                . '&amp;width=' . $width
                . '&amp;action=' . $action
                . '&amp;share=' . $share
                . ($locale == '' ? '' : '&amp;locale=' . $locale)
                . '&amp;height=35"'
                . ' scrolling="no" frameborder="0"'
                . ' style="border:none; overflow:hidden; width:' . $width . 'px; height:35px;"'
                . ' allowTransparency="true"></iframe>');
        }

    }

    /**
     * Facebook Share
     *
     * @return string
     */
    public function facebookShare()
    {
        $options = $this->option->getAll();
        $url = $this->url;
        $version = $options['facebook']['version'];
        $fb_root = $options['facebook']['fb_root'] ? '<div id="fb-root"></div>' : '';
        $width = $options['facebook_share']['width'];
        $type = $options['facebook_share']['type'];

        if ($version == "html5") {
            return $this->linkRaw($fb_root
                . '<div class="fb-share-button" '
                . 'data-href="' . $url . '" '
                . ($width != "" ? 'data-width="' . $width . '" ' : '')
                . 'data-type="' . $type . '">'
                . '</div>');
        } else {
            return $this->linkRaw($fb_root
                . '<fb:share-button '
                . 'href="' . $url . '" '
                . ($width != "" ? 'width="' . $width . '" ' : '')
                . 'type="' . $type . '" >'
                . '</fb:share-button>');
        }
    }

    /**
     * Facebook Send
     *
     * @return string
     */
    public function facebookSend()
    {
        $options = $this->option->getAll();
        $url = $this->url;
        $version = $options['facebook']['version'];
        $fb_root = $options['facebook']['fb_root'] ? '<div id="fb-root"></div>' : '';
        $colorscheme = $options['facebook_send']['colorscheme'];
        $width = $options['facebook_send']['width'];
        $height = $options['facebook_send']['height'];

        if ($version == "html5") {
            return $this->linkRaw($fb_root
                . '<div class="fb-send" '
                . 'data-href="' . $url . '" '
                . ($width != "" ? 'data-width="' . $width . '" ' : '')
                . ($height != "" ? 'data-height="' . $height . '" ' : '')
                . 'data-colorscheme="' . $colorscheme . '">'
                . '</div>');
        } else {
            return $this->linkRaw($fb_root
                . '<fb:send '
                . 'href="' . $url . '" '
                . ($width != "" ? 'width="' . $width . '" ' : '')
                . ($height != "" ? 'height="' . $height . '" ' : '')
                . 'colorscheme="' . $colorscheme . '" >'
                . '</fb:send>');
        }
    }

    /**
     * reddit
     *
     * @return string
     */
    public function reddit()
    {
        $url = "//www.reddit.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Share on reddit");
        $icon = Url::images("reddit.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * LinkedIn
     *
     * @return string
     */
    public function linkedin()
    {
        $url = "//www.linkedin.com/shareArticle?mini=true&url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Share on LinkedIn");
        $icon = Url::images("linkedin.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * Instapaper
     *
     * @return string
     */
    public function instapaper()
    {
        return $this->linkRaw('<iframe border="0" scrolling="no" width="78" height="17" allowtransparency="true" frameborder="0" '
            . 'style="margin-bottom: -3px; z-index: 1338; border: 0px; background-color: transparent; overflow: hidden;" '
            . 'src="//www.instapaper.com/e2?url=' . $this->encode_url . '&title=' . $this->encode_title . '&description="'
            . '></iframe>');
    }

    /**
     * StumbleUpon
     *
     * @return string
     */
    public function stumbleupon()
    {
        $url = "//www.stumbleupon.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = Text::locale("Share on StumbleUpon");
        $icon = Url::images("stumbleupon.png");
        return $this->link($url, $alt, $icon, 16, 16);
    }

    /**
     * mixi Check
     *
     * @return string
     */
    public function mixi()
    {
        $options = $this->option->getAll();
        $data_button = $options['mixi']['button'];
        $data_key = $options['mixi']['check_key'];

        return $this->linkRaw('<a href="http://mixi.jp/share.pl" class="mixi-check-button"'
            . " data-url='{$this->url}'"
            . " data-button='{$data_button}'"
            . " data-key='{$data_key}'>Check</a>"
            . '<script type="text/javascript" src="//static.mixi.jp/js/share.js"></script>');
    }

    /**
     * mixi Like
     *
     * @return string
     */
    public function mixiLike()
    {
        $options = $this->option->getAll();
        $data_key = $options['mixi']['check_key'];
        $width = $options['mixi_like']['width'];

        return $this->linkRaw('<iframe src="http://plugins.mixi.jp/favorite.pl?href=' . $this->encode_url . '&service_key=' . $data_key . '&show_faces=false" '
            . 'scrolling="no" '
            . 'frameborder="0" '
            . 'allowTransparency="true" '
            . 'style="border:0; overflow:hidden; width:' . $width . 'px; height:20px;"></iframe>');
    }

    /**
     * GREE Social Feedback
     *
     * @return string
     */
    public function gree()
    {
        $options = $this->option->getAll();
        $url = $this->encode_url;
        $type = $options['gree']['button_type'];
        $size = $options['gree']['button_size'];
        switch ($type) {
            case '0':
                $btn_type = 'btn_iine';
                break;
            case '1':
                $btn_type = 'btn_kininaru';
                break;
            case '2':
                $btn_type = 'btn_osusume';
                break;
            case '3':
                $btn_type = 'btn_share';
                break;
            case '4':
                $btn_type = 'btn_logo';
                break;
            default:
                $btn_type = 'btn_logo';
        }
        $alt = Text::locale("Share on GREE");
        return $this->linkRaw('<a href="http://gree.jp/?mode=share&act=write'
            . '&url=' . $url
            . '&button_type=' . $type
            . '&button_size=' . $size
            . '&guid=ON" '
            . 'title="' . $alt . '" target=_blank>'
            . '<img alt="' . $alt . '" title="' . $alt . '" '
            . 'src="http://i.share.gree.jp/img/share/button/' . $btn_type . '_' . $size . '.png">'
            . '</a>');
    }

    /**
     * atode
     *
     * @return string
     */
    public function atode()
    {
        $options = $this->option->getAll();
        $type = $options['atode']['button_type'];
        switch ($type) {
            case 'iconsja':
                return $this->linkRaw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconsja.gif" alt="email this" border="0" align="absmiddle" width="16" height="16"></a>');
            case 'iconnja':
                return $this->linkRaw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconnja.gif" alt="email this" border="0" align="absmiddle" width="66" height="20"></a>');
            case 'iconnen':
                return $this->linkRaw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconnen.gif" alt="email this" border="0" align="absmiddle" width="66" height="20"></a>');
        }
        return '';
    }

    /**
     * LINE
     *
     * @return string
     */
    public function line()
    {
        $options = $this->option->getAll();
        if ($options['line']['button_type'] == "line88x20") {
            $icon = Url::images("line88x20.png");
            $width = 88;
            $height = 20;
        } else {
            $icon = Url::images("line20x20.png");
            $width = 20;
            $height = 20;
        }

        if ($options['line']['protocol'] === 'line') {
            $url = "line://msg/text/{$this->encode_title}%0D%0A{$this->encode_url}";
        } else {
            $url = "http://line.me/R/msg/text/?{$this->encode_title}%0D%0A{$this->encode_url}";
        }
        return $this->link($url, "LINEで送る", $icon, $width, $height, false);
    }

    /**
     * Pocket
     *
     * @return string
     */
    public function pocket()
    {
        $options = $this->option->getAll();
        return $this->linkRaw('<a href="https://getpocket.com/save" class="pocket-btn" data-lang="en" data-save-url="' . $this->url . '" data-pocket-count="' . $options['pocket']['button_type'] . '" data-pocket-align="left" >Pocket</a><script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script>');
    }


    /**
     * pinterest
     *
     * @return string
     */
    public function pinterest()
    {
        $options = $this->option->getAll();
        $pinterest = $options['pinterest'];
        if ($pinterest['type'] === 'hover') {
            return '';
        }
        $data_pin_shape = '';
        $data_pin_color = '';
        $data_pin_lang = '';

        if ($pinterest['shape'] === 'round') {
            // 円形ボタン
            $data_pin_shape = 'data-pin-shape="round"';

            if ($pinterest['size'] === 'large') {
                $data_pin_height = 'data-pin-height="32"';
                $img_src = "//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_32.png";
            } else {
                $data_pin_height = '';
                $img_src = '//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_16.png';
            }
        } else {
            // 長方形ボタン
            $color = $pinterest['color'];
            $lang = $pinterest['lang'];
            $data_pin_color = 'data-pin-color="' . $color . '"';
            $data_pin_lang = 'data-pin-lang="' . $lang . '"';

            if ($pinterest['size'] === 'large') {
                $data_pin_height = 'data-pin-height="28"';
                $img_src = "//assets.pinterest.com/images/pidgets/pinit_fg_${lang}_rect_${color}_28.png";
            } else {
                $data_pin_height = '';
                $img_src = "//assets.pinterest.com/images/pidgets/pinit_fg_${lang}_round_${$color}_16.png";
            }
        }

        return $this->linkRaw(
            '<a href="//jp.pinterest.com/pin/create/button/"'
            . ' data-pin-do="buttonBookmark"'
            . ' ' . $data_pin_color
            . ' ' . $data_pin_lang
            . ' ' . $data_pin_shape
            . ' ' . $data_pin_height
            . '>'
            . '<img src="' . $img_src . '" /></a>'
        );
    }
}
