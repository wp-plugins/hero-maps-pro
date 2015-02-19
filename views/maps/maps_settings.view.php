<script type="text/javascript" src="<?php echo $_GET['vp']; ?>js/maps_settings.view.js"></script>
<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Map Settings<br />
            <strong class="size_11 hero_grey">Change the basic settings for your map</strong>
        </h1>
        
        <div class="hero_section_holder hero_grey size_14"> 
        	<div class="hero_col_12">
                <div class="hero_col_6">
                    <label for="map_type" style="cursor:default;">
                        <h2 class="size_14 hero_green">Map Type</h2>
                        <p class="size_12 hero_grey"></p>
                    </label>
                </div>
                <div class="hero_col_6">
                    <select data-size="sml" id="map_type" name="map_type">
                    </select>
                </div>
            </div>
            <div class="hide_container map_theme_container">
            	<div class="internal">
                    <div class="hero_col_12">
                        <div class="hero_col_6">
                            <label for="map_theme" style="cursor:default;">
                                <h2 class="size_14 hero_green">Map Theme</h2>
                                <p class="size_12 hero_grey"></p>
                            </label>
                        </div>
                        <div class="hero_col_6">
                            <select data-size="sml" id="map_theme" name="map_theme">
                            </select>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
            	</div>
            </div>
        </div>
        
        <div class="hero_section_holder hero_grey size_14">
        	<div class="hero_col_12">
                <div class="hero_col_6">
                    <label for="autofit">
                        <h2 class="size_14 hero_green">Auto Fit</h2>
                        <p class="size_12 hero_grey">Auto-scale the map to the maximum zoom level where all markers are visible</p>
                    </label>
                </div>
                <div class="hero_col_6">
                    <input type="checkbox" data-size="lrg" id="autofit" name="autofit" value="" class="show_switch">
                </div>
            </div>
            <div class="hide_container">
            	<div class="internal">
                    <div class="hero_col_12">
                        <div class="hero_col_9">
                            <div class="hero_col_5">
                                <label for="map_center">Map Center</label><br>
                                <input type="text" data-size="lrg" id="map_center" name="map_center" onclick="jQuery(this).select();" readonly>
                            </div>
                            <div class="hero_col_1">&nbsp;</div>
                            <div class="hero_col_5">
                                <label for="rest_zoom">Rest Zoom</label><br>
                                <input type="text" data-size="lrg" id="rest_zoom" name="rest_zoom" readonly>
                            </div>
                        </div>
                        <div class="hero_col_12">
                            <div id="get_map_center_zoom_btn" class="hero_button_auto red_button rounded_3">Get map center and zoom</div>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        
        <div class="hero_section_holder hero_grey size_14"> 
        	<div class="hero_col_12">
                <div class="hero_col_6">
                    <label for="mouse_wheel_zoom">
                        <h2 class="size_14 hero_green">Mouse Wheel Zoom</h2>
                        <p class="size_12 hero_grey">Zoom in and out of the map with the mouse wheel</p>
                    </label>
                </div>
                <div class="hero_col_6">
                    <input type="checkbox" data-size="lrg" id="mouse_wheel_zoom" name="mouse_wheel_zoom" value="">
                </div>
            </div>
        </div>
        
	</div>
</div>