//MAPS_ADVANCED VIEW

//view load
jQuery(function(){
	//configure view
	configure_view();
	//bind component change listeners
	bind_component_change_listeners();
	//switch components
	switch_components();
	//bind button listeners
	bind_button_listeners();
});

//configure view components
function configure_view(){
	//marker drop delay
	jQuery('#marker_drop_delay').val(map_object.map_advanced.marker_drop_delay);
	//marker animation
	jQuery.each(map_config.marker_animation_types, function(key,val){
		jQuery('#marker_animation').append('<option value="'+ key +'" '+ (map_object.map_advanced.marker_animation == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//marker animation timer
	jQuery('#marker_animation_timer').val(map_object.map_advanced.marker_animation_timer);
	//marker tooltip
	if(map_object.map_advanced.marker_tooltip){
		jQuery('#marker_tooltip').attr('checked',true);
	}
	//map load zoom
	jQuery('#map_load_zoom').val(map_object.map_advanced.map_load_zoom);
	//marker click zoom
	jQuery('#marker_click_zoom').val(map_object.map_advanced.marker_click_zoom);
}

//bind component change listeners
function bind_component_change_listeners(){
	//marker drop delay
	jQuery('#marker_drop_delay').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_advanced.marker_drop_delay = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker animation
	jQuery('#marker_animation').off().on('change', function(){
		//set json object value
		map_object.map_advanced.marker_animation = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker animation timer
	jQuery('#marker_animation_timer').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_advanced.marker_animation_timer = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker tooltip
	jQuery('#marker_tooltip').off().on('change', function(){
		//set json object value
		map_object.map_advanced.marker_tooltip = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
	});
	//map load zoom
	jQuery('#map_load_zoom').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_advanced.map_load_zoom = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker click zoom
	jQuery('#marker_click_zoom').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_advanced.marker_click_zoom = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});	
}

//bind button listeners
function bind_button_listeners(){
	//map load zoom
	jQuery('#get_load_zoom_btn').off().on('click', function(){
		//get map zoom
		var cur_zoom = google_map.getZoom();
		map_object.map_advanced.map_load_zoom = cur_zoom;
		jQuery('#map_load_zoom').val(map_object.map_advanced.map_load_zoom);
		//flag save
		flag_save_required('persist_map_object');
	});
	//marker click zoom
	jQuery('#get_marker_click_zoom_btn').off().on('click', function(){
		//get map zoom
		var cur_zoom = google_map.getZoom();
		map_object.map_advanced.marker_click_zoom = cur_zoom;
		jQuery('#marker_click_zoom').val(map_object.map_advanced.marker_click_zoom);
		//flag save
		flag_save_required('persist_map_object');
	});
}