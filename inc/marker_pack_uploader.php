<?php
	
	#SECURITY CHECK
	require_once('frame_sec.check.php');
	if(isset($secure_tag) && $secure_tag){ //secure (display content)

		#MARKER PACK UPLOADER
		
		//check for post
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			
			//vars
			$tmp_dir = '../_marker_pack_uploads/';
			$file_mimes = array(
				'application/zip',
				'application/x-zip',
				'application/x-zip-compressed',
				'application/octet-stream',
				'application/x-compress',
				'application/x-compressed',
				'multipart/x-zip',			 			
				'application/rar',
				'application/x-rar',
				'application/x-rar-compressed'
			);
			
			//check file type
			if(in_array($_FILES['marker_pack']['type'], $file_mimes)){
				//check tmp dir
				if(!is_dir($tmp_dir)){
					mkdir($tmp_dir);
				}
				//place file in tmp_dir
				$file_name = sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0x0fff) | 0x4000,mt_rand(0, 0x3fff) | 0x8000,mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
				$file = $tmp_dir . $file_name . '.zip';
				move_uploaded_file($_FILES['marker_pack']['tmp_name'], $file);
				echo '
					<script type="text/javascript">
						window.parent.process_marker_pack(\'process_complete\');
					</script>
				';
			}else{
				echo '
					<script type="text/javascript">
						window.parent.show_message("error", "Upload Error", "The selected file was not a valid marker pack.");
					</script>
				';
			}
			
		}

?>

<!--BEGIN: includes-->
<link type="text/css" rel="stylesheet" href="../assets/css/marker_pack_uploader.css"></link>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/marker_pack_uploader.js"></script>
<!--END: includes-->

<!--BEGIN: upload form-->
<div class="marker_pack_uploader">
	<form method="post" enctype="multipart/form-data" id="marker-pack-uploader">
    	<div class="hero_form_row_full">
            <input type="file" id="marker_pack" name="marker_pack">
            <div class="marker-pack-upload-btn"><a class="hero_button_auto green_button rounded_3 size_14">Choose File</a></div>
        </div>
    </form>
</div>
<!--END: upload form-->

<?php
	}
?>