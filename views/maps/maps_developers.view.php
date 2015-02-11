<script type="text/javascript" src="<?php echo $_GET['vp']; ?>js/maps_developers.view.js"></script>
<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Developers<br />
            <strong class="size_11 hero_grey">Enable advanced functionality</strong>
        </h1>
        
        <div class="hero_section_holder hero_grey size_14"> 
			<div class="hero_col_12">
                <div class="hero_col_6">
                    <label for="javascript_callback">
                        <h2 class="size_14 hero_green">Marker "onclick" Event</h2>
                        <p class="size_12 hero_grey">
                        	With this feature enabled, you will be able to add a JavaScript method that will be called onclick of a location marker.
                        </p>
                    </label>
                </div>
                <div class="hero_col_6">
                    <input type="checkbox" data-size="lrg" id="javascript_callback" name="javascript_callback" value="1" class="hide_switch">
                </div>
            </div>
            <div class="hide_container">
            	<div class="internal">
                    <div class="hero_col_12">
                        <div class="hero_col_6">
                            <label for="callback_method">
                                <h2 class="size_14 hero_green">Javascript Method</h2>
                                <p class="size_12 hero_grey">Specify the method that will be called when a location marker is clicked</p>
                            </label>
                        </div>
                        <div class="hero_col_6">
                            <input type="text" data-size="sml" id="callback_method" name="callback_method" maxlength="30">
                        </div>
                    </div>
                    <div class="hero_col_12">
<!--BEGIN: sample code-->
<span class="hero_red size_12">Example method</span>
<pre class="size_12">
//show location marker data
function show_location_marker_data(marker_data){
    //log location marker data to the console
    console.log(marker_data);
    //example output
    {"marker_id": 123, "location_title": "my location marker", "custom_param": "my location"}
}
</pre>
<!--END: sample code-->
                    </div>
                    <div class="hero_col_12">
                        <p>
                            In the above example, you would enter "show_location_marker_data" into the "JavaScript Method" input. The JavaScript method will be passed a JSON object containing 3 parameters when called - 
                            The "marker_id" (INT: unique to hmapspro), the "location_title" (STRING: pulled from "Edit Location Marker") and a "custom_param" (STRING: pulled from "Custom Parameter" in "Edit Location Marker"). 
                        </p>
                        <br>
                        <p>
                        	If the "Location Title" or the "Custom Parameter" are left blank, a <b>null</b> value will be supplied.
                        </p>
                    </div>
                    <div class="hero_col_12">
<!--BEGIN: sample code-->
<span class="hero_red size_12">Example output without "Location Title" or "Custom Parameter"</span>
<pre class="size_12">
//example output
{"marker_id": 123, "location_title": null, "custom_param": null}
</pre>
<!--END: sample code-->
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        
        <div class="hero_section_holder hero_grey size_14"> 
            <div class="hero_col_12">
                <div class="hero_col_6">
                    <label for="css_class">
                        <h2 class="size_14 hero_green">CSS Class</h2>
                        <p class="size_12 hero_grey">Add a CSS class to the map container to assist with advanced customization options</p>
                    </label>
                </div>
                <div class="hero_col_6">
                    <input type="text" data-size="sml" id="css_class" name="css_class" maxlength="50">
                </div>
            </div>
        </div>
        
	</div>
</div>