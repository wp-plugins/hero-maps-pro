//MAPS VIEW CORE

//core config
var map_config = {
	"map_types": {
		"ROADMAP": {
			"title": "Roadmap",
			"show_theme": true
		},
		"SATELLITE": {
			"title": "Satellite",
			"show_theme": false
		},
		"HYBRID": {
			"title": "Hybrid",
			"show_theme": false
		},
		"TERRAIN": {
			"title": "Terrain",
			"show_theme": true
		}
	},
	"control_positions": {
		"DEFAULT": "Default",
		"TOP_CENTER": "Top Center",
		"TOP_LEFT": "Top Left",
		"TOP_RIGHT": "Top Right",
		"BOTTOM_CENTER": "Bottom Center",
		"BOTTOM_LEFT": "Bottom Left",
		"BOTTOM_RIGHT": "Bottom Right",
		"LEFT_CENTER": "Left Center",
		"RIGHT_CENTER": "Right Center"
	},
	"map_type_control_styles": {
		"DEFAULT": "Default",
		"HORIZONTAL_BAR": "Horizontal Bar",
		"DROPDOWN_MENU": "Dropdown Menu"
	},
	"zoom_control_styles": {
		"DEFAULT": "Default",
		"SMALL": "Small",
		"LARGE": "Large"
	},
	"overview_control_styles": {
		"Open": 1,
		"Closed": 0
	},
	"marker_animation_types": {
		"DROP": "Drop",
		"BOUNCE": "Bounce",
		"DEFAULT": "Default"
	},
	"map_themes": {
		"Color": "",
		"Greyscale": "#928e89"
	}
};

//core globals
var core_params;
var google_map;
var map_overlay;
var map_markers_object;
var map_markers_object_load_timer;
var map_animation_time = 800;
var map_object;

//view load (callback)
function load_map_data(json){
	//get map id from json object
	map_id = json.map_id;
	//highlight active
	setTimeout(function(){
		jQuery('.hero_sub #sub_item_row_'+ map_id).addClass('active_sidebar_elem');
	},400);
	//load map
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_get_map_data',
			'map_id': map_id
		},
		dataType: "json"
	}).done(function(map_data){
		map_object = map_data;
		delete map_object.map_markers.default;
		//initialise maps view
		initialise_maps_view();
		//unlock core view
		unlock_core_view_reload();
	});
}

//initialise maps view
function initialise_maps_view(){
	//manual view load
	manual_load_view('maps_dropdown');
	//load Google Maps and initialise map
	load_gmap_script();
	//get markers
	get_map_markers();
	//bind marker edit panel switch(s)
	bind_marker_edit_panel_switch();
}

//get markers
function get_map_markers(){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_get_markers'
		},
		dataType: "json"
	}).done(function(markers){
		//set view global
		map_markers_object = markers;
	});
}

//load Google Maps async
function load_gmap_script(){
	//check if API already loaded
	if(typeof google === 'object' && typeof google.maps === 'object'){
		initialise_map();
	}else{
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=initialise_map";
		document.body.appendChild(script);
	}
}

//initialise map
function initialise_map(){
	//initialise map
	defaultLatLon = eval("new google.maps.LatLng("+ map_object.map_settings.map_center +");");
	var map_options = {
		zoom: map_object.map_settings.rest_zoom,
		scrollwheel: false,
		center: defaultLatLon,
		disableDefaultUI: true,
		mapTypeId: eval("google.maps.MapTypeId."+ map_object.map_settings.map_type +"")
	};
	//load map
	google_map = new google.maps.Map(document.getElementById("hero_map_main"),map_options);
	//add map overlay for pixel point detection
	map_overlay = new google.maps.OverlayView();
	map_overlay.draw = function(){};
	map_overlay.setMap(google_map);
	//location search
	var input = document.getElementById('location_search');
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.bindTo('bounds', google_map);
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		var place = autocomplete.getPlace();
		if(place.geometry.viewport){
			google_map.fitBounds(place.geometry.viewport);
		}else{
			google_map.setCenter(place.geometry.location);
			google_map.setZoom(17);
		}
	});
	var center;
	function calculateCenter(){
		center = google_map.getCenter();
	}
	google.maps.event.addDomListener(google_map, 'idle', function(){
		calculateCenter();
	});
	google.maps.event.addDomListener(window, 'resize', function(){
		google_map.setCenter(center);
	});
	//manage map controls
	manage_map_controls();
	//manage map theme
	manage_map_theme();
	//place existing markers
	place_existing_map_markers();
}

