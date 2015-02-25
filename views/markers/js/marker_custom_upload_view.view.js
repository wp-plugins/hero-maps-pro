//MARKER_CUSTOM_UPLOAD_VIEW VIEW

//view globals
var custom_markers;
var marker_top_offset = 0;
var marker_left_offset = 0;

//view load
jQuery(function(){
	//load iframe for pack upload
	load_iframe();
	//get cutom markers
	get_custom_markers();
});

//load iframe for pack upload
function load_iframe(){
	load_secure_iframe('inc/custom_marker_uploader.php', 50, '.custom_marker_upload_holder');
}

//detect processing completion
function process_complete(){
	//show success message
	window.parent.show_message("success", "Upload Success", "The selected marker has been successfully installed.");
	//reload iframe (new security token)
	load_iframe();
	//update custom marker table
	get_custom_markers();
}

//get custom markers
function get_custom_markers(){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_get_custom_markers'
		},
		dataType: "json"
	}).done(function(response){
		//empty table
		jQuery('#custom_marker_table_holder').empty();
		if(typeof response !== 'boolean'){
			//set custom markers value
			custom_markers = response;
			//create table
			var table_html = '<div class="hero_list_holder hero_grey size_11">';
				table_html += '<div class="hero_col_12 hero_list_heading hero_white">';
					table_html += '<div class="hero_col_3"><span>Marker</span></div>';
					table_html += '<div class="hero_col_3" style="padding-right:0 !important;"><span>Dimensions</span></div>';
					table_html += '<div class="hero_col_4"><span>Offset</span></div>';
				table_html += '</div>';
				table_html += '<div id="custom_marker_rows">';
				table_html += '</div>';
			table_html += '</div>';
			jQuery('#custom_marker_table_holder').append(table_html);
			//append rows
			jQuery.each(response, function(key,val){
				if(val.used){
					var delete_html = '<div class="hero_edit_item" data-tooltip="Marker in use" style="opacity:0.3; background-image:url('+ plugin_url +'assets/images/admin/delete_icon.png)"></div>';
				}else{
					var delete_html = '<div class="hero_edit_item" onclick="delete_marker_request('+ val.marker_id +');" style="background-image:url('+ plugin_url +'assets/images/admin/delete_icon.png)"></div>';
				}
				var row_html = '<div class="hero_col_12" style="padding:0px;">';
				row_html += '<div class="hero_col_3"><div class="custom_marker_img_holder"><img src="data:image/png;base64,'+ val.img_binary +'"></div></div>';
					row_html += '<div class="hero_col_3" style="padding:20px 0px;"><span>'+ val.width +'px X '+ val.height +'px</span></div>';
					row_html += '<div class="hero_col_4" style="padding:20px 0px;"><span>Top: '+ val.top_offset +'px; Left: '+ val.left_offset +'px;</span></div>';
					row_html += '<div class="hero_col_2" style="padding:20px 0px;">';
						row_html += '<div class="hero_edits rounded_20">';
							row_html += '<div class="hero_edit_item" onclick="edit_custom_marker('+ val.marker_id +');" style="background-image:url('+ plugin_url +'assets/images/admin/edit_icon.png)"></div>';
							row_html += delete_html;
						row_html += '</div>';
					row_html += '</div>';
				row_html += '</div>';
				jQuery('#custom_marker_rows').append(row_html);
				switch_components();
			});
		}
	});
}

//edit custom marker
function edit_custom_marker(id){
	launch_hero_popup('markers/html_snippets/offset_edit.html','adjust_marker_offset','marker_edit_complete',undefined,{"marker_id":id});
}

//adjust marker offset
function adjust_marker_offset(json){
	//lookup marker
	jQuery.each(custom_markers, function(key,val){
		if(val.marker_id == json.marker_id){
			//place marker for adjustment
			place_marker_for_adjustment(val);
			return false;
		}
	});
}

//place marker for adjustment
function place_marker_for_adjustment(data){
	//adjustment config
	var img_width = 349;
	var img_height = 349;
	var container_width = (data.width * 2);
	var container_height = (data.height * 2);
	var container_border = 3;
	//set container positioning
	jQuery('.marker_border_container').css({
		'width': container_width + 'px',
		'height': container_height + 'px',
		'margin-top': Math.round((img_height / 2) - (container_height / 2) - container_border) + 'px',
		'margin-left': Math.round((img_width / 2) - (container_width / 2) - container_border) + 'px',
		'border': container_border +'px dashed #F00'
	});
	//load marker (with existing offset)
	jQuery('.marker_border_container').empty().append('<img id="marker_icon" style="position:absolute; top:'+ (data.height - data.top_offset) +'px; left:'+ (data.width - data.left_offset) +'px;" src="data:image/png;base64,'+ data.img_binary +'">');
	marker_top_offset = data.top_offset;
	marker_left_offset = data.left_offset;
	//set marker draggable
	jQuery('#marker_icon').draggable({
		revert: false,
		containment: "parent",
		cursor: 'move',
		stop: function(event, ui){
			marker_top_offset = (data.height - parseInt(jQuery(this).css('top')));
			marker_left_offset = (data.width - parseInt(jQuery(this).css('left')));
		}
	});
}

//marker edit complete
function marker_edit_complete(json){
	//show success
	show_message('success', 'Update Success', 'The marker offset has been successfully updated.');
	//update marker offset
	update_marker_offset(json.marker_id);
}

//update marker offset
function update_marker_offset(marker_id){
	//update marker offset
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_update_custom_marker_offset',
			'marker_id': marker_id,
			'top_offset': marker_top_offset,
			'left_offset': marker_left_offset
		},
		dataType: "json"
	}).done(function(response){
		//reset global offset values
		marker_top_offset = 0;
		marker_left_offset = 0;
		//reload markers
		get_custom_markers();
	});
}

//delete marker request
function delete_marker_request(marker_id){
	if(window.confirm('Are you sure you want to delete this marker?')){
		delete_marker(marker_id);
	}
}

//delete marker
function delete_marker(marker_id){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_remove_custom_marker',
			'marker_id': marker_id
		},
		dataType: "json"
	}).done(function(response){
		//show success
		show_message('success', 'Removal Success', 'The marker has been successfully removed.');
		//reload markers
		get_custom_markers();
	});
}