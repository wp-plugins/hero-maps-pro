//MARKER PROCESSOR

//load
jQuery(function(){
	//process marker pack
	process_marker_pack();
});

//process marker pack
function process_marker_pack(callback){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_process_marker_packs'
		},
		dataType: "json"
	}).done(function(response){
		if(typeof callback !== 'undefined'){
			eval(""+ callback +"();");
		}
	});
}

//process custom marker
function process_custom_marker(callback){
	jQuery.ajax({
		url: ajax_url,
		type: "POST",
		data: {
			'action': 'hmapspro_process_custom_markers'
		},
		dataType: "json"
	}).done(function(response){
		if(typeof callback !== 'undefined'){
			eval(""+ callback +"();");
		}
	});
}