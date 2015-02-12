<script type="text/javascript" src="<?php echo $_GET['vp']; ?>js/maps_setup.view.js"></script>
<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Map Setup<br />
            <strong class="size_11 hero_grey">Change the setup options for your map</strong>
        </h1>
        <div class="hero_section_holder hero_grey size_14"> 
        	<div class="hero_col_12">
                <div class="hero_col_4">
                    <h2 class="size_18 hero_red weight_600">Map Name</h2>
                    <input type="text" data-size="lrg" id="map_name" name="map_name" maxlength="15">
                </div>
                <div class="hero_col_4">
                    <h2 class="size_18 hero_red weight_600">Shortcode</h2>
                    <input type="text" data-size="lrg" id="shortcode" name="shortcode" onclick="jQuery(this).select();" readonly>
                </div>
            </div>
        </div>
        <div class="hero_section_holder hero_grey size_14"> 
        	<div class="hero_col_12">
                <div class="hero_col_12">
                    <h2 class="size_18 hero_red weight_600">Dimensions</h2>
                    <p class="size_12 hero_grey">A responsive map will automatically adjust its width</p>
                </div>
                <div class="hero_col_10">
                    <div class="hero_col_3 h_cust_component comp_right_sep">
                        <div class="hero_col_8">
                            <label for="fixed_width">
                                <h2 class="size_14 hero_green">Fixed Width</h2>
                            </label>
                        </div>
                        <div class="hero_col_4">
                            <input type="radio" data-size="sml" id="fixed_width" class="responsive_switch" name="responsive_switch" value="1">
                        </div>
                    </div>
                    <div class="hero_col_3 h_cust_component">
                        <div class="hero_col_8">
                            <label for="responsive">
                                <h2 class="size_14 hero_green">Responsive</h2>
                            </label>
                        </div>
                        <div class="hero_col_4">
                            <input type="radio" data-size="sml" id="responsive" class="responsive_switch" name="responsive_switch" value="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero_col_12">
                <div class="hero_col_4">
                    <label for="map_width">Map Width</label>
                    <input type="text" data-size="lrg" data-hero_type="px" id="map_width" name="map_width" maxlength="4">
                </div>
                <div class="hero_col_4">
                    <label for="map_height">Map Height</label>
                    <input type="text" data-size="lrg" data-hero_type="px" id="map_height" name="map_height" maxlength="4">
                </div>
            </div>
        </div>
	</div>
</div>