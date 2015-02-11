//MAPS_DEVELOPERS VIEW

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
	//javascript callback
	if(map_object.map_developers.javascript_callback){
		jQuery('#javascript_callback').attr('checked',true);
	}
	//callback method
	jQuery('#callback_method').val(map_object.map_developers.callback_method);
	//css class
	jQuery('#css_class').val(map_object.map_developers.css_class);
}

//bind component change listeners
function bind_component_change_listeners(){
	//javascript callback
	jQuery('#javascript_callback').off().on('change', function(){
		//set json object value
		map_object.map_developers.javascript_callback = jQuery(this).is(':checked') ? true : false;
		//flag save
		flag_save_required('persist_map_object');
	});
	//callback method
	jQuery('#callback_method').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_developers.callback_method = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
	});
	//css class
	jQuery('#css_class').off().on('change keyup paste', function(){
		//set json object value
		map_object.map_developers.css_class = jQuery(this).val();
		//flag save
		flag_save_required('persist_map_object');
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
			jQuery('#custom_param_container').css('visibility','visible');
			container.stop().animate({
				'height': container.children('.internal').height() +'px'
			},animation_timer, function(){
				container.css({
					'overflow': 'visible'
				})
			});
		}else{
			jQuery('#custom_param_container').css('visibility','hidden');
			container.css({'overflow': 'hidden'}).stop().animate({
				'height': 0
			},animation_timer);
		}
	});
}