//CUSTOM MARKER UPLOADER

//frame load
jQuery(function(){
	//bind upload button
	bind_upload_btn();
});

//bind upload button
function bind_upload_btn(){
	jQuery('.custom-marker-upload-btn a').off().on('click', function(){
		jQuery('#custom_marker').trigger('click').off().on('change', function(){
			jQuery('#custom-marker-uploader').trigger('submit');
		});
	});
}