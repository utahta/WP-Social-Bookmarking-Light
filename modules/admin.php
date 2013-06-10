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
 * use jquery in admin page
 */
function wp_social_bookmarking_light_admin_print_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-draggable');
}

/**
 * use jquery-ui in admin page
 */
function wp_social_bookmarking_light_admin_print_styles()
{
    wp_enqueue_style('jquery-ui-tabs', WP_SOCIAL_BOOKMARKING_LIGHT_URL."/libs/jquery/css/pepper-grinder/jquery-ui-1.8.6.custom.css");
}

/**
 * admin header
 */
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
.wsbl_options td{
    text-align: left;
    margin: 0px;
    padding: 3px;
}

#wsbl_sortable, #wsbl_draggable {
    list-style-type: none;
    margin: 0;
    padding: 5px;
    overflow: auto;
    width: 165px;
    height: 240px;
    float: left;
    border: 1px solid #999;
    background-color: #FFF;
}
#wsbl_sortable li, #wsbl_draggable li{
    width: 135px;
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

/**
 * get services
 */
function wsbl_get_service_codes()
{
    var val = jQuery("#services_id").val();
    return jQuery.map(val.split(","), function(n, i){
        return jQuery.trim(n);
    });
}

/**
 * get tab id.
 */
function wsbl_get_tab_ids(service_id)
{
    if(service_id == 'facebook'){
        return ['facebook', 'facebook_like', 'facebook_send'];
    }
    if(service_id == 'mixi'){
        return ['mixi', 'mixi_like'];
    }
    return [service_id];
}

/**
 * has option
 */
function wsbl_has_option(service_id)
{
    var services = wsbl_get_service_codes();
    var ids = wsbl_get_tab_ids(service_id);
    for(var i in ids){
        if(jQuery.inArray(ids[i], services) >= 0){
            return true;
        }
    }
    return false;
}

/**
 * tab toggle
 */
function wsbl_tab_toggle(service_id, is_simply)
{
    var has_option = wsbl_has_option(service_id);
    var tab_id = service_id;
    
    var tab_id_settings = "#" + tab_id + "_settings";
    if(is_simply){
        has_option ? jQuery(tab_id_settings).show() : jQuery(tab_id_settings).hide();
    }
    else{
        has_option ? jQuery(tab_id_settings).slideDown() : jQuery(tab_id_settings).slideUp();
    }
}

/**
 * update services
 */
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
    var services = ['mixi', 'twitter', 'hatena_button', 'facebook', 'gree', 'evernote', 'tumblr', 'atode', 'google_plus_one', 'line', 'pocket'];
    for(var i in services){
        wsbl_tab_toggle(services[i], is_simply);
    }
}

/**
 * set sortable
 */
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

// main
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

/**
 * admin page
 */
