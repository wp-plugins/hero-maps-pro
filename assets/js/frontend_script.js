//HMAPSPRO - frontend map management

//plugin globals
var hmaps_pro_map_loaded = false;
var hmapspro_loading_gmaps = false;
var hmapspro_info_window_pointer = [];

//create map
/*
	note: this method is called by classes/frontend.class.php
*/
function hmapspro_create_map(map_object,muid){
	if(typeof muid !== 'undefined' && typeof map_object !== 'undefined'){
		hmapspro_load_google_maps(map_object,muid);
	}
}

//load google maps
function hmapspro_load_google_maps(map_object,muid){
	//check if API already loaded
	if(!hmapspro_loading_gmaps){
		//mark loading
		hmapspro_loading_gmaps = true;
		if(typeof google === 'object' && typeof google.maps === 'object'){
			//mark google maps API loaded
			hmaps_pro_map_loaded = true;
		}else{
			//load google maps API
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=hmapspro_map_initialiser";
			document.body.appendChild(script);
		}
	}
	//initialise map
	hmapspro_initialise_map(map_object,muid);
}

//map initialiser
function hmapspro_map_initialiser(){
	hmaps_pro_map_loaded = true;
}

//initialise map
function hmapspro_initialise_map(map_object,muid){
	if(hmaps_pro_map_loaded){
		hmapspro_inject_map(map_object,muid);
	}else{
		//loop until google maps API is present
		setTimeout(function(){
			hmapspro_initialise_map(map_object,muid);
		},100);
	}
}

