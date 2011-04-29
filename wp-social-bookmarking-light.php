<?php
/*
Plugin Name: WP Social Bookmarking Light
Plugin URI: http://www.ninxit.com/blog/2010/06/13/wp-social-bookmarking-light/
Description: This plugin inserts social share links at the top or bottom of each post.
Author: utahta
Author URI: http://www.ninxit.com/blog/
Version: 1.6.4
*/
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

define( "WP_SOCIAL_BOOKMARKING_LIGHT_URL", WP_PLUGIN_URL."/wp-social-bookmarking-light" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL", WP_SOCIAL_BOOKMARKING_LIGHT_URL."/images" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN", "wp-social-bookmarking-light" );

load_plugin_textdomain( WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN,
                        "wp-content/plugins/wp-social-bookmarking-light/po" );

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
        $charset = get_settings( 'blog_charset' );
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
        return $this->link_raw('<iframe allowtransparency="true" frameborder="0" scrolling="no"'
        						.' src="http://platform.twitter.com/widgets/tweet_button.html'
        						.'?url='.$this->encode_url
        						.'&amp;text='.$this->encode_title
        						.'&amp;via='.$twitter['via']
        						.'&amp;lang='.$twitter['lang']
        						.'&amp;count='.$twitter['count']
        						.'"'
        						.' style="width:'.$twitter['width'].'px; height:'.$twitter['height'].'px;">'
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
        $action = $options['facebook_like']['action'];
        $colorscheme = $options['facebook_like']['colorscheme'];
        
        return $this->link_raw('<iframe src="http://www.facebook.com/plugins/like.php?href='.$this->encode_url
        						.'&amp;layout=button_count'
        						.'&amp;show_faces=false'
        						.'&amp;width=80'
        						.'&amp;action='.$action
        						.'&amp;colorscheme='.$colorscheme
        						.'&amp;height=20"'
        						.' scrolling="no" frameborder="0"'
        						.' style="border:none; overflow:hidden; width:100px; height:20px;"'
        						.' allowTransparency="true"></iframe>');
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
        $url = "http://gree.jp/?mode=share&act=write&url={$this->encode_url}&title={$this->encode_title}&site_type=website";
        $alt = __( "Share on GREE", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/gree.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
}

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

function wp_social_bookmarking_light_default_options()
{
    return array( "services" => "hatena, hatena_users, facebook, google_buzz, yahoo, livedoor, friendfeed, tweetmeme",
                  "position" => "top",
                  "single_page" => true,
                  "is_page" => true,
                  "mixi" => array('check_key' => '',
                                   'check_robots' => 'noimage',
                                   'button' => 'button-3'),
                  "twitter" => array('via' => "",
                                      'lang' => "en",
                                      'count' => 'horizontal',
                                      'width' => '130',
                                      'height' => '20'),
                  "hatena_button" => array('layout' => 'standard'),
                  'facebook_like' => array('action' => 'like',
                                            'colorscheme' => 'light'),
    );
}

function wp_social_bookmarking_light_options()
{
    $options = get_option("wp_social_bookmarking_light_options", array());
    
    // Compatibility version 1.5.2 or less
    if(!is_array($options['mixi'])){
        $options['mixi'] = array();
        if(isset($options['mixi_check_key']) || isset($options['mixi_check_robots'])){
            $options['mixi']['check_key'] = $options['mixi_check_key'];
            $options['mixi']['check_robots'] = $options['mixi_check_robots'];
            unset($options['mixi_check_key']);
            unset($options['mixi_check_robots']);
        }
    }
    
    // array merge recursive overwrite (1 depth)
    $default_options = wp_social_bookmarking_light_default_options();
    foreach( $default_options as $key => $val ){
        if(is_array($default_options[$key])){
            if(!is_array($options[$key])){
                $options[$key] = array();
            }
            $options[$key] = array_merge($default_options[$key], $options[$key]);
        }
    }
    return array_merge( wp_social_bookmarking_light_default_options(), $options );
}

function wp_social_bookmarking_light_wp_head()
{
    // for mixi Check
    $options = wp_social_bookmarking_light_options();
    foreach( explode(",", $options['services']) as $service ){
        $service = trim($service);
        if( "mixi" == $service ){
?>
<meta name="mixi-check-robots" content="<?php echo $options['mixi']['check_robots'] ?>" />
<?php
            break;
        }
    }
    
?>
<style type="text/css">
div.wp_social_bookmarking_light{border:0 !important;padding:0 !important;margin:0 !important;}
div.wp_social_bookmarking_light div{float:left !important;border:0 !important;padding:0 4px 0 0 !important;margin:0 !important;height:20px !important;text-indent:0 !important;}
div.wp_social_bookmarking_light img{border:0 !important;padding:0;margin:0;vertical-align:top !important;}
.wp_social_bookmarking_light_clear{clear:both !important;}
a.wp_social_bookmarking_light_instapaper {display: inline-block;font-family: 'Lucida Grande', Verdana, sans-serif;font-weight: bold;font-size: 11px;-webkit-border-radius: 8px;-moz-border-radius: 8px;color: #fff;background-color: #626262;border: 1px solid #626262;padding: 0px 3px 0px;text-shadow: #3b3b3b 1px 1px 0px;min-width: 62px;text-align: center;vertical-align:top;line-height:20px;}
a.wp_social_bookmarking_light_instapaper, a.wp_social_bookmarking_light_instapaper:hover, a.wp_social_bookmarking_light_instapaper:active, a.wp_social_bookmarking_light_instapaper:visited {color: #fff; text-decoration: none; outline: none;}
.wp_social_bookmarking_light_instapaper:focus {outline: none;}
</style>
<?php
}

function wp_social_bookmarking_light_output( $services, $link, $title )
{
    $wp = new WpSocialBookmarkingLight( $link, $title, get_bloginfo('name') );
    $class_methods = wp_social_bookmarking_light_get_class_methods();
    $out = '';
    foreach( explode(",", $services) as $service ){
        $service = trim($service);
        if($service != ''){
            if(in_array($service, $class_methods)){
                $out .= '<div>'.call_user_func( array( $wp, $service ) ).'</div>'; // A WpSocialBookmarkingLight method is called.
            }
            else{
                $out .= "<div>[`$service` not found]</div>";
            }
        }
    }
    if( $out == '' ){
        return $out;
    }
    return "<div class='wp_social_bookmarking_light'>{$out}</div><br class='wp_social_bookmarking_light_clear' />";
}

function wp_social_bookmarking_light_output_e( $services=null, $link=null, $title=null )
{
    if($services == null){
        $options = wp_social_bookmarking_light_options();
        $services = $options['services'];
    }
    echo wp_social_bookmarking_light_output( $services, $link, $title );
}

function wp_social_bookmarking_light_the_content( $content )
{
    if( is_feed() || is_404() || is_robots() || is_comments_popup() || (function_exists( 'is_ktai' ) && is_ktai()) ){
       return $content;
    }
    
    $options = wp_social_bookmarking_light_options();
    if( $options['single_page'] && !is_singular() ){
        return $content;
    }
    if( !$options['is_page'] && is_page() ){
        return $content;
    }
    
    $out = wp_social_bookmarking_light_output( $options['services'], get_permalink(), get_the_title() );
    if( $out == '' ){
       return $content;
    }
    if( $options['position'] == 'top' ){
        return "{$out}{$content}";
    }
    else if( $options['position'] == 'bottom' ){
        return "{$content}{$out}";
    }
    return $content;
}

// admin pages
function wp_social_bookmarking_light_admin_print_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-draggable');
}

function wp_social_bookmarking_light_admin_print_styles()
{
    wp_enqueue_style('jquery-ui-tabs', WP_SOCIAL_BOOKMARKING_LIGHT_URL."/libs/jquery/css/pepper-grinder/jquery-ui-1.8.6.custom.css");
}

function wp_social_bookmarking_light_admin_head()
{
?>
<style type="text/css">
.wsbl_options{
    border: 1px solid #CCCCCC;
    background-color: #F8F8EB;
    vertical-align: top;
    margin: 0px 10px 10px 0px;
    padding: 0px;
}
.wsbl_options th{
    background-color: #E8E8DB;
    text-align: center;
    margin: 0px;
    padding: 3px;
}
.wsbl_options td{en
    text-align: left;
    margin: 0px;
    padding: 3px;
}

#wsbl_sortable, #wsbl_draggable {
	list-style-type: none;
	margin: 0;
	padding: 5px;
	overflow: auto;
	width: 160px;
	height: 240px;
	float: left;
	border: 1px solid #999;
	background-color: #FFF;
}
#wsbl_sortable li, #wsbl_draggable li{
	width: 120px;
	height: 20px;
	font-size: 12px;
	margin: 0px auto;
	padding: 3px;
	border: 1px solid #999;
	background-color: #F8F8EB;
	cursor: pointer;
}
.wsbl_sortable_highlight {
	border: 1px dashed #333 !important;
	background-color: transparent !important;
}
.wsbl_txt_draggable{
	float:left;
}
.wsbl_img_draggable{
	margin-left: auto;
	margin-right: 0;
	text-align: right;
	display: none;
}
.wsbl_point_left{
	float: left;
	height : 240px ;
	margin: 0 20px;
}
.wsbl_point_left img{
	margin-top: 90px;
}
</style>

<script type="text/javascript" charset="utf-8">
//<![CDATA[

function wsbl_get_service_codes()
{
    var val = jQuery("#services_id").val();
    return jQuery.map(val.split(","), function(n, i){
        return jQuery.trim(n);
    });
}

function wsbl_options_toggle(service_id, is_simply)
{
    var has_option = jQuery.inArray(service_id, wsbl_get_service_codes()) >= 0;
    
    var service_id_settings = "#" + service_id + "_settings";
    if(is_simply){
        has_option ? jQuery(service_id_settings).show() : jQuery(service_id_settings).hide();
    }
    else{
        has_option ? jQuery(service_id_settings).slideDown() : jQuery(service_id_settings).slideUp();
    }
}

function wsbl_update_services(is_simply)
{
	var vals = "";
	var service = jQuery("#wsbl_sortable .wsbl_txt_draggable");
	service.each(function(){
		vals += vals == "" ? "" : ",";
		vals += jQuery(this).text();
	});
	jQuery("#services_id").val(vals);
	
	is_simply = is_simply || false;
    wsbl_options_toggle("mixi", is_simply);
    wsbl_options_toggle("twitter", is_simply);
    wsbl_options_toggle("hatena_button", is_simply);
    wsbl_options_toggle("facebook_like", is_simply);
}

function wsbl_update_sortable()
{
	jQuery("#wsbl_sortable .wsbl_img_draggable").each(function(){
		var button = jQuery(this);
		button.css("display", "block"); // show delete button.
    	var img = jQuery("img", button);
    	img.mousedown(function(){
    		var p = jQuery(this).parents("li");
    		p.slideUp("fast", function(){
    			p.remove();
    			wsbl_update_services();
    		});
    	});
    	img.attr('src', '<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/close_button.png"?>');
    	img.hover(
        	function(){
        		jQuery(this).attr('src', '<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/close_button2.png"?>');
        	},
        	function(){
        		jQuery(this).attr('src', '<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/close_button.png"?>');
        	}
    	);
    });
}

// read onece
jQuery(document).ready(function(){
    jQuery("#wsbl_sortable").sortable({
    	placeholder: "wsbl_sortable_highlight",
    	update:function(e, ui){
    		wsbl_update_sortable();
    		wsbl_update_services();
    	}
    });
    
    jQuery("#wsbl_draggable li").draggable({
    	connectToSortable:"#wsbl_sortable",
    	helper:'clone',
    	revert:"invalid"
    });
    jQuery("#wsbl_draggable, #wsbl_sortable").disableSelection();

	wsbl_update_sortable();
    wsbl_update_services(true);
	
    jQuery("#tabs").tabs();
});
//]]>
</script>

<?php
}