function wp_social_bookmarking_light_options_page()
{
    if( isset( $_POST['save'] ) ){
        $options = wp_social_bookmarking_light_save_options($_POST);
        echo '<div class="updated"><p><strong>'.__( 'Options saved.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
    }
    else if( isset( $_POST['restore'] ) ){
        $options = wp_social_bookmarking_light_restore_default_options();
        echo '<div class="updated"><p><strong>'.__( 'Restore defaults.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
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
            <li><a href="#tabs-1_2"><span><?php _e("Styles") ?></span></a></li>
            <li id='mixi_settings'><a href="#tabs-2"><span><?php _el("mixi") ?></span></a></li>
            <li id='twitter_settings'><a href="#tabs-3"><span><?php _el("twitter") ?></span></a></li>
            <li id='hatena_button_settings'><a href="#tabs-4"><span><?php _el("hatena_button") ?></span></a></li>
            <li id='facebook_settings'><a href="#tabs-5"><span><?php _el("facebook") ?></span></a></li>
            <li id='gree_settings'><a href="#tabs-7"><span><?php _el("gree") ?></span></a></li>
            <li id='evernote_settings'><a href="#tabs-8"><span><?php _el("evernote") ?></span></a></li>
            <li id='tumblr_settings'><a href="#tabs-9"><span><?php _el("tumblr") ?></span></a></li>
            <li id='atode_settings'><a href="#tabs-10"><span><?php _el("atode") ?></span></a></li>
            <li id='google_plus_one_settings'><a href="#tabs-11"><span><?php _el("google_plus_one") ?></span></a></li>
            <li id='line_settings'><a href="#tabs-12"><span><?php _el("line") ?></span></a></li>
            <li id='pocket_settings'><a href="#tabs-13"><span><?php _el("pocket") ?></span></a></li>
            </ul>

        <!-- General -->
        <div id="tabs-1">
            <table class='form-table'>
            <tr>
                <th scope="row"><?php _el('Position') ?>:</th>
                <td>
                <select name='position'>
                <option value='top' <?php if( $options['position'] == 'top' ) echo 'selected'; ?>>Top</option>
                <option value='bottom' <?php if( $options['position'] == 'bottom' ) echo 'selected'; ?>>Bottom</option>
                <option value='both' <?php if( $options['position'] == 'both' ) echo 'selected'; ?>>Both</option>
                <option value='none' <?php if( $options['position'] == 'none' ) echo 'selected'; ?>>None</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _el('Singular') ?>:</th>
                <td>
                <select name='single_page'>
                <option value='true' <?php if( $options['single_page'] == true ) echo 'selected'; ?>>Yes</option>
                <option value='false' <?php if( $options['single_page'] == false ) echo 'selected'; ?>>No</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _el('Page') ?>:</th>
                <td>
                <select name='is_page'>
                <option value='true' <?php if( $options['is_page'] == true ) echo 'selected'; ?>>Yes</option>
                <option value='false' <?php if( $options['is_page'] == false ) echo 'selected'; ?>>No</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _el('Services') ?>: <br/> <span style="font-size:10px">(drag-and-drop)</span></th>
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
        
        <!-- Styles -->
        <div id="tabs-1_2">
            <table class='form-table'>
            <tr>
	            <th scope="row">Custom CSS:</th>
            	<td>
            		<textarea name="styles" rows="20" cols="80"><?php echo $options['styles'] ?></textarea>
            	</td>
            </tr>
            </table>
        </div>
        
        <!-- mixi -->
        <div id="tabs-2">
            <!-- General -->
            <strong>General</strong>
            <table class='form-table'>
            <tr>
                <th scope="row">Check Key:</th>
                <td>
                <input type="text" name='mixi_check_key' value="<?php echo $options['mixi']["check_key"] ?>" size=50 />
                </td>
            </tr>
            </table>
            <br/>
            
            <!-- mixi Check -->
            <strong>mixi Check</strong>
            <table class='form-table'>
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
            <br/>
			
			<!-- mixi Like -->
            <strong>mixi Like</strong>
            <table class='form-table'>
            <tr>
                <th scope="row">Width:</th>
                <td>
                <input type="text" name='mixi_like_width' value="<?php echo $options['mixi_like']["width"] ?>"/>
                </td>
            </tr>
            </table>
        </div>

        <!-- Twitter -->
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
            </table>
        </div>

        <!-- hatena button -->
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

        <!-- facebook -->
        <div id="tabs-5">
            <!-- General -->
            <strong>General</strong>
            <table class='form-table'>
            <tr>
                <th scope="row">Locale:</th>
                <td>
                <input type="text" name='facebook_locale' value="<?php echo $options['facebook']["locale"] ?>" /><br/>
                <span>en_US, ja_JP, fr_FR ...</span> see more <a href='http://developers.facebook.com/docs/internationalization/' target=_blank>facebook docs</a>
                </td>
            </tr>
            </table>
            <br/>

            <!-- Like Button -->
            <strong>Like Button</strong>
            <table class='form-table'>
            <tr>
                <th scope="row">Version:</th>
                <td>
                <select name='facebook_like_version'>
                <option value='iframe' <?php if( $options['facebook_like']['version'] == 'iframe' ) echo 'selected'; ?>>iframe</option>
                <option value='xfbml' <?php if( $options['facebook_like']['version'] == 'xfbml' ) echo 'selected'; ?>>xfbml</option>
                </select>
                </td>
            </tr>
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
            <tr>
                <th scope="row">Send button:<br> <span style="font-size:10px">(confirm width size)</span></th>
                <td>
                <select name='facebook_like_send'>
                <option value='true' <?php if( $options['facebook_like']['send'] == true ) echo 'selected'; ?>>Yes</option>
                <option value='false' <?php if( $options['facebook_like']['send'] == false ) echo 'selected'; ?>>No</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Width:</th>
                <td>
                <input type="text" name='facebook_like_width' value="<?php echo $options['facebook_like']['width'] ?>" size=20 />
                </td>
            </tr>
            <tr>
                <th scope="row">Font:</th>
                <td>
                <select name='facebook_like_font'>
                <option value='' <?php if( $options['facebook_like']['font'] == '' ) echo 'selected'; ?>></option>
                <option value='arial' <?php if( $options['facebook_like']['font'] == 'arial' ) echo 'selected'; ?>>arial</option>
                <option value='lucida+grande' <?php if( $options['facebook_like']['font'] == 'lucida+grande' ) echo 'selected'; ?>>lucida grande</option>
                <option value='tahoma' <?php if( $options['facebook_like']['font'] == 'tahoma' ) echo 'selected'; ?>>tahoma</option>
                <option value='trebuchet+ms' <?php if( $options['facebook_like']['font'] == 'trebuchet+ms' ) echo 'selected'; ?>>trebuchet ms</option>
                <option value='verdana' <?php if( $options['facebook_like']['font'] == 'verdana' ) echo 'selected'; ?>>verdana</option>
                </select>
                </td>
            </tr>
            </table>
            <br/>
            
            <!-- Send Button -->
            <strong>Send Button</strong>
            <table class='form-table'>
            <tr>
                <th scope="row">Color Scheme:</th>
                <td>
                <select name='facebook_send_colorscheme'>
                <option value='light' <?php if( $options['facebook_send']['colorscheme'] == 'light' ) echo 'selected'; ?>>light</option>
                <option value='dark' <?php if( $options['facebook_send']['colorscheme'] == 'dark' ) echo 'selected'; ?>>dark</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Font:</th>
                <td>
                <select name='facebook_send_font'>
                <option value='' <?php if( $options['facebook_send']['font'] == '' ) echo 'selected'; ?>></option>
                <option value='arial' <?php if( $options['facebook_send']['font'] == 'arial' ) echo 'selected'; ?>>arial</option>
                <option value='lucida+grande' <?php if( $options['facebook_send']['font'] == 'lucida+grande' ) echo 'selected'; ?>>lucida grande</option>
                <option value='tahoma' <?php if( $options['facebook_send']['font'] == 'tahoma' ) echo 'selected'; ?>>tahoma</option>
                <option value='trebuchet+ms' <?php if( $options['facebook_send']['font'] == 'trebuchet+ms' ) echo 'selected'; ?>>trebuchet ms</option>
                <option value='verdana' <?php if( $options['facebook_send']['font'] == 'verdana' ) echo 'selected'; ?>>verdana</option>
                </select>
                </td>
            </tr>
            </table>
        </div>
        
        <!-- gree -->
        <div id="tabs-7">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='gree_button_type'>
                <option value='0' <?php if( $options['gree']['button_type'] == '0' ) echo 'selected'; ?>><?php _el("iine") ?></option>
                <option value='1' <?php if( $options['gree']['button_type'] == '1' ) echo 'selected'; ?>><?php _el("kininaru") ?></option>
                <option value='2' <?php if( $options['gree']['button_type'] == '2' ) echo 'selected'; ?>><?php _el("osusume") ?></option>
                <option value='3' <?php if( $options['gree']['button_type'] == '3' ) echo 'selected'; ?>><?php _el("share") ?></option>
                <option value='4' <?php if( $options['gree']['button_type'] == '4' ) echo 'selected'; ?>><?php _el("logo") ?></option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Button size:</th>
                <td>
                <select name='gree_button_size'>
                <option value='16' <?php if( $options['gree']['button_size'] == '16' ) echo 'selected'; ?>>16</option>
                <option value='20' <?php if( $options['gree']['button_size'] == '20' ) echo 'selected'; ?>>20</option>
                </select>
                </td>
            </tr>
            </table>
        </div>

        <!-- evernote -->
        <div id="tabs-8">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='evernote_button_type' onchange='jQuery("#evernote_img").attr("src", "http://static.evernote.com/"+this.form.evernote_button_type.value+".png")'>
                <?php
                $button_types = array('article-clipper', 'article-clipper-remember', 'article-clipper-fr', 'article-clipper-es', 'article-clipper-jp', 'article-clipper-rus', 'site-mem-16');
                foreach($button_types as $button_type){
                    ?><option value='<?php echo $button_type ?>' <?php if( $options['evernote']['button_type'] == $button_type ) echo 'selected'; ?>><?php echo $button_type?></option><?php
                }
                ?>
                </select>
                <img id='evernote_img' style="vertical-align:middle" src='http://static.evernote.com/<?php echo $options['evernote']['button_type'] ?>.png'>
                </td>
            </tr>
            </table>
        </div>

        <!-- tumblr -->
        <div id="tabs-9">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='tumblr_button_type' onchange='jQuery("#tumblr_img").attr("src", "http://platform.tumblr.com/v1/share_"+this.form.tumblr_button_type.value+".png")'>
                <?php
                $button_types = array('1', '2', '3', '4');
                foreach($button_types as $button_type){
                    ?><option value='<?php echo $button_type ?>' <?php if( $options['tumblr']['button_type'] == $button_type ) echo 'selected'; ?>>share_<?php echo $button_type?></option><?php
                }
                ?>
                </select>
                <img id='tumblr_img' style="vertical-align:middle" src='http://platform.tumblr.com/v1/share_<?php echo $options['tumblr']['button_type'] ?>.png'>
                </td>
            </tr>
            </table>
        </div>

        <!-- atode -->
        <div id="tabs-10">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='atode_button_type' onchange='jQuery("#atode_img").attr("src", "http://atode.cc/img/"+this.form.atode_button_type.value+".gif")'>
                <?php
                $button_types = array('iconsja', 'iconnja', 'iconnen');
                foreach($button_types as $button_type){
                    ?><option value='<?php echo $button_type ?>' <?php if( $options['atode']['button_type'] == $button_type ) echo 'selected'; ?>><?php echo $button_type?></option><?php
                }
                ?>
                </select>
                <img id='atode_img' style="vertical-align:middle" src='http://atode.cc/img/<?php echo $options['atode']['button_type'] ?>.gif'>
                </td>
            </tr>
            </table>
        </div>

        <!-- google +1 -->
        <div id="tabs-11">
            <table class='form-table'>
            <tr>
                <th scope="row">Button size:</th>
                <td>
                <select name='google_plus_one_button_size'>
                <option value='small' <?php if( $options['google_plus_one']['button_size'] == 'small' ) echo 'selected'; ?>>small</option>
                <option value='medium' <?php if( $options['google_plus_one']['button_size'] == 'medium' ) echo 'selected'; ?>>medium</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Language:</th>
                <td>
                <select name='google_plus_one_lang'>
                <?php 
                $langs = array(
                    "ar" => "Arabic",
                    "ar" => "Arabic",
                    "bg" => "Bulgarian",
                    "ca" => "Catalan",
                    "zh-CN" => "Chinese (Simplified)",
                    "zh-TW" => "Chinese (Traditional)",
                    "hr" => "Croatian",
                    "cs" => "Czech",
                    "da" => "Danish",
                    "nl" => "Dutch",
                    "en-US" => "English (US)",
                    "en-GB" => "English (UK)",
                    "et" => "Estonian",
                    "fil" => "Filipino",
                    "fi" => "Finnish",
                    "fr" => "French",
                    "de" => "German",
                    "el" => "Greek",
                    "iw" => "Hebrew",
                    "hi" => "Hindi",
                    "hu" => "Hungarian",
                    "id" => "Indonesian",
                    "it" => "Italian",
                    "ja" => "Japanese",
                    "ko" => "Korean",
                    "lv" => "Latvian",
                    "lt" => "Lithuanian",
                    "ms" => "Malay",
                    "no" => "Norwegian",
                    "fa" => "Persian",
                    "pl" => "Polish",
                    "pt-BR" => "Portuguese (Brazil)",
                    "pt-PT" => "Portuguese (Portugal)",
                    "ro" => "Romanian",
                    "ru" => "Russian",
                    "sr" => "Serbian",
                    "sv" => "Swedish",
                    "sk" => "Slovak",
                    "sl" => "Slovenian",
                    "es" => "Spanish",
                    "es-419" => "Spanish (Latin America)",
                    "th" => "Thai",
                    "tr" => "Turkish",
                    "uk" => "Ukrainian",
                    "vi" => "Vietnamese",
                );
                foreach($langs as $key => $val){
                    $selected = $options['google_plus_one']['lang'] == $key ? "selected" : "";
                    echo "<option $selected value='$key'>$val</option>\n";
                }
                ?>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Annotation:</th>
                <td>
                <select name='google_plus_one_annotation'>
                <option value='none' <?php if( $options['google_plus_one']['annotation'] == "none" ) echo 'selected'; ?>>none</option>
                <option value='bubble' <?php if( $options['google_plus_one']['annotation'] == "bubble" ) echo 'selected'; ?>>bubble</option>
                <option value='inline' <?php if( $options['google_plus_one']['annotation'] == "inline" ) echo 'selected'; ?>>inline</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Inline size:</th>
                <td>
                <input type="text" name='google_plus_one_inline_size' value="<?php echo $options['google_plus_one']["inline_size"] ?>" />
                </td>
            </tr>
            
            </table>
        </div>

        <!-- line -->
        <div id="tabs-12">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='line_button_type' onchange='jQuery("#line_img").attr("src", "<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL ?>/"+this.form.line_button_type.value+".png")'>
                <?php
                $button_types = array('line20x20', 'line88x20');
                foreach($button_types as $button_type){
                    ?><option value='<?php echo $button_type ?>' <?php if( $options['line']['button_type'] == $button_type ) echo 'selected'; ?>><?php echo $button_type?></option><?php
                }
                ?>
                </select>
                <img id='line_img' style="vertical-align:middle" src='<?php echo WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/".$options['line']['button_type'] ?>.png'>
                </td>
            </tr>
            </table>
        </div>

        <!-- pocket -->
        <div id="tabs-13">
            <table class='form-table'>
            <tr>
                <th scope="row">Button type:</th>
                <td>
                <select name='pocket_button_type'>
                <option value='none' <?php if( $options['pocket']['button_type'] == 'none' ) echo 'selected'; ?>>none</option>
                <option value='horizontal' <?php if( $options['pocket']['button_type'] == 'horizontal' ) echo 'selected'; ?>>horizontal</option>
                <option value='vertical' <?php if( $options['pocket']['button_type'] == 'vertical' ) echo 'selected'; ?>>vertical</option>
                </select>
                </td>
            </tr>
            </table>
        </div>
        
    </div>
    <p class="submit">
    <input class="button-primary" type="submit" name='save' value='<?php _e('Save Changes') ?>' />
    <input type="submit" name='restore' value='<?php _e('Restore defaults') ?>' />
    </p>
    </form>
    
    <table class='wsbl_options'>
    <tr><th><?php _el("Service Code") ?></th><th><?php _el("Explain") ?></th></tr>
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
    <tr><td>google_plus_one</td><td>Google +1</td></tr>
    <tr><td>delicious</td><td>Delicious</td></tr>
    <tr><td>digg</td><td>Digg</td></tr>
    <tr><td>friendfeed</td><td>FriendFeed</td></tr>
    <tr><td>facebook</td><td>Facebook Share</td></tr>
    <tr><td>facebook_like</td><td>Facebook Like Button</td></tr>
    <tr><td>facebook_send</td><td>Facebook Send Button</td></tr>
    <tr><td>reddit</td><td>reddit</td></tr>
    <tr><td>linkedin</td><td>LinkedIn</td></tr>
    <tr><td>evernote</td><td>Evernote</td></tr>
    <tr><td>instapaper</td><td>Instapaper</td></tr>
    <tr><td>stumbleupon</td><td>StumbleUpon</td></tr>
    <tr><td>mixi</td><td>mixi Check (require <a href="http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck" onclick="window.open('http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck'); return false;" >mixi check key</a>)</td></tr>
    <tr><td>mixi_like</td><td>mixi Like (require <a href="http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck" onclick="window.open('http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck'); return false;" >mixi check key</a>)</td></tr>
    <tr><td>gree</td><td>GREE Social Feedback</td></tr>
    <tr><td>atode</td><td>atode (toread)</td></tr>
    <tr><td>line</td><td>LINE Button</td></tr>
    <tr><td>pocket</td><td>Pocket Button</td></tr>
    </table>
</div>

<?php
}

/**
 * admin menu
 */
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
