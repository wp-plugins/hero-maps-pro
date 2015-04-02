//MAPS_SETTINGS VIEW

//view config
var animation_timer = 300; //show/hide animation time in milliseconds

//view load
jQuery(function(){
	//configure view
	configure_view();
	//place theme control state
	place_theme_control_state();
	//bind component change listeners
	bind_component_change_listeners();
	//place controls in correct state
	place_control_state();
	//switch components
	switch_components();
	//bind button listeners
	bind_button_listeners();
});

//configure view components
function configure_view(){
	//map type
	jQuery.each(map_config.map_types, function(key,val){
		jQuery('#map_type').append('<option value="'+ key +'" '+ (map_object.map_settings.map_type == key ? 'selected' : '') +'>'+ val.title +'</option>');
	});
	//map theme
	jQuery.each(map_config.map_themes, function(key,val){
		jQuery('#map_theme').append('<option value="'+ val +'" '+ (map_object.map_settings.map_theme == val ? 'selected' : '') +'>'+ key +'</option>');
	});
	//autofit
	if(map_object.map_settings.autofit){
		jQuery('#autofit').attr('checked',true);
	}
	//map center
	jQuery('#map_center').val(map_object.map_settings.map_center);
	//rest zoom
	jQuery('#rest_zoom').val(map_object.map_settings.rest_zoom);
	//mouse wheel zoom
	if(map_object.map_settings.mouse_wheel_zoom){
		jQuery('#mouse_wheel_zoom').attr('checked',true);
	}
}

//bind component change listeners
function bind_component_change_listeners(){
	//map type
	jQuery('#map_type').off().on('change', function(){
		//set json object value
		map_object.map_settings.map_type = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//update map type
		eval("google_map.setMapTypeId(google.maps.MapTypeId."+ jQuery(this).val() +");");
		//manage theme control
		manage_theme_control();
	});
	//map theme
	jQuery('#map_theme').off().on('change', function(){
		//set json object value
		map_object.map_settings.map_theme = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map theme
		manage_map_theme();
	});
	//autofit
	bind_view_hide_controls();
	//map center
	jQuery('#map_center').off().on('change', function(){
		//set json object value
		map_object.map_settings.map_center = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//rest zoom
	jQuery('#rest_zoom').off().on('change', function(){
		//set json object value
		map_object.map_settings.rest_zoom = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//mouse wheel zoom
	jQuery('#mouse_wheel_zoom').off().on('change', function(){
		//set json object value
		map_object.map_settings.mouse_wheel_zoom = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
	});
}

//place theme control state
function place_theme_control_state(){
	//lookup in map_config
	jQuery.each(map_config.map_types, function(key,val){
		if(key == map_object.map_settings.map_type){
			if(val.show_theme){
				var new_height = jQuery('.map_theme_container').children('.internal').height();
				jQuery('.map_theme_container').css({
					'height': new_height +'px',
					'overflow': 'visible'
				});
			}
			return false;
		}
	});
}

//show theme control
function manage_theme_control(){
	//lookup in map_config
	var show;
	jQuery.each(map_config.map_types, function(key,val){
		if(key == map_object.map_settings.map_type){
			show = val.show_theme;
			return false;
		}
	});
	//get container
	var container = jQuery('.map_theme_container');
	//manage display
	if(show){
		container.stop().animate({
			'height': container.children('.internal').height() +'px'
		},animation_timer, function(){
			container.css({
				'overflow': 'visible'
			})
		});
	}else{
		var container = jQuery('.map_theme_container');
		container.css({'overflow': 'hidden'}).stop().animate({
			'height': 0
		},animation_timer);
	}
}

//place controls in correct state
function place_control_state(){
	jQuery('.show_switch').each(function(){
		var checked = jQuery(this).is(':checked');
		var container = jQuery(this).closest('.hero_section_holder').find('.hide_container');
		if(!checked){
			var new_height = container.children('.internal').height();
			container.css({
				'height': new_height +'px',
				'overflow': 'visible'
			});
		}
	});
}

//bind view/hide controls
function bind_view_hide_controls(){
	jQuery('.show_switch').on('change', function(){
		var checked = jQuery(this).is(':checked');
		var container = jQuery(this).closest('.hero_section_holder').find('.hide_container');
		if(!checked){
			container.stop().animate({
				'height': container.children('.internal').height() +'px'
			},animation_timer, function(){
				container.css({
					'overflow': 'visible'
				})
			});
			//update map object
			map_object.map_settings.autofit = false;
		}else{
			container.css({'overflow': 'hidden'}).stop().animate({
				'height': 0
			},animation_timer);
			//update map object
			map_object.map_settings.autofit = true;
			jQuery('#map_center').val('0,0');
			map_object.map_settings.map_center = '0,0';
		}
		//flag save
		flag_save_required('persist_map_object');
	});
}

//bind button listeners
function bind_button_listeners(){
	jQuery('#get_map_center_zoom_btn').off().on('click', function(){
		//get map center
		var latlon = google_map.getCenter();
		var lat = latlon.lat();
		var lon = latlon.lng();
		map_object.map_settings.map_center = lat +','+ lon;
		jQuery('#map_center').val(map_object.map_settings.map_center);
		//get map zoom
		var cur_zoom = google_map.getZoom();
		map_object.map_settings.rest_zoom = cur_zoom;
		jQuery('#rest_zoom').val(map_object.map_settings.rest_zoom);
		//flag save
		flag_save_required('persist_map_object');
	});
}