//MAPS_MARKERS VIEW

//view globals
var map_markers_object_load_timer_categories;

//view load
jQuery(function(){
	//extract categories
	extract_marker_categories();
	//set map holder droppable
	set_map_droppable();
});

//extract categories
function extract_marker_categories(){
	if(typeof map_markers_object === 'undefined'){
		clearTimeout(map_markers_object_load_timer_categories);
		map_markers_object_load_timer_categories = setTimeout("extract_marker_categories();",100);
	}else{
		clearTimeout(map_markers_object_load_timer_categories);
		//loop through markers
		jQuery('#marker_category').empty();
		jQuery.each(map_markers_object.categories, function(key,val){
			//place category
			jQuery('#map_marker_category').append('<option value="'+ val.category_id +'">'+ val.category +'</option>');
		});
		//enable components
		switch_components();
		//bind marker pack change listner
		bind_map_marker_pack_change_listener();
		//load presets for category
		load_presets_for_map_category();
	}
}

//bind marker pack change listner
function bind_map_marker_pack_change_listener(){
	jQuery('#map_marker_category').on('change', function(){
		load_presets_for_map_category();
	});
}

//load colour presets for category
function load_presets_for_map_category(){
	//get marker category
	var category_id = parseInt(jQuery("#map_marker_category option").filter(":selected").val());
	//empty container
	jQuery('.hero_preset_holder').empty();
	//remove notice
	jQuery('.pack_notice_copy').remove();
	//populate container
	var check_count = 0;
	jQuery.each(map_markers_object.categories[category_id].links, function(key,val){
		var active = '';
		if(check_count == 0){
			flid = val.link;
		}
		var preset_html = '<div id="colour_preset_cat_'+ val.link +'" class="hero_preset_color rounded_20" onclick="load_map_markers_for_colour_preset('+ category_id +', '+ val.link +');">';
		preset_html += '<div class="hero_preset_one rounded_left_20" style="background-color: rgb('+ hexToRgb(val.primary_colour) +');"></div>';
		preset_html += '<div class="hero_preset_two rounded_right_20" style="background-color: rgb('+ hexToRgb(val.secondary_colour) +');"></div>';
		preset_html += '</div>';
		jQuery('.hero_preset_holder').append(preset_html);
		check_count++;
	});
	//load markers for category colour preset
	load_map_markers_for_colour_preset(category_id,flid);
	//check for Custom pack
	if(map_markers_object.categories[category_id].category == 'Custom'){
		//change opacity
		jQuery('.hero_preset_color').css({
			'opacity': 0.1
		}).parent().parent().append('<div class="size_12 pack_notice_copy" style="float:left; margin:9px 0 0 15px;">Color schemes are not supported for custom markers</div>');
	}
}

//load markers for category colour preset
function load_map_markers_for_colour_preset(category_id,link_id){
	jQuery('#marker_display_holder').empty();
	jQuery('.hero_preset_color').removeClass('hero_preset_active');
	jQuery('#colour_preset_cat_'+ link_id).addClass('hero_preset_active');
	jQuery.each(map_markers_object.categories[category_id].links[link_id]['markers'], function(key,val){
		var width_resize_ratio = (30 / val.width);
		var new_height = parseInt(val.height * width_resize_ratio);
		var img_container = '<div class="marker_img_container" style="width:40px; height:'+ (new_height + 9) +'px">';
				img_container += '<img style="z-index:99999;" data-colour="'+ map_markers_object.categories[category_id].links[link_id].primary_colour +'" data-leftoffset="'+ val.left_offset +'" data-topoffset="'+ val.top_offset +'" data-width="'+ val.width +'" data-height="'+ val.height +'" data-id="'+ val.marker_id +'" id="marker_'+ val.marker_id +'" src="data:image/png;base64,'+ val.img_binary +'">';
			img_container += '</div>';
		jQuery('#marker_display_holder').append(img_container);
	});
	//set location markers draggable
	set_markers_draggable();
}

//set map holder droppable
function set_map_droppable(){
	jQuery('#hero_map_main').droppable({
		tolerance: 'fit',
		over: function(event, ui){
			var marker = jQuery('#'+ ui.draggable.prop('id'));
			var width = marker.data('width');
			var height = marker.data('height');
			marker.stop().animate({
				'width': width +'px',
				'height': height +'px'
			},200);
		},
		out: function(event, ui){
			var marker = jQuery('#'+ ui.draggable.prop('id'));
			var width_resize_ratio = (30 / marker.data('width'));
			var new_height = parseInt(marker.data('height') * width_resize_ratio);
			marker.stop().animate({
				'width': 30 +'px',
				'height': new_height +'px'
			},200);
		},
		drop: function(event, ui){
			var marker = jQuery('#'+ ui.draggable.prop('id'));
			var width_resize_ratio = (30 / marker.data('width'));
			var new_height = parseInt(marker.data('height') * width_resize_ratio);
			marker.stop().animate({
				'width': 30 +'px',
				'height': new_height +'px'
			},200);
			//get cursor position
			var offset = jQuery(this).offset();
            var x = event.pageX - offset.left;
            var y = event.pageY - offset.top;
			var point = new google.maps.Point(x,y);
			var latlng = map_overlay.getProjection().fromContainerPixelToLatLng(point);
			//place marker
			place_map_marker(ui.draggable.prop('id'), marker.attr('src'), latlng);
		}
    });
}

//set location markers draggable
function set_markers_draggable(){
	jQuery('.marker_img_container').each(function(key,val){
		var img = jQuery(this).children('img');
		var left_offset = img.data('leftoffset');
		var top_offset = img.data('topoffset');
		img.draggable({
			revert: true,
			cursorAt: {
				left: left_offset,
				top: (top_offset + 5)
			}
		});
	});
}

//place map marker
function place_map_marker(id,marker_src,latlng){
	//place marker on map
	var marker = jQuery('#'+ id);
	var width = marker.data('width');
	var height = marker.data('height');
	var top_offset = marker.data('topoffset');
	var left_offset = marker.data('leftoffset');
	var marker_id = marker.data('id');
	var colour = marker.data('colour');
	var icon_object = new google.maps.MarkerImage(marker_src, new google.maps.Size(width, height), new google.maps.Point(0, 0), new google.maps.Point(left_offset, top_offset));
	var map_marker = new google.maps.Marker({
		position: latlng,
		draggable: true,
		raiseOnDrag: true,
		icon: icon_object,
		map: google_map
	});
	//add marker to map_object
	var marker_object = {
		"marker_id": marker_id,
		"latlng": latlng.lat() +','+ latlng.lng(),
		"title": "",
		"info_window_show": false,
		"info_window_content": "",
		"new": true,
		"gmp": map_marker, //used as pointer to marker icon on map
		"link_show": false,
		"link_title": "",
		"link": "",
		"link_colour": colour,
		"link_target": "_blank",
		"custom_param": "",
		"deleted": false
	};
	//generate random string as id
	var icon_id = grs();
	//add the marker_object to the map_object
	map_object.map_markers[icon_id] = marker_object;
	//bind marker listeners
	bind_marker_listeners(map_marker,icon_id);
	//flag save
	flag_save_required('persist_map_object');
}

//generate random string
function grs(){
    function _p8(s){
        var p = (Math.random().toString(16)+"000000000").substr(2,8);
        return s ? p.substr(0,4) + p.substr(4,4) : p ;
    }
    return 'grs'+ _p8() + _p8(true) + _p8(true) + _p8();
}