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
        $blogname = $this->to_utf8( $blogname );
        $this->url = $url;
        $this->title = $title;
        $this->encode_url = rawurlencode( $url );
        $this->encode_title = rawurlencode( $title );
        $this->encode_blogname = rawurlencode( $blogname );
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
                                .'<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>');
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
        $width = $twitter['width'] != '' ? $twitter['width'] : '120';
        $height = $twitter['height'] != '' ? $twitter['height'] : '20';
        return $this->link_raw('<iframe allowtransparency="true" frameborder="0" scrolling="no"'
                                .' src="http://platform.twitter.com/widgets/tweet_button.html'
                                .'?url='.$this->encode_url
                                .'&amp;text='.$this->encode_title
                                .($twitter['via'] != '' ? '&amp;via='.$twitter['via'] : '')
                                .'&amp;lang='.$twitter['lang']
                                .'&amp;count='.$twitter['count']
                                .'"'
                                .' style="width:'.$width.'px; height:'.$twitter['height'].'px;">'
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
        $url = "http://www.tumblr.com/share?v=3&u={$this->encode_url}&t={$this->encode_title}";
        $alt = __( "Share on Tumblr", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/tumblr.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
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
                                    .($locale == '' ? '' : '$amp;locale='.$locale)
                                    .'&amp;height=21"'
                                    .' scrolling="no" frameborder="0"'
                                    .' style="border:none; overflow:hidden; width:'.$width.'px; height:21px;"'
                                    .' allowTransparency="true"></iframe>');
        }
        else{
            $locale = ($locale == '' ? 'en_US' : $locale);
            return $this->link_raw('<script src="http://connect.facebook.net/'.$locale.'/all.js#xfbml=1"></script>'
                                    .'<fb:like '
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
        $locale = $options['facebook']['locale'];
        $locale = ($locale == '' ? 'en_US' : $locale);
        
    	return $this->link_raw('<script src="http://connect.facebook.net/'.$locale.'/all.js#xfbml=1"></script>'
    	                        .'<fb:send href="'.$url.'" font="'.$font.'" colorscheme="'.$colorscheme.'"></fb:send>');
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
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/evernote.png";
        $script = "(function(){EN_CLIP_HOST='http://www.evernote.com';try{var x=document.createElement('SCRIPT');x.type='text/javascript';x.src=EN_CLIP_HOST+'/public/bookmarkClipper.js?'+(new Date().getTime()/100000);document.getElementsByTagName('head')[0].appendChild(x);}catch(e){location.href=EN_CLIP_HOST+'/clip.action?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title);}})();";
        $img = "<img src='${icon}' width='16' height='16' />";
        return $this->link_raw( "<a href='#' title='Clip to Evernote' onclick=\"${script} return false;\">${img}</a>" );
    }
    
    /**
     * @brief Instapaper
     */
    function instapaper()
    {
        $href = "javascript:function iprl5(){var d=document,z=d.createElement(&#039;scr&#039;+&#039;ipt&#039;),b=d.body,l=d.location;try{if(!b)throw(0);d.title=&#039;(Saving...) &#039;+d.title;z.setAttribute(&#039;src&#039;,l.protocol+&#039;//www.instapaper.com/j/GKo8MDzHWjRx?u=&#039;+encodeURIComponent(l.href)+&#039;&amp;t=&#039;+(new Date().getTime()));b.appendChild(z);}catch(e){alert(&#039;Please wait until the page has loaded.&#039;);}}iprl5();void(0)";
        return $this->link_raw( '<a href="'.$href.'" class="wp_social_bookmarking_light_instapaper" style="line-height:17px !important" title="Read Later">Read Later</a>' );
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
}

/**
 * class method
 * @return array
 */
function wp_social_bookmarking_light_get_class_methods(){
    $all_methods = get_class_methods('WpSocialBookmarkingLight');
    $except_methods = array('WpSocialBookmarkingLight', 'to_utf8', 'link_raw', 'link', 'get_methods');
    $methods = array();
    foreach($all_methods as $method){
        if(in_array($method, $except_methods)){
            continue;
        }
        $methods[] = $method;
    }
    return $methods;
}
