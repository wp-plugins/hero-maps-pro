//UPLOAD_MARKER_PACK_VIEW VIEW

//view load
jQuery(function(){
	//load iframe for pack upload
	load_iframe();
});

//load iframe for pack upload
function load_iframe(){
	load_secure_iframe('inc/marker_pack_uploader.php', 50, '.marker_pack_upload_holder');
}

//detect processing completion
function process_complete(){
	//show success message
	window.parent.show_message("success", "Upload Success", "The selected marker pack has been successfully installed.");
	//reload iframe (new security token)
	load_iframe();
	//navigate to marker packs page
	load_sub_view('marker_packs', 'markers/','marker_packs_view');
}