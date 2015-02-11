//MAPS_SETUP VIEW

//view config
var default_map_width = 1000; //default map width in px

//view load
jQuery(function(){
	//set view header
	set_current_header_label('Currently Editing Map', map_object.map_setup.map_name);
	//configure view
	configure_view();
	//bind component change listeners
	bind_component_change_listeners();
	//bind responsive switch listener
	bind_responsive_switch_listener();
	//switch components
	switch_components();
});

//configure view components
function configure_view(){
	//map name
	jQuery('#map_name').val(map_object.map_setup.map_name);
	//shortcode
	jQuery('#shortcode').val('['+ plugin_name +' id='+ map_object.map_setup.map_id +']');
	//fixed width
	if(!map_object.map_setup.responsive){
		jQuery('#fixed_width').attr('checked',true);
	}
	//responsive
	if(map_object.map_setup.responsive){
		jQuery('#responsive').attr('checked',true);
	}
	//manage responsive switch
	manage_responsive_switch();
	//map width
	jQuery('#map_width').val(map_object.map_setup.map_width);
	//map height
	jQuery('#map_height').val(map_object.map_setup.map_height);
}

//bind component change listeners
function bind_component_change_listeners(){
	//map name
	jQuery('#map_name').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_setup.map_name = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
	});
	//map width
	jQuery('#map_width').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_setup.map_width = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
	//map height
	jQuery('#map_height').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_setup.map_height = parseInt(jQuery(this).val());
		//flag save
		flag_save_required('persist_map_object');
	});
}

//bind responsive switch listener
function bind_responsive_switch_listener(){
	jQuery('.responsive_switch').off().on('change', function(){
		//manage responsive switch
		manage_responsive_switch();
	});
}

//manage responsive switch
function manage_responsive_switch(){
	if(jQuery('#responsive').is(':checked')){
		//set map object responsive
		map_object.map_setup.responsive = true;
		//set map width to percentage (100%)
		jQuery('#map_width').removeClass('hero_px').addClass('hero_perc').val(100).attr('readonly',true).trigger('change');
	}else{
		//set map object responsive
		map_object.map_setup.responsive = false;
		//set map width to px (default to 1000px)
		jQuery('#map_width').removeClass('hero_perc').addClass('hero_px').val(default_map_width).removeAttr('readonly').trigger('change');
	}
}