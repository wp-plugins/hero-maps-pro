//SIDEBAR PRE_POPULATION

//load
/*
	note: this method is required by the core framework and is called when the plugin is initialised.
*/
var first_load = true;
function prepopulate_sidebar_elements(){
	//load maps into sidebar
	get_all_maps('load_maps_into_sidebar');
	//bind buttons
	bind_sidebar_btn_listeners()
}

//load maps into sidebar
function load_maps_into_sidebar(maps){
	var map_data = extract_json_object(maps);
	jQuery('.maps_submenu_holder li').remove();
	if(map_data){
		jQuery.each(map_data, function(key,val){
			add_sidebar_element('maps_dropdown', val.map_id, val.name, {"map_id": val.map_id}, 'load_map_data');
		});
		if(!first_load){
			var sidebar_item_height = jQuery('.hero_sidebar_item').height();
			var sub_item_height = jQuery('.hero_sub').height();
			jQuery('#maps_dropdown').stop().animate({
				'height': (sidebar_item_height + sub_item_height) +'px'
			},300);
		}
	}
	first_load = false;
}

//get maps
function get_all_maps(callback){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_get_all_maps'
		},
		dataType: "json"
	}).done(function(maps){
		eval(""+ callback +"('"+ encodeURIComponent(JSON.stringify(maps)) +"');");
	});
}

//generate new map
function generate_new_map(map_name){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_generate_new_map',
			'map_name': map_name
		},
		dataType: "json"
	}).done(function(map_id){
		//load maps into sidebar
		get_all_maps('load_maps_into_sidebar');
		show_message('success', 'Map Added', 'Your new map has been successfully created.');
		manual_load_core_view('maps_dropdown', {"map_id": map_id}, 'load_map_data');
		animate_add_closed(map_id);
		add_new_active = false;
	});
}

//bind sidebar button listeners
function bind_sidebar_btn_listeners(){
	jQuery('.hero_sidebar').on('click','#sidebar_add_map_btn', function(){
		//show map add
		show_map_add();
	});
}

//show map add
var add_new_active = false;
function show_map_add(){
	if(!add_new_active){
		var add_html = '<div class="hero_add_new">';
				add_html += '<div class="hero_new_wrap">';
					add_html += '<form id="insert_map_form">';
						add_html += '<input type="text" data-size="lrg" placeholder="Map Name" name="new_map_name" id="new_map_name">';
						add_html += '<div class="hero_sidebar_button size_11 rounded_3 hero_white" id="add_new_map_btn">Add</div>';
					add_html += '</form>';
				add_html += '</div>';
			add_html += '</div>';
		jQuery(add_html).insertAfter(jQuery('#maps_dropdown'));
		jQuery('#new_map_name').focus();
		add_new_active = true;
		animate_add_open();
	}
}

//bind menu add listener
var block_map_add = false;
function bind_menu_add_listener(){
	jQuery('#insert_map_form').off().on('submit', function(event){
		//prevent default
		event.preventDefault();
		if(!block_map_add){
			block_map_add = true;
			//remove error
			jQuery('#new_map_name').removeClass('has-error');
			//get map name
			var map_name = jQuery('#new_map_name').val();
			//check map name
			if(map_name.length > 1){
				generate_new_map(map_name);
			}else{
				jQuery('#new_map_name').addClass('has-error');
				block_map_add = false;
			}
		}
	});
	jQuery('#add_new_map_btn').off().on('click', function(){
		jQuery('#insert_map_form').submit();
	});
}

//animate open add new
function animate_add_open(){
	jQuery('.hero_add_new').animate({
		'height': 50 +'px'		
	},300, function(){
		bind_menu_add_listener();
	});
}

//animate add closed
function animate_add_closed(){
	jQuery('.hero_add_new').animate({
		'height': 0 +'px'	
	},300,function(){
		jQuery('.hero_add_new').remove();
		block_map_add = false;
	});			
}