<script type="text/javascript" src="<?php echo $_GET['v']; ?>js/view.core.js"></script>

<div class="hero_maps_pro">
    
    <div class="hero_viewport"></div>

	<!--BEGIN: map specific views-->
    <div class="hero_map_content_holder">
        
        <div id="hero_map_main"></div>
        
        <div id="location_marker_coords_change_listener"></div>
        
        <div class="hero_map_marker_details">
        	<div class="hero_section_holder hero_grey size_14"> 
        	
                <div class="hero_col_12" style="padding:10px 0 0 15px;">
                    <div class="hero_col_4">
                    	<div class="hero_col_1" style="display:table; float:left; width:auto; text-align:right;"><img id="edit_location_marker_img" width="30" src=""></div>
                        <div class="hero_col_11"><h2 class="size_14 hero_green" style="padding-top:10px;">Edit Location Marker</h2></div>
                    </div>
                    <div class="hero_col_4">
                        <div class="hero_col_12" style="margin:-10px 0 0 0;">
                        	<div id="custom_param_container" style="visibility:hidden;">
                                <div class="hero_col_4">
                                    <label for="custom_param">
                                        <h2 style="line-height:26px" class="size_14 hero_red">Custom Parameter</h2>
                                    </label>
                                </div>
                                <div class="hero_col_8" style="padding-right:0 !important; width:66.66666666666666% !important;">
                                    <input data-tooltip="The Custom Parameter will be passed to your JS callback when this marker is clicked" style="background-color:#E09092; color:#FFF;" type="text" data-size="lrg" id="custom_param" name="custom_param" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero_col_4" style="margin:-10px 0 0 -10px; padding-bottom:2px;">
                        <div style="display:table; float:right; width:auto;" class="hero_col_12">
                            <div id="del_location_marker_btn" class="hero_button_auto red_button rounded_3"><img></div>
                            <script type="text/javascript">
                                jQuery('#del_location_marker_btn img').attr('src',plugin_url +'assets/images/admin/delete_btn_img.png');
                            </script>
                            <div id="done_location_marker_btn" class="hero_button_auto green_button rounded_3">DONE</div>
                        </div>
                    </div>
                </div>
            
            	<div class="input_containers hero_col_12" style="padding:0 0 0 15px;">
                	<div class="hero_col_4">
                        <div class="hero_col_12">
                            <label for="location_title">
                               	<h2 class="size_14 hero_green">Location Title</h2>
                            </label>
                            <input type="text" data-size="lrg" id="location_title" name="location_title" maxlength="50">
                        </div>
                        
                        <div class="hero_col_12">
                            <label for="location_coordinates">
                                <h2 class="size_14 hero_green">Location Coordinates</h2>
                            </label>
                           	<input type="text" data-size="lrg" id="location_coordinates" name="location_coordinates" onClick="jQuery(this).select();" readonly>
                        </div>
                    </div>
                    <div class="hero_col_4">
                    	<div class="hero_col_12">
                            <div style="display:table; float:left;">
                                <label for="info_window_content">
                                    <h2 class="size_14 hero_green">Info Window Content</h2>
                                </label>
                            </div>
                            <div style="display:table; float:right;"><input style="opacity: 0; position:absolute; z-index: 5550; margin-top:0;" type="checkbox" data-size="sml" id="info_window_show" name="info_window_show" value="1"></div>
                            <textarea style="height:108px !important; max-height:108px; min-height:108px;" data-size="lrg" id="info_window_content" name="info_window_content" readonly></textarea>
                    	</div>
                    </div>
                    
                    <div class="hero_col_4">
                        <div class="hero_col_12">
                        	<div class="hero_col_5">
                                <label for="link_title">
                                    <h2 class="size_14 hero_green">Link Title</h2>
                                </label>
                                <input type="text" data-size="lrg" id="link_title" name="link_title" maxlength="20">
                            </div>
                            <div class="hero_col_7">
                            	<div style="display:table; float:left;">
                                    <label for="link">
                                        <h2 class="size_14 hero_green">Link</h2>
                                    </label>
                                </div>
                                <div style="display:table; float:right;"><input style="opacity: 0; position:absolute; z-index: 5550; margin-top:0;" type="checkbox" data-size="sml" id="link_show" name="link_show" value="1"></div>
                                <input placeholder="e.g. http://www.heroplugins.com" type="text" data-size="lrg" id="link" name="link" maxlength="255">
                            </div>
                        </div>
                        
                        <div class="hero_col_12">
                            <div class="hero_col_5">
                                <label for="link_colour">
                                    <h2 class="size_14 hero_green">Link Colour</h2>
                                </label>
                                <input type="text" id="link_colour" class="color_picker" name="link_colour" value="" maxlength="7">
                            </div>
                            <div class="hero_col_7">
                                <label for="link_target" style="cursor:default;">
                                    <h2 class="size_14 hero_green">Link Target</h2>
                                </label>
                                <select style="display:none;" data-size="lrg" id="link_target" name="link_target" class="has-error">
                                    <option value="_blank">New Window</option>
                                    <option value="_self">Same Window</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
            
            </div>
        </div>
        
        <div class="hero_map_search_bar">
        	<input class="location_search_input" name="location_search" id="location_search" type="text" placeholder="Search Map" />
        </div>
        
    </div>
    <!--END: map specific views-->

</div>