<?php
	#PLUGIN CORE FRAMEWORK PANEL
?>
<script type="text/javascript">
	var plugin_name = '<?php echo $plugin_name; ?>';
	var plugin_friendly_name = '<?php echo $plugin_friendly_name; ?>';
	var plugin_friendly_description = '<?php echo $plugin_friendly_description; ?>';
	var plugin_version = '<?php echo 'Version: '. $plugin_version; ?>';
	var plugin_first_release = '<?php if(isset($first_release)){ echo $hmapspro_helper->friendly_date($first_release); }else{ echo 'Unknown'; } ?>';
	var plugin_last_updated = '<?php if(isset($last_updated)){ echo $hmapspro_helper->friendly_date($last_updated); }else{ echo 'Unknown'; } ?>';
	var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
	var plugin_url = '<?php echo $plugin_url; ?>';
</script>
<script type="text/javascript" src="<?php echo $plugin_url; ?>/views/sidebar_prepopulation.js"></script>
<div class="hero_message_status">
</div>
<div class="hero_popup_main">
    <div class="hero_popup_resize">        	
        <div class="hero_popup_container">
            <div class="hero_popup_inner"></div>
        </div>
        <div class="hero_popup_update">
            <div class="popup_buttons">
                <div class="hero_button_auto green_button rounded_3 hero_popup_update_btn">Update</div>
                <div class="hero_button_auto red_button rounded_3 hero_popup_cancel_btn">Cancel</div>
            </div>
        </div>
    </div>
</div>
<div class="hero_main">
    <div class="hero_sidebar">
        <div class="hero_sidebar_logo" onclick="window.open('http://herodev.co.za/plug/wp/');"></div>
        <div class="hero_sidebar_nav"></div>
        <div class="hero_sidebar_links">
            <div class="sidebar_link_sep"></div>
            <div class="hero_sidebar_item hero_docs">
                <div class="hero_sidebar_parent" onclick="window.open('http://herodev.co.za/plug/wp/document/maps-documentation/?pid=35');">
                    <div class="hero_sidebar_icon"></div>
                    <div class="hero_sidebar_label">Documentation</div>
                </div>
			</div>
            <div class="hero_sidebar_item hero_website">
                <div class="hero_sidebar_parent" onclick="window.open('http://herodev.co.za/plug/wp/');">
                    <div class="hero_sidebar_icon"></div>
                    <div class="hero_sidebar_label">heroplugins.com</div>
                </div>
			</div>
        </div>
    </div>
    <div class="hero_admin"></div>
</div>