//manage map controls
function manage_map_controls(){
	var map_options = {};
	//street view
	if(map_object.map_controls.street_view){
		map_options.streetViewControl = true;
		eval("map_options.streetViewControlOptions = {position: google.maps.ControlPosition."+ map_object.map_controls.street_view_position +"};");
	}else{
		map_options.streetViewControl = false;
		map_options.streetViewControlOptions = {};
	}
	//map type
	if(map_object.map_controls.map_type){
		map_options.mapTypeControl = true;
		eval("map_options.mapTypeControlOptions = {position: google.maps.ControlPosition."+ map_object.map_controls.map_type_position +", style: google.maps.MapTypeControlStyle."+ map_object.map_controls.map_type_style +"};");
	}else{
		map_options.mapTypeControl = false;
		map_options.mapTypeControlOptions = {};
	}
	//pan
	if(map_object.map_controls.pan){
		map_options.panControl = true;
		eval("map_options.panControlOptions = {position: google.maps.ControlPosition."+ map_object.map_controls.pan_position +"};");
	}else{
		map_options.panControl = false;
		map_options.panControlOptions = {};
	}
	//zoom
	if(map_object.map_controls.zoom){
		map_options.zoomControl = true;
		eval("map_options.zoomControlOptions = {position: google.maps.ControlPosition."+ map_object.map_controls.zoom_position +", style: google.maps.ZoomControlStyle."+ map_object.map_controls.zoom_style +"};");
	}else{
		map_options.zoomControl = false;
		map_options.zoomControlOptions = {};
	}
	//scale
	if(map_object.map_controls.scale){
		map_options.scaleControl = true;
	}else{
		map_options.scaleControl = false;
	}
	//overview
	if(map_object.map_controls.overview){
		map_options.overviewMapControl = true;
		eval("map_options.overviewMapControlOptions = {opened: "+ map_object.map_controls.overview_style +"};");
	}else{
		map_options.overviewMapControl = false;
		map_options.overviewMapControlOptions = {};
	}
	//update map options
	google_map.setOptions(map_options);
	//custom parameter
	if(map_object.map_developers.javascript_callback){
		jQuery('#custom_param_container').css('display','block');
	}
		
}

//manage map theme
function manage_map_theme(){
	if(map_object.map_settings.map_theme == ''){
		var theme = null;
	}else{
		//get colour
		var colour = map_object.map_settings.map_theme;
		var theme = [
			{
				featureType: "all",
				stylers:[
					{saturation: -100}
				]
			},
			{
				featureType: "water",
				elementType: "geometry.fill",
				stylers:[
					{color: colour},
					{saturation: -30},
					{lightness: 50}
				]
			},
			{
				featureType: "landscape",
				
				stylers:[
					{saturation: -100},
					{lightness: 20}
				]
			},
			{
				featureType: "road",
				elementType: "geometry.stroke",
				stylers:[
					{color: colour},
					{saturation: -30},
					{lightness: 0}
				]
			},
			{
				featureType: "road",
				elementType: "geometry.fill",
				stylers:[
					{color: colour},
					{saturation: -30},
					{lightness: 60}
				]
			}
		];
	}
    google_map.setOptions({
        styles: theme
    });
}

