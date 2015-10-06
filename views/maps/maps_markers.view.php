<script type="text/javascript" src="<?php echo $_GET['vp']; ?>js/maps_markers.view.js"></script>
<div class="marker_selection hero_col_12">
    <div class="hero_col_2 marker_pack">
    	<div class="hero_grey size_14">
            <div class="selection_holder hero_col_12">
                <p class="hero_green size_14">Marker Pack</p>
                <div class="hero_col_12">
                    <select data-size="med" id="map_marker_category" name="map_marker_category">
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="hero_col_9 marker_selector">
        <div class="hero_grey size_14">
            <div class="hero_col_12 colour_choice">
                <div class="hero_col_1"><p class="hero_green size_14">Color</p></div>
                <div class="hero_col_11">
                	<div class="hero_preset_holder"></div>
                </div>
            </div>
            <div class="hero_col_12 drag_copy">
	            <div class="hero_col_1">&nbsp;</div>
	            <div class="hero_col_11"><p class="size_12">Drag and drop one of the markers below onto the map</p></div>
            </div>
            <div class="hero_col_12 marker_choice">
                <div class="hero_col_1"><p class="hero_green size_14">Markers</p></div>
                <div class="hero_col_11">
                	<div id="marker_display_holder"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Map Markers<br />
            <strong class="size_11 hero_grey">Adding location markers to the map</strong>
        </h1>
        <div class="hero_section_holder hero_grey">
        	<div class="hero_col_12">
            	<p>
                	To add a new location marker to the map, select a marker pack, pick a marker colour and drag and drop your chosen marker onto the map. Simply click on a 
                    placed marker to edit the marker's location information or remove the marker. A marker's location can be fine-tuned by zooming into the map and dragging the 
                    marker to a new location.
                </p>
            </div>
        </div>
	</div>
</div>