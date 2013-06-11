<?php
/*
Copyright 2010 utahta (email : labs.ninxit@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Services
 */
class WpSocialBookmarkingLight
{
    var $url;
    var $title;
    var $encode_url;
    var $encode_title;
    var $encode_blogname;
    
    function WpSocialBookmarkingLight( $url, $title, $blogname )
    {
        $title = $this->to_utf8( $title );
        $this->blogname = $this->to_utf8( $blogname );
        $this->url = $url;
        $this->title = $title;
        $this->encode_url = rawurlencode( $url );
        $this->encode_title = rawurlencode( $title );
        $this->encode_blogname = rawurlencode( $this->blogname );
    }
    
    function to_utf8( $str )
    {
        $charset = get_option( 'blog_charset' );
        if( strcasecmp( $charset, 'UTF-8' ) != 0 && function_exists('mb_convert_encoding') ){
            $str = mb_convert_encoding( $str, 'UTF-8', $charset );
        }
        return $str;
    }
    
    function link_raw( $url ){
        return $url;
    }
    function link( $url, $alt, $icon, $width, $height ){
        $width = $width ? "width='$width'" : "";
        $height = $height ? "height='$height'" : "";
        return "<a href='{$url}' title='{$alt}' rel=nofollow class='wp_social_bookmarking_light_a' target=_blank>"
               ."<img src='{$icon}' alt='{$alt}' title='{$alt}' $width $height class='wp_social_bookmarking_light_img' />"
               ."</a>";
    }
    