//place existing markers
function place_existing_map_markers(){
	//get marker data
	if(typeof map_markers_object === 'undefined'){
		clearTimeout(map_markers_object_load_timer);
		map_markers_object_load_timer = setTimeout("place_existing_map_markers();",100);
	}else{
		clearTimeout(map_markers_object_load_timer);
		jQuery.each(map_object.map_markers, function(key,val){
			//get marker data
			marker_data = get_marker_data_from_object(val.marker_id);
			//place marker on map
			var width = marker_data.width;
			var height = marker_data.height;
			var top_offset = marker_data.top_offset;
			var left_offset = marker_data.left_offset;
			var marker_id = val.marker_id;
			var icon_object = new google.maps.MarkerImage('data:image/png;base64,'+ marker_data.img_binary, new google.maps.Size(width, height), new google.maps.Point(0, 0), new google.maps.Point(left_offset, top_offset));
			var latlng_object = val.latlng.split(',');
			var latlng = new google.maps.LatLng(latlng_object[0] , latlng_object[1]);
			var map_marker = new google.maps.Marker({
				position: latlng,
				draggable: true,
				raiseOnDrag: true,
				icon: icon_object,
				map: google_map
			});
			//bind marker listeners
			bind_marker_listeners(map_marker,key);
			//update gmp
			map_object.map_markers[key].gmp = map_marker;
		});
	}
}

//bind marker listeners
function bind_marker_listeners(map_marker,icon_id){
	//marker click
	google.maps.event.addListener(map_marker, "click", function(){
		//populate edit panel
		populate_edit_panel(map_object.map_markers[icon_id]);
		//show edit panel
		show_marker_edit_panel();
	});
	//marker drag start
	google.maps.event.addListener(map_marker, "dragstart", function(){
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker drag end
	google.maps.event.addListener(map_marker, "dragend", function(){
		//get marker position
		var latlng = map_marker.getPosition();
		var latlng_string = latlng.lat() +','+ latlng.lng();
		//update marker in map_object
		map_object.map_markers[icon_id].latlng = latlng_string;
		//trigger coords changed
		jQuery('#location_marker_coords_change_listener').trigger('change');
	});
}

//get marker data from map_markers_object
function get_marker_data_from_object(marker_id){
	//loop object
	var marker_data;
	jQuery.each(map_markers_object.categories, function(key,val){
		jQuery.each(val.links, function(key,val){
			jQuery.each(val.markers, function(key,val){
				if(val.marker_id == marker_id){
					marker_data = val;
					return false;
				}
			});
		});
	});
	return marker_data;
}

//persist map object
function persist_map_object(){
	//close map marker object before save
	jQuery('#done_location_marker_btn').trigger('click');
	var persist_data = jQuery.extend(true, {}, map_object);
	jQuery.each(persist_data.map_markers, function(key,val){
		val.gmp = null; //clear map pointers
	});
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_update_map_object',
			'map_object': persist_data
		},
		dataType: "json"
	}).done(function(marker_data){
		//remove all markers from the map
		jQuery.each(map_object.map_markers, function(key,val){
			//delete the marker object from the map
			if(val.gmp != null){
				val.gmp.setMap(null);
			}
		});
		//update the map markers object with DB data
		map_object.map_markers = marker_data;
		delete map_object.map_markers.default;
		//place updated markers on the map
		place_existing_map_markers();	
		//update name in sidebar
		jQuery('#sub_item_row_'+ map_object.map_setup.map_id).html(map_object.map_setup.map_name);
		//show success message
		show_message('success', 'Update Success', 'The changes to the map have been saved.');
		//unlock core view
		unlock_core_view_reload();
		//update map name in header text
		set_current_header_label('Currently Editing Map', map_object.map_setup.map_name);
	});
}

//populate edit panel
function populate_edit_panel(marker_data){
	//marker image
	jQuery('#edit_location_marker_img').attr('src','data:image/png;base64,'+ get_marker_data_from_object(marker_data.marker_id).img_binary);
	//location title
	jQuery('#location_title').val(marker_data.title);
	//location coordinates
	jQuery('#location_coordinates').val(marker_data.latlng);
	//info window show
	if(marker_data.info_window_show){
		jQuery('#info_window_show').removeAttr('checked').trigger('click');
		//info window content
		jQuery('#info_window_content').val(marker_data.info_window_content);
	}else{
		jQuery('#info_window_show').attr('checked','checked').trigger('click');
		jQuery('#info_window_content').val('');
	}
	//link show
	if(marker_data.link_show){
		jQuery('#link_show').removeAttr('checked').trigger('click');
		//link title
		jQuery('#link_title').val(marker_data.link_title);
		//link
		jQuery('#link').val(marker_data.link);
	}else{
		jQuery('#link_show').attr('checked','checked').trigger('click');
		jQuery('#link_title').val('');
		jQuery('#link').val('');
	}
	//link colour
	jQuery('#link_colour').val(marker_data.link_colour).trigger('change');
	//link target	
	jQuery('#link_target option').each(function(key,val){
		if(jQuery(this).val() == marker_data.link_target){
			jQuery(this).attr('selected',true);
		}else{
			jQuery(this).removeAttr('selected');
		}
	});
	//custom param
	jQuery('#custom_param').val(marker_data.custom_param);
	update_select_component(jQuery('#link_target'));
	//bind location marker edit listener
	bind_location_marker_edit_listeners(marker_data);
}

