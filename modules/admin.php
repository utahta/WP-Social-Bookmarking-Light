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
 * admin header
 */
function wp_social_bookmarking_light_admin_head()
{
?>
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
                    'gree', 'tumblr', 'atode', 'google_plus_one', 'line', 'pocket', 'pinterest'];
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