    /**
     * @brief Hatena Bookmark
     */
    function hatena()
    {
        $url = "http://b.hatena.ne.jp/add?mode=confirm&url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Hatena Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/hatena.gif";
        return $this->link( $url, $alt, $icon, 16, 12 );
    }
    function hatena_users()
    {
        $url = "http://b.hatena.ne.jp/entry/{$this->url}";
        $alt = sprintf( __("Hatena Bookmark - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://b.hatena.ne.jp/entry/image/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    function hatena_button()
    {
        $options = wp_social_bookmarking_light_options();
        $url = "http://b.hatena.ne.jp/entry/{$this->url}";
        $title = $this->title;
        $alt = __( "Bookmark this on Hatena Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        return $this->link_raw('<a href="'.$url.'"'
                                .' class="hatena-bookmark-button"'
                                .' data-hatena-bookmark-title="'.$title.'"'
                                .' data-hatena-bookmark-layout="'.$options['hatena_button']['layout'].'"'
                                .' title="'.$alt.'">'
                                .' <img src="http://b.st-hatena.com/images/entry-button/button-only.gif"'
                                .' alt="'.$alt.'" width="20" height="20" style="border: none;" /></a>'
                                .'<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button_wo_al.js" charset="utf-8" async="async"></script>');
    }
    
    /**
     * @brief twib
     */
    function twib()
    {
        $url = "http://twib.jp/share?url={$this->encode_url}";
        $alt = __( "Post to Twitter", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/twib.gif";
        return $this->link( $url, $alt, $icon, 18, 18 );
    }
    function twib_users()
    {
        $url = "http://twib.jp/url/{$this->url}";
        $alt = sprintf( __("Tweets - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://image.twib.jp/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief tweetmeme
     */
    function tweetmeme()
    {
        return $this->link_raw( "<script type='text/javascript'>"
                               ."tweetmeme_style = 'compact';"
                               ."tweetmeme_url='{$this->url}';"
                               ."</script>"
                               ."<script type='text/javascript' src='http://tweetmeme.com/i/scripts/button.js'></script>" );
    }
    
    /**
     * @brief twitter
     */
    function twitter()
    {
        $options = wp_social_bookmarking_light_options();
        $twitter = $options['twitter'];
        return $this->link_raw('<iframe allowtransparency="true" frameborder="0" scrolling="no"'
                                .' src="http://platform.twitter.com/widgets/tweet_button.html'
                                .'?url='.$this->encode_url
                                .'&amp;text='.$this->encode_title
                                .($twitter['via'] != '' ? '&amp;via='.$twitter['via'] : '')
                                .'&amp;lang='.$twitter['lang']
                                .'&amp;count='.$twitter['count']
                                .'" style="width:130px; height:20px;">'
                                .'</iframe>');
    }

    /**
     * @brief Livedoor Clip
     */
    function livedoor()
    {
        $url = "http://clip.livedoor.com/redirect?link={$this->encode_url}&title={$this->encode_blogname}%20-%20{$this->encode_title}&ie=utf-8";
        $alt = __( "Bookmark this on Livedoor Clip", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/livedoor.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function livedoor_users()
    {
        $url = "http://clip.livedoor.com/page/{$this->url}";
        $alt = sprintf( __("Livedoor Clip - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://image.clip.livedoor.com/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief Yahoo!JAPAN Bookmark
     */
    function yahoo()
    {
        $url = "http://bookmarks.yahoo.co.jp/bookmarklet/showpopup?t={$this->encode_title}&u={$this->encode_url}&ei=UTF-8";
        $alt = __( "Bookmark this on Yahoo Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/yahoo.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function yahoo_users()
    {
        return $this->link_raw( "<script src='http://num.bookmarks.yahoo.co.jp/numimage.js?disptype=small'></script>" );
    }
    
    /**
     * @brief Yahoo Buzz
     */
    function yahoo_buzz()
    {
        $url = "http://buzz.yahoo.com/buzz?targetUrl={$this->encode_url}&headline={$this->encode_title}";
        $alt = __( "Buzz This", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/yahoo_buzz.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief BuzzURL
     */
    function buzzurl()
    {
        $url = "http://buzzurl.jp/entry/{$this->url}";
        $alt = __( "Bookmark this on BuzzURL", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/buzzurl.gif";
        return $this->link( $url, $alt, $icon, 21, 15 );
    }
    function buzzurl_users()
    {
        $url = "http://buzzurl.jp/entry/{$this->url}";
        $alt = sprintf( __("BuzzURL - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://api.buzzurl.jp/api/counter/v1/image?url={$this->encode_url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief nifty clip
     */
    function nifty()
    {
        $url = "http://clip.nifty.com/create?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on @nifty clip", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/nifty.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function nifty_users()
    {
        $url = '#';
        $alt = sprintf( __("@nifty clip - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://api.clip.nifty.com/api/v1/image/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief Tumblr
     */
    function tumblr()
    {
        $options = wp_social_bookmarking_light_options();
        $type = $options['tumblr']['button_type'];
        $width = 'width:81px;';
        switch($type){
            case '1' : $width = 'width:81px;'; break;
            case '2' : $width = 'width:61px;'; break;
            case '3' : $width = 'width:129px;'; break;
            case '4' : $width = 'width:20px;'; break;
        }
        return $this->link_raw('<a href="http://www.tumblr.com/share?v=3&u='.$this->encode_url.'&t='.$this->encode_title.'" '
                            .'title="'.__l("Share on Tumblr").'" '
                            .'style="display:inline-block; text-indent:-9999px; overflow:hidden; '
                            .$width.' height:20px; '
                            .'background:url(\'http://platform.tumblr.com/v1/share_'.$type.'.png\')'
                            .' top left no-repeat transparent;">'
                            .__l("Share on Tumblr")
                            .'</a>');
    }
    
    /**
     * @brief FC2 Bookmark
     */
    function fc2()
    {
        $url = "http://bookmark.fc2.com/user/post?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on FC2 Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/fc2.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function fc2_users()
    {
        $url = "http://bookmark.fc2.com/search/detail?url={$this->encode_url}";
        $alt = sprintf( __("FC2 Bookmark - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://bookmark.fc2.com/image/users/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief newsing
     */
    function newsing()
    {
        $url = "http://newsing.jp/nbutton?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Newsing it!", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/newsing.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Choix
     */
    function choix()
    {
        $url = "http://www.choix.jp/bloglink/{$this->url}";
        $alt = __( "Choix it!", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/choix.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Google Bookmarks
     */
    function google()
    {
        $url = "http://www.google.com/bookmarks/mark?op=add&bkmk={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Google Bookmarks", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/google.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Google Buzz
     */
    function google_buzz()
    {
        $url = "http://www.google.com/buzz/post?url={$this->encode_url}&message={$this->encode_title}";
        $alt = __( "Post to Google Buzz", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/google-buzz.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    /**
     * @brief Google +1
     */
    function google_plus_one()
    {
        $options = wp_social_bookmarking_light_options();
        $button_size = $options['google_plus_one']['button_size'];
        $annotation = $options['google_plus_one']['annotation'];
        $width = $annotation == 'inline' ? 'width="'.$options['google_plus_one']['inline_size'].'"' : "";
        $raw = '<g:plusone size="'.$button_size.'" annotation="'.$annotation.'" href="'.$this->url.'" '.$width.'></g:plusone>';
        return $this->link_raw($raw);
    }

    /**
     * @brief Delicious
     */
    function delicious()
    {
        $url = "http://delicious.com/save?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Delicious", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/delicious.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Digg
     */
    function digg()
    {
        $url = "http://digg.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Digg", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/digg.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Friend feed
     */
    function friendfeed()
    {
        $url = "http://friendfeed.com/?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on FriendFeed", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/friendfeed.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Facebook
     */
    function facebook()
    {
        $url = "http://www.facebook.com/share.php?u={$this->encode_url}&t={$this->encode_title}";
        $alt = __( "Share on Facebook", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/facebook.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Facebook Like Button
     */
    function facebook_like()
    {
        $options = wp_social_bookmarking_light_options();
        $version = $options['facebook_like']['version'];
        $action = $options['facebook_like']['action'];
        $colorscheme = $options['facebook_like']['colorscheme'];
        $send = $options['facebook_like']['send'] ? 'true' : 'false';
        $width = $options['facebook_like']['width'];
        $font = $options['facebook_like']['font'];
        $locale = $options['facebook']['locale'];
        
        if($version == 'iframe'){
            return $this->link_raw('<iframe src="http://www.facebook.com/plugins/like.php?href='.$this->encode_url
                                    .'&amp;send='.$send
                                    .'&amp;layout=button_count'
                                    .'&amp;show_faces=false'
                                    .'&amp;width='.$width
                                    .'&amp;action='.$action
                                    .'&amp;colorscheme='.$colorscheme
                                    .($font == '' ? '' : '&amp;font='.$font)
                                    .($locale == '' ? '' : '&amp;locale='.$locale)
                                    .'&amp;height=21"'
                                    .' scrolling="no" frameborder="0"'
                                    .' style="border:none; overflow:hidden; width:'.$width.'px; height:21px;"'
                                    .' allowTransparency="true"></iframe>');
        }
        else{
            return $this->link_raw('<fb:like '
                                    .'href="'.$this->url.'" '
                                    .'send="'.$send.'" '
                                    .'layout="button_count" '
                                    .'width="'.$width.'" '
                                    .'show_faces="false" '
                                    .'action="'.$action.'" '
                                    .'colorscheme="'.$colorscheme.'" '
                                    .'font="'.$font.'">'
                                    .'</fb:like>');
        }
    }
    
    /**
     * @brief Facebook Send
     */
    function facebook_send()
    {
    	$options = wp_social_bookmarking_light_options();
    	$url = $this->url;
    	$font = $options['facebook_send']['font'];
    	$colorscheme = $options['facebook_send']['colorscheme'];
        
    	return $this->link_raw('<fb:send href="'.$url.'" font="'.$font.'" colorscheme="'.$colorscheme.'"></fb:send>');
    }

   /**
    * @brief reddit
    */
    function reddit()
    {
        $url = "http://www.reddit.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on reddit", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/reddit.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief LinkedIn
     */
    function linkedin()
    {
        $url = "http://www.linkedin.com/shareArticle?mini=true&url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on LinkedIn", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/linkedin.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Evernote
     */
    function evernote()
    {
        $options = wp_social_bookmarking_light_options();
        $type = $options['evernote']['button_type'];
        
        return $this->link_raw('<a href="#" onclick="Evernote.doClip({ title:\''.$this->title.'\', url:\''.$this->url.'\' });return false;">'
                                .'<img src="http://static.evernote.com/'.$type.'.png" />'
								.'</a>');
    }
    
    /**
     * @brief Instapaper
     */
    function instapaper()
    {
        return $this->link_raw('<iframe border="0" scrolling="no" width="78" height="17" allowtransparency="true" frameborder="0" '
                                .'style="margin-bottom: -3px; z-index: 1338; border: 0px; background-color: transparent; overflow: hidden;" '
                                .'src="http://www.instapaper.com/e2?url='.$this->encode_url.'&title='.$this->encode_title.'&description="'
                                .'></iframe>');
    }
    
    /**
     * @brief StumbleUpon
     */
    function stumbleupon()
    {
        $url = "http://www.stumbleupon.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on StumbleUpon", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/stumbleupon.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief mixi Check
     */
    function mixi()
    {
        $options = wp_social_bookmarking_light_options();
        $data_button = $options['mixi']['button'];
        $data_key = $options['mixi']['check_key'];
        
        return $this->link_raw( '<a href="http://mixi.jp/share.pl" class="mixi-check-button"'
                                 ." data-url='{$this->url}'"
                                 ." data-button='{$data_button}'"
                                 ." data-key='{$data_key}'>Check</a>"
                                 .'<script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script>' );
    }
    
    /**
     * @brief mixi Like
     */
    function mixi_like()
    {
        $options = wp_social_bookmarking_light_options();
        $data_key = $options['mixi']['check_key'];
        $width = $options['mixi_like']['width'];
        
        return $this->link_raw('<iframe src="http://plugins.mixi.jp/favorite.pl?href='.$this->encode_url.'&service_key='.$data_key.'&show_faces=false" '
                                .'scrolling="no" '
                                .'frameborder="0" '
            					.'allowTransparency="true" '
                                .'style="border:0; overflow:hidden; width:'.$width.'px; height:20px;"></iframe>');
    }
    
    /**
     * @brief GREE Social Feedback
     */
    function gree()
    {
    	$options = wp_social_bookmarking_light_options();
        $url = $this->encode_url;
        $type = $options['gree']['button_type'];
        $size = $options['gree']['button_size'];
        switch($type){
        	case '0': $btn_type = 'btn_iine'; break;
        	case '1': $btn_type = 'btn_kininaru'; break;
        	case '2': $btn_type = 'btn_osusume'; break;
        	case '3': $btn_type = 'btn_share'; break;
        	case '4': $btn_type = 'btn_logo'; break;
        	default: $btn_type = 'btn_logo';
        }
        $alt = __( "Share on GREE", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        return $this->link_raw('<a href="http://gree.jp/?mode=share&act=write'
                                 .'&url='.$url
                                 .'&button_type='.$type
                                 .'&button_size='.$size
                                 .'&guid=ON" '
                                 .'title="'.$alt.'" target=_blank>'
                                 .'<img alt="'.$alt.'" title="'.$alt.'" '
                                 .'src="http://i.share.gree.jp/img/share/button/'.$btn_type.'_'.$size.'.png">'
                                 .'</a>');
    }
    
    /**
     * @brief atode
     */
    function atode()
    {
    	$options = wp_social_bookmarking_light_options();
        $type = $options['atode']['button_type'];
        switch($type){
            case 'iconsja': return $this->link_raw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconsja.gif" alt="email this" border="0" align="absmiddle" width="16" height="16"></a>');
            case 'iconnja': return $this->link_raw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconnja.gif" alt="email this" border="0" align="absmiddle" width="66" height="20"></a>');
            case 'iconnen': return $this->link_raw('<a href=\'http://atode.cc/\' onclick=\'javascript:(function(){var s=document.createElement("scr"+"ipt");s.charset="UTF-8";s.language="javascr"+"ipt";s.type="text/javascr"+"ipt";var d=new Date;s.src="http://atode.cc/bjs.php?d="+d.getMilliseconds();document.body.appendChild(s)})();return false;\'><img src="http://atode.cc/img/iconnen.gif" alt="email this" border="0" align="absmiddle" width="66" height="20"></a>');
        }
        return '';
    }
    
    /**
     * @brief LINE
     */
    function line()
    {
    	$options = wp_social_bookmarking_light_options();
    	if($options['line']['button_type'] == "line88x20"){
    	    $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/line88x20.png";
    	    $width = 88;
    	    $height = 20;
    	}
    	else{
    	    $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/line20x20.png";
    	    $width = 20;
    	    $height = 20;
    	}
    	return $this->link("http://line.naver.jp/R/msg/text/?{$this->title}%0D%0A{$this->url}", "LINEで送る", $icon, $width, $height);
    }

    /**
     * @brief Pocket
     */
    function pocket()
    {
        $options = wp_social_bookmarking_light_options();
        return $this->link_raw('<a href="https://getpocket.com/save" class="pocket-btn" data-lang="en" data-save-url="' . $this->url . '" data-pocket-count="' . $options['pocket']['button_type'] . '" data-pocket-align="left" >Pocket</a><script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script>');
    }

}

/**
 * class method
 * @return array
 */
function wp_social_bookmarking_light_get_class_methods(){
    $all_methods = get_class_methods('WpSocialBookmarkingLight');
    $except_methods = array('WpSocialBookmarkingLight', 'wpsocialbookmarkinglight', 'to_utf8', 'link_raw', 'link', 'get_methods');
    $methods = array();
    foreach($all_methods as $method){
        if(in_array($method, $except_methods)){
            continue;
        }
        $methods[] = $method;
    }
    return $methods;
}