function wp_social_bookmarking_light_admin_menu()
{
    if( function_exists('add_options_page') ){
        $page = add_options_page( 'WP Social Bookmarking Light', 
                          'WP Social Bookmarking Light', 
                          'manage_options', 
                          __FILE__, 
                          'wp_social_bookmarking_light_options_page' );
                          
        add_action('admin_print_styles-'.$page, 'wp_social_bookmarking_light_admin_print_styles');
        add_action('admin_print_scripts-'.$page, 'wp_social_bookmarking_light_admin_print_scripts');
        add_action('admin_head-'.$page, 'wp_social_bookmarking_light_admin_head');
    }
}

// initialize all
function wp_social_bookmarking_light_init()
{
    add_action( 'wp_head', 'wp_social_bookmarking_light_wp_head' );
    add_filter( 'the_content', 'wp_social_bookmarking_light_the_content' );
    add_action( 'admin_menu', 'wp_social_bookmarking_light_admin_menu' );
}
add_action( 'init', 'wp_social_bookmarking_light_init' );

// options page
function wp_social_bookmarking_light_options_page()
{
    if( isset( $_POST['save'] ) ){
        $options = array("services" => $_POST["services"],
                          "position" => $_POST["position"],
                          "single_page" => $_POST["single_page"] == 'true',
                          "is_page" => $_POST["is_page"] == 'true',
                          "mixi" => array('check_key' => $_POST["mixi_check_key"],
                          				   'check_robots' => $_POST["mixi_check_robots"],
                                           'button' => $_POST['mixi_button']),
                          "twitter" => array('via' => $_POST['twitter_via'],
                                              'lang' => $_POST['twitter_lang'],
                                              'count' => $_POST['twitter_count'],
                                              'width' => $_POST['twitter_width'],
                                              'height' => $_POST['twitter_height']),
                          'hatena_button' => array('layout' => $_POST['hatena_button_layout']),
                          'facebook_like' => array('action' => $_POST['facebook_like_action'],
                                                    'colorscheme' => $_POST['facebook_like_colorscheme']),
        );
        update_option( 'wp_social_bookmarking_light_options', $options );
        echo '<div class="updated"><p><strong>'.__( 'Options saved.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
    }
    else if( isset( $_POST['reset'] ) ){
        $options = wp_social_bookmarking_light_default_options();
        update_option( 'wp_social_bookmarking_light_options', $options );
        echo '<div class="updated"><p><strong>'.__( 'Reset options.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
    }
    else{
        $options = wp_social_bookmarking_light_options();
    }
    $class_methods = wp_social_bookmarking_light_get_class_methods();
?>

<div class="wrap">
    <h2>WP Social Bookmarking Light</h2>

    <form method='POST' action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"><span><?php _e("General Settings") ?></span></a></li>
            <li id='mixi_settings'><a href="#tabs-2"><span><?php _e("mixi") ?></span></a></li>
            <li id='twitter_settings'><a href="#tabs-3"><span><?php _e("twitter") ?></span></a></li>
            <li id='hatena_button_settings'><a href="#tabs-4"><span><?php _e("hatena_button") ?></span></a></li>
            <li id='facebook_like_settings'><a href="#tabs-5"><span><?php _e("facebook_like") ?></span></a></li>
            <li><a href="#tabs-10"><span><?php _e("Donate", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?></span></a></li>
        </ul>
        <div id="tabs-1">
            <table class='form-table'>
            <tr>
            <th scope="row"><?php _e('Position', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?>:</th>
            <td>
            <select name='position'>
            <option value='top' <?php if( $options['position'] == 'top' ) echo 'selected'; ?>>Top</option>
            <option value='bottom' <?php if( $options['position'] == 'bottom' ) echo 'selected'; ?>>Bottom</option>
            <option value='none' <?php if( $options['position'] == 'none' ) echo 'selected'; ?>>None</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row"><?php _e('Singular', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?>:</th>
            <td>
            <select name='single_page'>
            <option value='true' <?php if( $options['single_page'] == true ) echo 'selected'; ?>>Enabled</option>
            <option value='false' <?php if( $options['single_page'] == false ) echo 'selected'; ?>>Disabled</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row"><?php _e('Page', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?>:</th>
            <td>
            <select name='is_page'>
            <option value='true' <?php if( $options['is_page'] == true ) echo 'selected'; ?>>Enabled</option>
            <option value='false' <?php if( $options['is_page'] == false ) echo 'selected'; ?>>Disabled</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row"><?php _e('Services', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?>: <br/> <span style="font-size:10px">(drag-and-drop)</span></th>
            <td>
            	<input type="text" id='services_id' name='services' value="<?php echo $options['services'] ?>"size=120 style="font-size:12px;" onclick="this.select(0, this.value.length)" readonly/>
            	<br />
            	<br />
            	<ul id="wsbl_sortable">
            	<?php
            	foreach( explode(",", $options['services']) as $service ){
                    $service = trim($service);
                    if($service != ''){
                        if(in_array($service, $class_methods)){
                            echo "<li>"
                            	 ."<div class='wsbl_txt_draggable'>$service</div>"
                            	 ."<div class='wsbl_img_draggable'><img src=''></div>"
                            	 ."<br clear='both'>"
                            	 ."</li>\n";
                        }
                    }
            	}
            	?>
            	</ul>
            	<div class="wsbl_point_left"><img src='<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/point_left.png"?>'></div>
            	<ul id="wsbl_draggable">
            	<?php
            	foreach($class_methods as $method){
            	    echo "<li>"
            	    	 ."<div class='wsbl_txt_draggable'>$method</div>"
            	    	 ."<div class='wsbl_img_draggable'><img src=''></div>"
            	    	 ."<br clear='both'>"
            	    	 ."</li>\n";
            	}
            	?>
            	</ul>
            	<br clear="both"/>
            </td>
            </tr>
            </table>
        </div>
        
        <div id="tabs-2">
            <table class='form-table'>
            <tr>
            <th scope="row">Check Key:</th>
            <td>
            <input type="text" name='mixi_check_key' value="<?php echo $options['mixi']["check_key"] ?>" size=50 />
            </td>
            </tr>
            <tr>
            <th scope="row">Check Robots:</th>
            <td>
            <input type="text" name='mixi_check_robots' value="<?php echo $options['mixi']["check_robots"] ?>" size=50 />
            </td>
            </tr>
            <tr>
            <th scope="row">Layout:</th>
            <td>
            <select name='mixi_button'>
            <option value='button-1' <?php if( $options['mixi']['button'] == 'button-1' ) echo 'selected'; ?>>button-1</option>
            <option value='button-2' <?php if( $options['mixi']['button'] == 'button-2' ) echo 'selected'; ?>>button-2</option>
            <option value='button-3' <?php if( $options['mixi']['button'] == 'button-3' ) echo 'selected'; ?>>button-3</option>
            <option value='button-4' <?php if( $options['mixi']['button'] == 'button-4' ) echo 'selected'; ?>>button-4</option>
            </select>
            </td>
            </tr>
            </table>
        </div>

        <div id="tabs-3">
            <table class='form-table'>
            <tr>
            <th scope="row">Via: <br> <span style="font-size:10px">(your twitter account)</span></th>
            <td>
            <input type="text" name='twitter_via' value="<?php echo $options['twitter']['via'] ?>" size=50 />
            </td>
            </tr>
            <tr>
            <th scope="row">Language:</th>
            <td>
            <select name='twitter_lang'>
            <option value='en' <?php if( $options['twitter']['lang'] == 'en' ) echo 'selected'; ?>>English</option>
            <option value='fr' <?php if( $options['twitter']['lang'] == 'fr' ) echo 'selected'; ?>>French</option>
            <option value='de' <?php if( $options['twitter']['lang'] == 'de' ) echo 'selected'; ?>>German</option>
            <option value='es' <?php if( $options['twitter']['lang'] == 'es' ) echo 'selected'; ?>>Spanish</option>
            <option value='ja' <?php if( $options['twitter']['lang'] == 'ja' ) echo 'selected'; ?>>Japanese</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row">Count:</th>
            <td>
            <select name='twitter_count'>
            <option value='none' <?php if( $options['twitter']['count'] == 'none' ) echo 'selected'; ?>>none</option>
            <option value='horizontal' <?php if( $options['twitter']['count'] == 'horizontal' ) echo 'selected'; ?>>horizontal</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row">Width:</th>
            <td>
            <input type="text" name='twitter_width' value="<?php echo $options['twitter']['width'] ?>" size=20 />
            </td>
            </tr>
            <tr>
            <th scope="row">Height:</th>
            <td>
            <input type="text" name='twitter_height' value="<?php echo $options['twitter']['height'] ?>" size=20 />
            </td>
            </tr>
            </table>
        </div>

        <div id="tabs-4">
            <table class='form-table'>
            <tr>
            <th scope="row">Layout:</th>
            <td>
            <select name='hatena_button_layout'>
            <option value='standard' <?php if( $options['hatena_button']['layout'] == 'standard' ) echo 'selected'; ?>>standard</option>
            <option value='simple' <?php if( $options['hatena_button']['layout'] == 'simple' ) echo 'selected'; ?>>simple</option>
            </select>
            </td>
            </tr>
            </table>
        </div>

        <div id="tabs-5">
            <table class='form-table'>
            <tr>
            <th scope="row">Action:</th>
            <td>
            <select name='facebook_like_action'>
            <option value='like' <?php if( $options['facebook_like']['action'] == 'like' ) echo 'selected'; ?>>like</option>
            <option value='recommend' <?php if( $options['facebook_like']['action'] == 'recommend' ) echo 'selected'; ?>>recommend</option>
            </select>
            </td>
            </tr>
            <tr>
            <th scope="row">Color Scheme:</th>
            <td>
            <select name='facebook_like_colorscheme'>
            <option value='light' <?php if( $options['facebook_like']['colorscheme'] == 'light' ) echo 'selected'; ?>>light</option>
            <option value='dark' <?php if( $options['facebook_like']['colorscheme'] == 'dark' ) echo 'selected'; ?>>dark</option>
            </select>
            </td>
            </tr>
            </table>
        </div>
        
        <div id="tabs-10">
        	<p>Your donation will help the development of "WP Social Bookmarking Light".</p>
        	<p>If you find it useful for you, feel free to lend your support.</p>
            <a href='http://www.pledgie.com/campaigns/14051' target=_blank><img alt='Click here to lend your support to: WP Social Bookmarking Light and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/14051.png?skin_name=chrome' border='0' /></a>
        </div>

    </div>
    <p class="submit">
    <input class="button-primary" type="submit" name='save' value='<?php _e('Save Changes') ?>' />
    <input type="submit" name='reset' value='<?php _e('Reset') ?>' />
    </p>
    </form>
    
    <table class='wsbl_options'>
    <tr><th><?php _e("Service Code", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?></th><th><?php _e("Explain", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN) ?></th></tr>
    <tr><td>hatena</td><td>Hatena Bookmark</td></tr>
    <tr><td>hatena_users</td><td>Hatena Bookmark Users</td></tr>
    <tr><td>hatena_button</td><td>Hatena Bookmark Button</td></tr>
    <tr><td>twib</td><td>Twib - Twitter</td></tr>
    <tr><td>twib_users</td><td>Twib Users - Twitter</td></tr>
    <tr><td>tweetmeme</td><td>TweetMeme - Twitter</td></tr>
    <tr><td>twitter</td><td>Tweet Button - Twitter</td></tr>
    <tr><td>livedoor</td><td>Livedoor Clip</td></tr>
    <tr><td>livedoor_users</td><td>Livedoor Clip Users</td></tr>
    <tr><td>yahoo</td><td>Yahoo!JAPAN Bookmark</td></tr>
    <tr><td>yahoo_users</td><td>Yahoo!JAPAN Bookmark Users</td></tr>
    <tr><td>yahoo_buzz</td><td>Yahoo!Buzz</td></tr>
    <tr><td>buzzurl</td><td>BuzzURL</td></tr>
    <tr><td>buzzurl_users</td><td>BuzzURL Users</td></tr>
    <tr><td>nifty</td><td>@nifty Clip</td></tr>
    <tr><td>nifty_users</td><td>@nifty Clip Users</td></tr>
    <tr><td>tumblr</td><td>Tumblr</td></tr>
    <tr><td>fc2</td><td>FC2 Bookmark</td></tr>
    <tr><td>fc2_users</td><td>FC2 Bookmark Users</td></tr>
    <tr><td>newsing</td><td>newsing</td></tr>
    <tr><td>choix</td><td>Choix</td></tr>
    <tr><td>google</td><td>Google Bookmarks</td></tr>
    <tr><td>google_buzz</td><td>Google Buzz</td></tr>
    <tr><td>delicious</td><td>Delicious</td></tr>
    <tr><td>digg</td><td>Digg</td></tr>
    <tr><td>friendfeed</td><td>FriendFeed</td></tr>
    <tr><td>facebook</td><td>Facebook Share</td></tr>
    <tr><td>facebook_like</td><td>Facebook Like Button</td></tr>
    <tr><td>reddit</td><td>reddit</td></tr>
    <tr><td>linkedin</td><td>LinkedIn</td></tr>
    <tr><td>evernote</td><td>Evernote</td></tr>
    <tr><td>instapaper</td><td>Instapaper</td></tr>
    <tr><td>stumbleupon</td><td>StumbleUpon</td></tr>
    <tr><td>mixi</td><td>mixi Check (require <a href="http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck" onclick="window.open('http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck'); return false;" >mixi check key</a>)</td></tr>
    <tr><td>gree</td><td>GREE Social Feedback</td></tr>
    </table>
</div>

<?php
}

?>
