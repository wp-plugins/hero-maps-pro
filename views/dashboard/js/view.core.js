//DASHBOARD VIEW CORE

//load
jQuery(function(){
	//load maps into sidebar
	get_all_maps('populate_maps_dashboard_table');
	//populate dashboard containers
	populate_dashboard_containers();
});

//populate dashboard containers
function populate_dashboard_containers(){
	jQuery('#plugin_version').html(plugin_version);
	jQuery('#plugin_last_update').html(plugin_last_updated);
	jQuery('#plugin_release_date').html(plugin_first_release);
}

//populate maps table
function populate_maps_dashboard_table(maps){
	var map_data = extract_json_object(maps);
	jQuery('#dashboard_map_holder').empty();
	if(map_data.length > 0){
		jQuery.each(map_data, function(key,val){
			var row_html = '<div class="hero_col_12" id="dashboard_item_'+ val.map_id +'">';
					row_html += '<div class="hero_col_4" style="cursor:pointer;" data-json="'+ encodeURIComponent(JSON.stringify({"map_id": val.map_id})) +'" onclick="load_sidebar_dropdown_view(jQuery(this),\'maps_dropdown\',\'load_map_data\');"><span>'+ val.name +'</span></div>';
					row_html += '<div class="hero_col_5"><span><input class="hero_ctc" style="width:100%;" onclick="jQuery(this).select();" type="text" value="[hmapspro id='+ val.map_id +']" readonly></span></div>';
					row_html += '<div class="hero_col_3">';
						row_html += '<div class="hero_edits rounded_20">';
							row_html += '<div class="hero_edit_item" data-json="'+ encodeURIComponent(JSON.stringify({"map_id": val.map_id})) +'" onclick="load_sidebar_dropdown_view(jQuery(this),\'maps_dropdown\',\'load_map_data\');" style="background-image:url('+ plugin_url +'/assets/images/admin/edit_icon.png)"></div>';
							row_html += '<div class="hero_edit_item" onclick="delete_map_request('+ val.map_id +');" style="background-image:url('+ plugin_url +'/assets/images/admin/delete_icon.png)"></div>';
						row_html += '</div>';
					row_html += '</div>';
				row_html += '</div>';
			jQuery('#dashboard_map_holder').append(row_html);
		});
	}else{
		var row_html = '<div class="hero_col_12" id="no_map_data_message"><span>To add your first map, click on "Maps" in the sidebar and select "Add New".</span></div>';
		jQuery('#dashboard_map_holder').append(row_html);
	}
}

//delete map request
function delete_map_request(map_id){
	if(window.confirm('Are you sure you want to delete this map?')){
		//delete map
		delete_map(map_id);
	}
}

//delete map
function delete_map(map_id){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_delete_map',
			'map_id': map_id
		},
		dataType: "json"
	}).done(function(response){
		//remove item from sidebar
		jQuery('#sub_item_row_'+ map_id).fadeOut(300, function(){
			jQuery(this).remove();
			//resize sidebar menu holder
			if(jQuery('#maps_dropdown').data('visible') == 'visible'){
				jQuery('#maps_dropdown').stop().animate({
					'height': (jQuery('.hero_sidebar_item').height() + jQuery('#maps_dropdown .hero_sub').height()) +'px'
				},500);
			}
		});
		//remove from table
		get_all_maps('populate_maps_dashboard_table');
	});
}