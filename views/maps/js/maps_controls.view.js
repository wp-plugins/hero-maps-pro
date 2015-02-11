//MAPS_CONTROLS VIEW

//view config
var animation_timer = 300; //show/hide animation time in milliseconds

//view load
jQuery(function(){
	//configure view
	configure_view();
	//bind component change listeners
	bind_component_change_listeners();
	//place controls in correct state
	place_control_state();
	//bind view/hide controls
	bind_view_hide_controls();
	//switch components
	switch_components();
});

//configure view components
function configure_view(){
	//street view
	if(map_object.map_controls.street_view){
		jQuery('#street_view').attr('checked',true);
	}
	//street view position
	jQuery.each(map_config.control_positions, function(key,val){
		jQuery('#street_view_position').append('<option value="'+ key +'" '+ (map_object.map_controls.street_view_position == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//map type
	if(map_object.map_controls.map_type){
		jQuery('#map_type').attr('checked',true);
	}
	//map type position
	jQuery.each(map_config.control_positions, function(key,val){
		jQuery('#map_type_position').append('<option value="'+ key +'" '+ (map_object.map_controls.map_type_position == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//map type style
	jQuery.each(map_config.map_type_control_styles, function(key,val){
		jQuery('#map_type_style').append('<option value="'+ key +'" '+ (map_object.map_controls.map_type_style == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//pan
	if(map_object.map_controls.pan){
		jQuery('#pan').attr('checked',true);
	}
	//pan position
	jQuery.each(map_config.control_positions, function(key,val){
		jQuery('#pan_position').append('<option value="'+ key +'" '+ (map_object.map_controls.pan_position == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//zoom
	if(map_object.map_controls.zoom){
		jQuery('#zoom').attr('checked',true);
	}
	//zoom position
	jQuery.each(map_config.control_positions, function(key,val){
		jQuery('#zoom_position').append('<option value="'+ key +'" '+ (map_object.map_controls.zoom_position == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//zoom style
	jQuery.each(map_config.zoom_control_styles, function(key,val){
		jQuery('#zoom_style').append('<option value="'+ key +'" '+ (map_object.map_controls.zoom_style == key ? 'selected' : '') +'>'+ val +'</option>');
	});
	//scale
	if(map_object.map_controls.scale){
		jQuery('#scale').attr('checked',true);
	}
	//overview
	if(map_object.map_controls.overview){
		jQuery('#overview').attr('checked',true);
	}
	//overview style
	jQuery.each(map_config.overview_control_styles, function(key,val){
		jQuery('#overview_style').append('<option value="'+ val +'" '+ (map_object.map_controls.overview_style == val ? 'selected' : '') +'>'+ key +'</option>');
	});
}

//bind component change listeners
function bind_component_change_listeners(){
	//street view
	jQuery('#street_view').off().on('change', function(){
		//set json object value
		map_object.map_controls.street_view = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//street view position
	jQuery('#street_view_position').off().on('change', function(){
		//set json object value
		map_object.map_controls.street_view_position = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//map type
	jQuery('#map_type').off().on('change', function(){
		//set json object value
		map_object.map_controls.map_type = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//map type position
	jQuery('#map_type_position').off().on('change', function(){
		//set json object value
		map_object.map_controls.map_type_position = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//map type style
	jQuery('#map_type_style').off().on('change', function(){
		//set json object value
		map_object.map_controls.map_type_style = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//pan
	jQuery('#pan').off().on('change', function(){
		//set json object value
		map_object.map_controls.pan = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//pan position
	jQuery('#pan_position').off().on('change', function(){
		//set json object value
		map_object.map_controls.pan_position = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//zoom
	jQuery('#zoom').off().on('change', function(){
		//set json object value
		map_object.map_controls.zoom = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//zoom position
	jQuery('#zoom_position').off().on('change', function(){
		//set json object value
		map_object.map_controls.zoom_position = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//zoom style
	jQuery('#zoom_style').off().on('change', function(){
		//set json object value
		map_object.map_controls.zoom_style = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//scale
	jQuery('#scale').off().on('change', function(){
		//set json object value
		map_object.map_controls.scale = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//overview
	jQuery('#overview').off().on('change', function(){
		//set json object value
		map_object.map_controls.overview = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
	//overview style
	jQuery('#overview_style').off().on('change', function(){
		//set json object value
		map_object.map_controls.overview_style = Boolean(parseInt(jQuery(this).val()));
		//flag save
		flag_save_required('persist_map_object');
		//manage map controls
		manage_map_controls();
	});
}

//place controls in correct state
function place_control_state(){
	jQuery('.hide_switch').each(function(){
		var checked = jQuery(this).is(':checked');
		var container = jQuery(this).closest('.hero_section_holder').find('.hide_container');
		if(checked){
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
	jQuery('.hide_switch').on('change', function(){
		var checked = jQuery(this).is(':checked');
		var container = jQuery(this).closest('.hero_section_holder').find('.hide_container');
		if(checked){
			container.stop().animate({
				'height': container.children('.internal').height() +'px'
			},animation_timer, function(){
				container.css({
					'overflow': 'visible'
				})
			});
		}else{
			container.css({'overflow': 'hidden'}).stop().animate({
				'height': 0
			},animation_timer);
		}
	});
}