//inject map
function hmapspro_inject_map(map_object,muid){
	//add class to map container
	jQuery('#hmapspro_map_'+ muid).addClass(map_object.map_developers.css_class);
	//set container size
	jQuery('#hmapspro_map_'+ muid).css({
		'width': map_object.map_setup.responsive ? 100 +'%' : map_object.map_setup.map_width +'px', //check if responsive
		'height': map_object.map_setup.map_height +'px'
	});
	//initialise map
	latlng = eval("new google.maps.LatLng("+ map_object.map_settings.map_center +");");
	var map_options = {
		zoom: map_object.map_advanced.map_load_zoom,
		scrollwheel: map_object.map_settings.mouse_wheel_zoom,
		center: latlng, //check for marker bounds - adjust center if required
		disableDefaultUI: true,
		mapTypeId: eval("google.maps.MapTypeId."+ map_object.map_settings.map_type +""),
		//map controls
		streetViewControl: map_object.map_controls.street_view,
		streetViewControlOptions: {
			position: eval("google.maps.ControlPosition."+ map_object.map_controls.street_view_position +"")
		},
		mapTypeControl: map_object.map_controls.map_type,
		mapTypeControlOptions: {
			position: eval("google.maps.ControlPosition."+ map_object.map_controls.map_type_position +""),
			style: eval("google.maps.MapTypeControlStyle."+ map_object.map_controls.map_type_style +"")
		},
		panControl: map_object.map_controls.pan,
		panControlOptions: {
			position: eval("google.maps.ControlPosition."+ map_object.map_controls.pan_position +"")
		},
		zoomControl: map_object.map_controls.zoom,
		zoomControlOptions: {
			position: eval("google.maps.ControlPosition."+ map_object.map_controls.zoom_position +""),
			style: eval("google.maps.ZoomControlStyle."+ map_object.map_controls.zoom_style +"")
		},
		scaleControl: map_object.map_controls.scale,
		overviewMapControl: map_object.map_controls.overview,
		overviewMapControlOptions: {
			opened: map_object.map_controls.overview_style
		}
	};
	//load map
	var google_map = new google.maps.Map(document.getElementById("hmapspro_map_"+ muid),map_options);
	//map theme
	if(map_object.map_settings.map_theme){
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
	//markers
	google.maps.event.addListener(google_map, "tilesloaded", function(){ //check for map load completion
		//remove listener
		google.maps.event.clearListeners(this,"tilesloaded");
		//remove default object
		delete map_object.map_markers.default;
		//place map markers
		var hmapspro_info_window_pointer;
		var cur_map_center;
		var cur_map_zoom;
		var bounds = new google.maps.LatLngBounds();
		//place markers after delay
		setTimeout(function(){
			var animation_timer = 0;
			var cur_marker_count = 0;
			var marker_count = (Object.keys(map_object.map_markers).length);
			jQuery.each(map_object.map_markers, function(key,val){
				animation_timer = (animation_timer + map_object.map_advanced.marker_animation_timer);
				setTimeout(function(){
					//place marker
					var width = val.width;
					var height = val.height;
					var top_offset = val.top_offset;
					var left_offset = val.left_offset;
					var icon_object = new google.maps.MarkerImage('data:image/png;base64,'+ val.img_binary, new google.maps.Size(width, height), new google.maps.Point(0, 0), new google.maps.Point(left_offset, top_offset));
					var latlng_object = val.latlng.split(',');
					var latlng = new google.maps.LatLng(latlng_object[0] , latlng_object[1]);
					var map_marker = new google.maps.Marker({
						position: latlng,
						draggable: false,
						icon: icon_object,
						map: google_map,
						animation: eval("google.maps.Animation."+ map_object.map_advanced.marker_animation +""),
						title: map_object.map_advanced.marker_tooltip ? val.title : null
					});
					//check if autfit					
					if(map_object.map_settings.autofit){
						bounds.extend(latlng);
					}
					//add marker click event listner
					google.maps.event.addListener(map_marker, "click", function(){
						//close info window if already open
						if(hmapspro_info_window_pointer != null){
							hmapspro_info_window_pointer.close();
							hmapspro_info_window_pointer = null;
						}else{
							//get current map center and zoom
							cur_map_center = google_map.getCenter();
							cur_map_zoom = google_map.getZoom();
						}
						//set zoom
						google_map.setZoom(map_object.map_advanced.marker_click_zoom);
						//pan to marker
						google_map.panTo(latlng);
						//check if callback required
						if(map_object.map_developers.javascript_callback){
							if(typeof map_object.map_developers.javascript_callback !== 'undefined' && map_object.map_developers.javascript_callback !== '' && map_object.map_developers.callback_method != ''){
								var extract = false;
								if(eval("typeof "+ map_object.map_developers.callback_method +" !== 'undefined'")){
									extract = true;
								}
								if(extract){
									//construct json object
									var json_object = {"marker_id": key, "location_title": val.title == '' ? null : val.title , "custom_param": val.custom_param == '' ? null : val.custom_param}
									eval(""+ map_object.map_developers.callback_method +"(hmapspro_extract_json('"+ encodeURIComponent(JSON.stringify(json_object)) +"'));");
								}
							}
						}
						//check if info window required
						if(val.info_window_show || val.link_show){
							//create info window html
							var info_window_html = '<div class="hmapspro_info_window">';
								info_window_html += '<h3>'+ val.title +'</h3>';
								if(val.info_window_show){
									info_window_html += '<p>';
										info_window_html += val.info_window_content;
									info_window_html += '</p>';
								}
								if(val.link_show){
									info_window_html += '<a href="'+ val.href +'" target="'+ val.link_target +'" style="color:'+ val.link_colour +'">';
										info_window_html += val.link_title;
									info_window_html += '</a>';
								}
								info_window_html += '</div>';
							//add info window	
							var infowindow = new google.maps.InfoWindow({
								content: info_window_html
							});
							//open info window
							infowindow.open(google_map,map_marker);
							//add info window object to array
							hmapspro_info_window_pointer = infowindow;					
							//detect close
							google.maps.event.addListener(infowindow, "closeclick", function(){
								//clear info window object
								hmapspro_info_window_pointer = null;
								//go back to prev pan and latlng on close
								google_map.setCenter(cur_map_center);
								google_map.setZoom(cur_map_zoom);
							});	
						}
					});
					//fit bounds
					cur_marker_count++;
					if(map_object.map_settings.autofit && cur_marker_count == marker_count){
						setTimeout(function(){
							google_map.fitBounds(bounds);
						}, map_object.map_advanced.marker_drop_delay);
					}
				}, animation_timer);
			});
			//center map
			if(!map_object.map_settings.autofit){
				var latlng_object = map_object.map_settings.map_center.split(',');
				var latlng = new google.maps.LatLng(latlng_object[0] , latlng_object[1]);
				google_map.setCenter(latlng);
				google_map.setZoom(map_object.map_settings.rest_zoom);
			}
		}, map_object.map_advanced.marker_drop_delay);
	});
	//maintain map center on resize
	var center;
	function calculateCenter(){
		center = google_map.getCenter();
	}
	google.maps.event.addDomListener(google_map, 'idle', function(){
		calculateCenter();
	});
	//change to detect map holder resize as opposed to a window resize
	var map_div = document.getElementById('hmapspro_map_'+ muid);
	google.maps.event.addDomListener(map_div, 'resize', function(){
		google_map.setCenter(center);
	});
}

//extract json object
function hmapspro_extract_json(json){
	if(json !== 'undefined'){
		return JSON.parse(decodeURIComponent(json));
	}
	return false;
}