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
    if(in_array('facebook_like', $services) || in_array('facebook_send', $services)){
        $locale = $options['facebook']['locale'];
        $locale = ($locale == '' ? 'en_US' : $locale);
        echo '<script type="text/javascript" src="http://connect.facebook.net/'.$locale.'/all.js#xfbml=1"></script>'."\n";
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
    // evernote
    if(in_array('evernote', $services)){
        echo '<script type="text/javascript" src="http://static.evernote.com/noteit.js"></script>'."\n";
    }
    // Google +1
    if(in_array('google_plus_one', $services)){
        $lang = $options['google_plus_one']['lang'];
?>
<script type="text/javascript">
  window.___gcfg = {lang: '<?php echo $lang ?>'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
    }
    
?>
<!-- END: WP Social Bookmarking Light -->
<?php
}
