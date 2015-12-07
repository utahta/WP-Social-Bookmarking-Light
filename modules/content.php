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
 * html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 * @return string
 */
function wp_social_bookmarking_light_output( $services, $link, $title )
{
    $wp = new WpSocialBookmarkingLight( $link, $title, get_bloginfo('name') );
    $class_methods = wp_social_bookmarking_light_get_class_methods();
    $out = '';
    foreach( explode(",", $services) as $service ){
        $service = trim($service);
        if($service != ''){
            if(in_array($service, $class_methods)){
                $out .= '<div class="wsbl_'.$service.'">'.call_user_func( array( $wp, $service ) ).'</div>'; // WpSocialBookmarkingLight method
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

/**
 * echo html format
 *
 * @param string $services
 * @param string $link
 * @param string $title
 */
function wp_social_bookmarking_light_output_e( $services=null, $link=null, $title=null )
{
    if($services == null){
        $options = wp_social_bookmarking_light_options();
        $services = $options['services'];
    }
    echo wp_social_bookmarking_light_output( $services, $link, $title );
}

/**
 * add_action wp_head
 */
function wp_social_bookmarking_light_wp_head()
{
?>
<!-- BEGIN: WP Social Bookmarking Light -->
<?php
    // load options
    $options = wp_social_bookmarking_light_options();
    $services = explode(",", $options['services']);

    // mixi-check-robots
    if(in_array('mixi', $services)){
?>
<meta name="mixi-check-robots" content="<?php echo $options['mixi']['check_robots'] ?>" />
<?php
    }

    // load javascript
    // tumblr
    if(in_array('tumblr', $services)){
        ?><script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script><?php
    }
    // facebook
    if(in_array('facebook_like', $services)  ||
       in_array('facebook_share', $services) ||
       in_array('facebook_send', $services)){
        $version = $options['facebook']['version'];
        if($version == "html5" || $version == "xfbml"){
            $locale = $options['facebook']['locale'];
            $locale = ($locale == '' ? 'en_US' : $locale);
?>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $locale ?>/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
        }
    }

    // css
?>
<style type="text/css">
<?php echo $options['styles'] ?>
</style>
<!-- END: WP Social Bookmarking Light -->
<?php
}

/**
 * add_filter the_content.
 */
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
    else if( $options['position'] == 'both'){
        return "{$out}{$content}{$out}";
    }
    return $content;
}

/**
 * wp_footer function
 */
function wp_social_bookmarking_light_wp_footer()
{
?>
<!-- BEGIN: WP Social Bookmarking Light -->
<?php
    // load options
    $options = wp_social_bookmarking_light_options();
    $services = explode(",", $options['services']);

    /*
     * load javascript
     */
    // twitter
    if (in_array('twitter', $services)) {
        echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>\n";
    }
    // evernote
    if (in_array('evernote', $services)) {
        echo '<script type="text/javascript" src="http://static.evernote.com/noteit.js"></script>'."\n";
    }
    // Google +1
    if (in_array('google_plus_one', $services)) {
        $lang = $options['google_plus_one']['lang'];
        echo '<script src="https://apis.google.com/js/platform.js" async defer>{lang: "'.$lang.'"}</script>'."\n";
    }
    // pinterest
    if (in_array('pinterest', $services)) {
        if ($options['pinterest']['type'] === 'all') {
            $data_pin_hover = $data_pin_shape = $data_pin_color = $data_pin_lang = $data_pin_height = '';
        } else {
            $data_pin_hover = 'data-pin-hover="true"';
            $shape = $options['pinterest']['shape'];
            $data_pin_shape = $shape === 'round' ? 'data-pin-shape="round"' : '';
            $data_pin_color = 'data-pin-color="'.$options['pinterest']['color'];
            $data_pin_lang = 'data-pin-lang="'.$options['pinterest']['lang'];
            $data_pin_height = '';
            if ($options['pinterest']['size'] === 'large') {
                $data_pin_height = $shape === 'round' ? 'data-pin-height="32"' : 'data-pin-height="28"';
            }
        }
        echo '<script type="text/javascript" async defer  '
            .$data_pin_shape.' '
            .$data_pin_color.' '
            .$data_pin_lang.' '
            .$data_pin_height.' '
            .$data_pin_hover.' '
            .'src="//assets.pinterest.com/js/pinit.js"></script>';
    }

    ?>
<!-- END: WP Social Bookmarking Light -->
<?php
}