//bind location marker edit listener
function bind_location_marker_edit_listeners(marker_data){
	jQuery('#location_marker_coords_change_listener').off().on('change', function(){
		jQuery('#location_coordinates').val(marker_data.latlng);
	});
	jQuery('#del_location_marker_btn').off().on('click', function(){
		if(window.confirm('Are you sure you want to delete this marker?')){
			//mark marker deleted
			remove_location_marker_from_object(marker_data);
			//hide panel
			hide_marker_edit_panel();
			//flag save
			flag_save_required('persist_map_object');
		}
	});
	jQuery('#done_location_marker_btn').off().on('click', function(){
		//get link target
		var link_target;
		jQuery('#link_target option').each(function(key,val){
			if(jQuery(this).is(':selected')){
				link_target = jQuery(this).val();
				return false;
			}
		});
		//check if data has changed
		if(marker_data.title != jQuery('#location_title').val() || marker_data.info_window_show != Boolean(jQuery('#info_window_show').is(':checked')) || marker_data.info_window_content != jQuery('#info_window_content').val() || marker_data.link_show != Boolean(jQuery('#link_show').is(':checked')) || marker_data.link_title != jQuery('#link_title').val() || marker_data.link != jQuery('#link').val() || marker_data.link_colour != jQuery('#link_colour').val() || marker_data.link_target != link_target || marker_data.custom_param != jQuery('#custom_param').val()){
			//flag save
			flag_save_required('persist_map_object');
		}
		//update marker object
		marker_data.title = jQuery('#location_title').val();
		marker_data.info_window_show = Boolean(jQuery('#info_window_show').is(':checked'));
		marker_data.info_window_content = jQuery('#info_window_content').val();
		marker_data.link_show = Boolean(jQuery('#link_show').is(':checked'));
		marker_data.link_title = jQuery('#link_title').val();
		marker_data.link = jQuery('#link').val();
		marker_data.link_colour = jQuery('#link_colour').val();
		marker_data.link_target = link_target;
		marker_data.custom_param = jQuery('#custom_param').val();
		//hide panel
		hide_marker_edit_panel();
	});
}

//mark marker deleted
function remove_location_marker_from_object(marker_data){
	//remove marker from map
	marker_data.gmp.setMap(null);
	//mark deleted
	marker_data.deleted = true;
	//clear gmp data
	marker_data.gmp = null;
}

//bind marker edit panel switch(s)
function bind_marker_edit_panel_switch(){
	//info window
	jQuery('#info_window_show').on('click', function(){
		if(jQuery(this).is(':checked')){
			jQuery('#info_window_content').removeAttr('readonly');
		}else{
			jQuery('#info_window_content').attr('readonly',true).val('');
		}
	});
	//link
	jQuery('#link_show').on('click', function(){
		if(jQuery(this).is(':checked')){
			jQuery('#link_title, #link').removeAttr('readonly');
		}else{
			jQuery('#link_title, #link').attr('readonly',true).val('');
		}
	});
}

//hide marker edit panel
function hide_marker_edit_panel(){
	jQuery('.hero_map_marker_details').stop().animate({
		'top': '-210px'
	},400);
}

//show marker edit panel
function show_marker_edit_panel(){
	jQuery('.hero_map_marker_details').stop().animate({
		'top': '40px'
	},400);
}