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
    
?>
<style type="text/css">
div.wp_social_bookmarking_light{border:0 !important;padding:0 !important;margin:0 !important;}
div.wp_social_bookmarking_light div{float:left !important;border:0 !important;padding:0 4px 0 0 !important;margin:0 !important;height:21px !important;text-indent:0 !important;}
div.wp_social_bookmarking_light img{border:0 !important;padding:0;margin:0;vertical-align:top !important;}
.wp_social_bookmarking_light_clear{clear:both !important;}
a.wp_social_bookmarking_light_instapaper {display: inline-block;font-family: 'Lucida Grande', Verdana, sans-serif;font-weight: bold;font-size: 11px;-webkit-border-radius: 8px;-moz-border-radius: 8px;color: #fff;background-color: #626262;border: 1px solid #626262;padding: 0px 3px 0px;text-shadow: #3b3b3b 1px 1px 0px;min-width: 62px;text-align: center;vertical-align:top;line-height:21px;}
a.wp_social_bookmarking_light_instapaper, a.wp_social_bookmarking_light_instapaper:hover, a.wp_social_bookmarking_light_instapaper:active, a.wp_social_bookmarking_light_instapaper:visited {color: #fff; text-decoration: none; outline: none;}
.wp_social_bookmarking_light_instapaper:focus {outline: none;}
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
    return $content;
}
