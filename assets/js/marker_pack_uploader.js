//MARKER PACK UPLOADER

//frame load
jQuery(function(){
	//bind upload button
	bind_upload_btn();
});

//bind upload button
function bind_upload_btn(){
	jQuery('.marker-pack-upload-btn a').off().on('click', function(){
		jQuery('#marker_pack').trigger('click').off().on('change', function(){
			jQuery('#marker-pack-uploader').trigger('submit');
		});
	});
}