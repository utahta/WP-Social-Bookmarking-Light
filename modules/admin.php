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
    wp_enqueue_style('jquery-ui-tabs', wp_social_bookmarking_light_url("libs/jquery/css/pepper-grinder/jquery-ui-1.8.6.custom.css"));
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
    if(service_id == 'facebook_general'){
        return ['facebook_like', 'facebook_share', 'facebook_send'];
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
    var services = ['mixi', 'twitter', 'hatena_button', 'facebook_general', 'facebook_like', 'facebook_share', 'facebook_send',
                    'gree', 'evernote', 'tumblr', 'atode', 'google_plus_one', 'line', 'pocket', 'pinterest'];
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
        img.attr('src', '<?php echo wp_social_bookmarking_light_images_url("close_button.png")?>');
        img.hover(
            function(){
                jQuery(this).attr('src', '<?php echo wp_social_bookmarking_light_images_url("close_button2.png")?>');
            },
            function(){
                jQuery(this).attr('src', '<?php echo wp_social_bookmarking_light_images_url("close_button.png")?>');
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
    $service_types = wp_social_bookmarking_light_service_types();
?>

<div class="wrap">
    <h2>WP Social Bookmarking Light</h2>

    <form method='POST' action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"><span><?php _e("General Settings") ?></span></a></li>
            <li><a href="#tabs-1_2"><span><?php _e("Styles") ?></span></a></li>
            <li><a href="#tabs-1_3"><span><?php _e("Donate") ?></span></a></li>
            <li id='mixi_settings'><a href="#tabs-2"><span><?php _el("Mixi") ?></span></a></li>
            <li id='twitter_settings'><a href="#tabs-3"><span><?php _el("Twitter") ?></span></a></li>
            <li id='hatena_button_settings'><a href="#tabs-4"><span><?php _el("Hatena") ?></span></a></li>
            <li id='facebook_general_settings'><a href="#tabs-15"><span><?php _el("FB") ?></span></a></li>
            <li id='facebook_like_settings'><a href="#tabs-5"><span><?php _el("FB Like") ?></span></a></li>
            <li id='facebook_share_settings'><a href="#tabs-6"><span><?php _el("FB Share") ?></span></a></li>
            <li id='facebook_send_settings'><a href="#tabs-14"><span><?php _el("FB Send") ?></span></a></li>
            <li id='gree_settings'><a href="#tabs-7"><span><?php _el("GREE") ?></span></a></li>
            <li id='evernote_settings'><a href="#tabs-8"><span><?php _el("Evernote") ?></span></a></li>
            <li id='tumblr_settings'><a href="#tabs-9"><span><?php _el("tumblr") ?></span></a></li>
            <li id='atode_settings'><a href="#tabs-10"><span><?php _el("atode") ?></span></a></li>
            <li id='google_plus_one_settings'><a href="#tabs-11"><span><?php _el("Google Plus One") ?></span></a></li>
            <li id='line_settings'><a href="#tabs-12"><span><?php _el("LINE") ?></span></a></li>
            <li id='pocket_settings'><a href="#tabs-13"><span><?php _el("Pocket") ?></span></a></li>
            <li id='pinterest_settings'><a href="#tabs-16"><span><?php _el("Pinterest") ?></span></a></li>
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
                            if(in_array($service, $service_types)){
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
                    <div class="wsbl_point_left"><img src='<?php echo wp_social_bookmarking_light_images_url("point_left.png")?>'></div>
                    <ul id="wsbl_draggable">
                    <?php
                    foreach($service_types as $method){
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

        <!-- Donate -->
        <div id="tabs-1_3">
            <table class='form-table'>
                <tr>
                    <th></th>
                    <td>If you want to support this project, please make a donation.</td>
                </tr>
                <tr>
                    <th scope="row">Amazon:</th>
                    <td>
                        <p><b>To: labs.ninxit@gmail.com</b></p>
                        <p>
                            <a href="https://www.amazon.co.jp/gp/product/B005FOVUS2/" target="_blank">ギフト券 - Eメールタイプ</a>
                        </p>
                        <p>
                            <a href="http://www.amazon.co.jp/registry/wishlist/234SVHP1HFGPR" target=_blank>Wishlist - 欲しいものリスト</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Bitcoin:</th>
                    <td>
                        <p>Please Donate To Bitcoin Address:</p>
                        <p><b>16H1Q6ZaYC7jX7tBLK2Z3EV9HmQEk5QnBo</b></p>
                        <img src="https://cloud.githubusercontent.com/assets/97572/16043856/b8e50f52-327c-11e6-8c0c-bc1f3603d9e0.png">
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
                    <th scope="row">Related: <br> <span style="font-size:10px">(related twitter account)</span></th>
                    <td>
                        <input type="text" name='twitter_related' value="<?php echo $options['twitter']['related'] ?>" size=50 />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Hashtags: <br> <span style="font-size:10px"></span></th>
                    <td>
                        <input type="text" name='twitter_hashtags' value="<?php echo $options['twitter']['hashtags'] ?>" size=50 />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Dnt: <br> <span style="font-size:10px">(Opt-out of tailoring Twitter)</span></th>
                    <td>
                        <select name='twitter_dnt'>
                            <option value='true' <?php if( $options['twitter']['dnt'] == true ) echo 'selected'; ?>>Yes</option>
                            <option value='false' <?php if( $options['twitter']['dnt'] == false ) echo 'selected'; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Language:</th>
                    <td>
                    <select name='twitter_lang'>
                        <option value="" <?php if( $options['twitter']['lang'] == '' ) echo 'selected'; ?>>---</option>
                        <option value="fr" <?php if( $options['twitter']['lang'] == 'fr' ) echo 'selected'; ?>>French - français</option>
                        <option value="en" <?php if( $options['twitter']['lang'] == 'en' ) echo 'selected'; ?>>English</option>
                        <option value="ar" <?php if( $options['twitter']['lang'] == 'ar' ) echo 'selected'; ?>>Arabic - العربية</option>
                        <option value="ja" <?php if( $options['twitter']['lang'] == 'ja' ) echo 'selected'; ?>>Japanese - 日本語</option>
                        <option value="es" <?php if( $options['twitter']['lang'] == 'es' ) echo 'selected'; ?>>Spanish - Español</option>
                        <option value="de" <?php if( $options['twitter']['lang'] == 'de' ) echo 'selected'; ?>>German - Deutsch</option>
                        <option value="it" <?php if( $options['twitter']['lang'] == 'it' ) echo 'selected'; ?>>Italian - Italiano</option>
                        <option value="id" <?php if( $options['twitter']['lang'] == 'id' ) echo 'selected'; ?>>Indonesian - Bahasa Indonesia</option>
                        <option value="pt" <?php if( $options['twitter']['lang'] == 'pt' ) echo 'selected'; ?>>Portuguese - Português</option>
                        <option value="ko" <?php if( $options['twitter']['lang'] == 'ko' ) echo 'selected'; ?>>Korean - 한국어</option>
                        <option value="tr" <?php if( $options['twitter']['lang'] == 'tr' ) echo 'selected'; ?>>Turkish - Türkçe</option>
                        <option value="ru" <?php if( $options['twitter']['lang'] == 'ru' ) echo 'selected'; ?>>Russian - Русский</option>
                        <option value="nl" <?php if( $options['twitter']['lang'] == 'nl' ) echo 'selected'; ?>>Dutch - Nederlands</option>
                        <option value="fil" <?php if( $options['twitter']['lang'] == 'fil' ) echo 'selected'; ?>>Filipino - Filipino</option>
                        <option value="msa" <?php if( $options['twitter']['lang'] == 'msa' ) echo 'selected'; ?>>Malay - Bahasa Melayu</option>
                        <option value="zh-tw" <?php if( $options['twitter']['lang'] == 'zh-tw' ) echo 'selected'; ?>>Traditional Chinese - 繁體中文</option>
                        <option value="zh-cn" <?php if( $options['twitter']['lang'] == 'zh-cn' ) echo 'selected'; ?>>Simplified Chinese - 简体中文</option>
                        <option value="hi" <?php if( $options['twitter']['lang'] == 'hi' ) echo 'selected'; ?>>Hindi - हिन्दी</option>
                        <option value="no" <?php if( $options['twitter']['lang'] == 'no' ) echo 'selected'; ?>>Norwegian - Norsk</option>
                        <option value="sv" <?php if( $options['twitter']['lang'] == 'sv' ) echo 'selected'; ?>>Swedish - Svenska</option>
                        <option value="fi" <?php if( $options['twitter']['lang'] == 'fi' ) echo 'selected'; ?>>Finnish - Suomi</option>
                        <option value="da" <?php if( $options['twitter']['lang'] == 'da' ) echo 'selected'; ?>>Danish - Dansk</option>
                        <option value="pl" <?php if( $options['twitter']['lang'] == 'pl' ) echo 'selected'; ?>>Polish - Polski</option>
                        <option value="hu" <?php if( $options['twitter']['lang'] == 'hu' ) echo 'selected'; ?>>Hungarian - Magyar</option>
                        <option value="fa" <?php if( $options['twitter']['lang'] == 'fa' ) echo 'selected'; ?>>Farsi - فارسی</option>
                        <option value="he" <?php if( $options['twitter']['lang'] == 'he' ) echo 'selected'; ?>>Hebrew - עִבְרִית</option>
                        <option value="ur" <?php if( $options['twitter']['lang'] == 'ur' ) echo 'selected'; ?>>Urdu - اردو</option>
                        <option value="th" <?php if( $options['twitter']['lang'] == 'th' ) echo 'selected'; ?>>Thai - ภาษาไทย</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Size:</th>
                    <td>
                    <select name='twitter_size'>
                        <option value='' <?php if( $options['twitter']['size'] === '' ) echo 'selected'; ?>>normal</option>
                        <option value='large' <?php if( $options['twitter']['size'] === 'large' ) echo 'selected'; ?>>large</option>
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
                <option value='standard-balloon' <?php if( $options['hatena_button']['layout'] == 'standard-balloon' ) echo 'selected'; ?>>standard-balloon</option>
                <option value='standard-noballoon' <?php if( $options['hatena_button']['layout'] == 'standard-noballoon' ) echo 'selected'; ?>>standard-noballoon</option>
                <option value='standard' <?php if( $options['hatena_button']['layout'] == 'standard' ) echo 'selected'; ?>>standard</option>
                <option value='simple' <?php if( $options['hatena_button']['layout'] == 'simple' ) echo 'selected'; ?>>simple</option>
                <option value='simple-balloon' <?php if( $options['hatena_button']['layout'] == 'simple-balloon' ) echo 'selected'; ?>>simple-balloon</option>
                </select>
                </td>
            </tr>
            </table>
        </div>

        <!-- Facebook General -->
        <div id="tabs-15">
            <table class='form-table'>
            <tr>
                <th scope="row">Locale:</th>
                <td>
                <input type="text" name='facebook_locale' value="<?php echo $options['facebook']["locale"] ?>" /><br/>
                <span>en_US, ja_JP, fr_FR ...</span> see more <a href='http://developers.facebook.com/docs/internationalization/' target=_blank>facebook docs</a>
                </td>
            </tr>
            <tr>
                <th scope="row">Version:</th>
                <td>
                <select name='facebook_version'>
                <option value='html5' <?php if( $options['facebook']['version'] == 'html5' ) echo 'selected'; ?>>html5</option>
                <option value='xfbml' <?php if( $options['facebook']['version'] == 'xfbml' ) echo 'selected'; ?>>xfbml</option>
                <option value='iframe' <?php if( $options['facebook']['version'] == 'iframe' ) echo 'selected'; ?>>iframe</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Add fb-root:</th>
                <td>
                <select name='facebook_fb_root'>
                <option value='true' <?php if( $options['facebook']['fb_root'] == true ) echo 'selected'; ?>>Yes</option>
                <option value='false' <?php if( $options['facebook']['fb_root'] == false ) echo 'selected'; ?>>No</option>
                </select>
                </td>
            </tr>
            </table>
        </div>
        
        <!-- Facebook Like Button -->
        <div id="tabs-5">
            <!-- Like Button -->
            <table class='form-table'>
            <tr>
                <th scope="row">Layout:</th>
                <td>
                <select name='facebook_like_layout'>
                <option value='button' <?php if( $options['facebook_like']['layout'] == 'button' ) echo 'selected'; ?>>button</option>
                <option value='button_count' <?php if( $options['facebook_like']['layout'] == 'button_count' ) echo 'selected'; ?>>button_count</option>
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
                <th scope="row">Share:</th>
                <td>
                <select name='facebook_like_share'>
                <option value='true' <?php if( $options['facebook_like']['share'] == true ) echo 'selected'; ?>>Yes</option>
                <option value='false' <?php if( $options['facebook_like']['share'] == false ) echo 'selected'; ?>>No</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Width:</th>
                <td>
                <input type="text" name='facebook_like_width' value="<?php echo $options['facebook_like']['width'] ?>" size=20 />
                </td>
            </tr>
            </table>
        </div>
        
        <!-- Facebook Share Button -->
        <div id="tabs-6">
            <table class='form-table'>
            <tr>
                <th scope="row">Layout:</th>
                <td>
                <select name='facebook_share_type'>
                <option value='button' <?php if( $options['facebook_share']['type'] == 'button' ) echo 'selected'; ?>>button</option>
                <option value='button_count' <?php if( $options['facebook_share']['type'] == 'button_count' ) echo 'selected'; ?>>button_count</option>
                </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Width:</th>
                <td>
                <input type="text" name='facebook_share_width' value="<?php echo $options['facebook_share']['width'] ?>" size=20 />
                </td>
            </tr>
            </table>
        </div>
        
        <!-- Facebook Send Button -->
        <div id="tabs-14">
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
                <th scope="row">Width:</th>
                <td>
                <input type="text" name='facebook_send_width' value="<?php echo $options['facebook_send']['width'] ?>" size=20 />
                </td>
            </tr>
            <tr>
                <th scope="row">Height:</th>
                <td>
                <input type="text" name='facebook_send_height' value="<?php echo $options['facebook_send']['height'] ?>" size=20 />
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
                    <option value='standard' <?php if( $options['google_plus_one']['button_size'] == 'standard' ) echo 'selected'; ?>>standard</option>
                    <option value='tall' <?php if( $options['google_plus_one']['button_size'] == 'tall' ) echo 'selected'; ?>>tall</option>
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
                <select name='line_button_type' onchange='jQuery("#line_img").attr("src", "<?php echo wp_social_bookmarking_light_images_url() ?>/"+this.form.line_button_type.value+".png")'>
                <?php
                $button_types = array('line20x20', 'line88x20');
                foreach($button_types as $button_type){
                    ?><option value='<?php echo $button_type ?>' <?php if( $options['line']['button_type'] == $button_type ) echo 'selected'; ?>><?php echo $button_type?></option><?php
                }
                ?>
                </select>
                <img id='line_img' style="vertical-align:middle" src='<?php echo wp_social_bookmarking_light_images_url($options['line']['button_type']) ?>.png'>
                </td>
            </tr>
            <tr>
                <th scope="row">Protocol:</th>
                <td>
                    <select name='line_protocol'>
                        <option value='http' <?php if( $options['line']['protocol'] == 'http' ) echo 'selected'; ?>>http://</option>
                        <option value='line' <?php if( $options['line']['protocol'] == 'line' ) echo 'selected'; ?>>line://</option>
                    </select>
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

        <!-- Pinterest -->
        <div id="tabs-16">
            <table class='form-table'>
                <tr>
                    <th scope="row">Type: <br> <span style="font-size:10px"></span></th>
                    <td>
                        <select name='pinterest_type'>
                            <option value='all' <?php if( $options['pinterest']['type'] === 'all' ) echo 'selected'; ?>>All</option>
                            <option value='hover' <?php if( $options['pinterest']['type'] === 'hover' ) echo 'selected'; ?>>Hover</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Shape: <br> <span style="font-size:10px"></span></th>
                    <td>
                        <select name='pinterest_shape'>
                            <option value='rect' <?php if( $options['pinterest']['shape'] === 'rect' ) echo 'selected'; ?>>Rectangle</option>
                            <option value='round' <?php if( $options['pinterest']['shape'] === 'round' ) echo 'selected'; ?>>Round</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Size: <br> <span style="font-size:10px"></span></th>
                    <td>
                        <select name='pinterest_size'>
                            <option value='large' <?php if( $options['pinterest']['size'] === 'large' ) echo 'selected'; ?>>Large</option>
                            <option value='small' <?php if( $options['pinterest']['size'] === 'small' ) echo 'selected'; ?>>Small</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Color: <br> <span style="font-size:10px"></span></th>
                    <td>
                        <select name='pinterest_color'>
                            <option value='red' <?php if( $options['pinterest']['color'] === 'red' ) echo 'selected'; ?>>Red</option>
                            <option value='gray' <?php if( $options['pinterest']['color'] === 'gray' ) echo 'selected'; ?>>Gray</option>
                            <option value='white' <?php if( $options['pinterest']['color'] === 'white' ) echo 'selected'; ?>>White</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Language:</th>
                    <td>
                        <select name='pinterest_lang'>
                            <option value="en" <?php if( $options['pinterest']['lang'] == 'en' ) echo 'selected'; ?>>English</option>
                            <option value="ja" <?php if( $options['pinterest']['lang'] == 'ja' ) echo 'selected'; ?>>Japanese - 日本語</option>
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
    <tr><td>twitter</td><td>Tweet Button - Twitter</td></tr>
    <tr><td>livedoor</td><td>Livedoor Clip</td></tr>
    <tr><td>livedoor_users</td><td>Livedoor Clip Users</td></tr>
    <tr><td>yahoo</td><td>Yahoo!JAPAN Bookmark</td></tr>
    <tr><td>yahoo_users</td><td>Yahoo!JAPAN Bookmark Users</td></tr>
    <tr><td>yahoo_buzz</td><td>Yahoo!Buzz</td></tr>
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
    <tr><td>facebook_share</td><td>Facebook Share Button</td></tr>
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
    <tr><td>pinterest</td><td>Pinterest Button</td></tr>
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
