<?php
	
	#SECURITY CHECK
	require_once('frame_sec.check.php');
	if(isset($secure_tag) && $secure_tag){ //secure (display content)

		#MARKER PACK UPLOADER
		
		//check for post
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			
			//vars
			$tmp_dir = '../_custom_marker_uploads/';
			$file_mimes = array(
				'image/png'
			);
			$min_dim = 40;
			$max_dim = 150;
			$img_info = getimagesize($_FILES['custom_marker']['tmp_name']);
			$img_width = $img_info[0];
			$img_height = $img_info[1];
			
			//check file type
			if(in_array($_FILES['custom_marker']['type'], $file_mimes) && $img_width >= $min_dim && $img_width <= $max_dim && $img_height >= $min_dim && $img_height <= $max_dim){
				//check tmp dir
				if(!is_dir($tmp_dir)){
					mkdir($tmp_dir);
				}
				//place file in tmp_dir
				$file_name = sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0x0fff) | 0x4000,mt_rand(0, 0x3fff) | 0x8000,mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
				$file = $tmp_dir . $file_name . '.png';
				move_uploaded_file($_FILES['custom_marker']['tmp_name'], $file);
				echo '
					<script type="text/javascript">
						window.parent.process_custom_marker(\'process_complete\');
					</script>
				';
			}else{
				echo '
					<script type="text/javascript">
						window.parent.show_message("error", "Upload Error", "The selected file was not a valid marker.");
					</script>
				';
			}
			
		}

?>

<!--BEGIN: includes-->
<link type="text/css" rel="stylesheet" href="../assets/css/custom_marker_uploader.css"></link>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/custom_marker_uploader.js"></script>
<!--END: includes-->

<!--BEGIN: upload form-->
<div class="custom_marker_uploader">
	<form method="post" enctype="multipart/form-data" id="custom-marker-uploader">
    	<div class="hero_form_row_full">
            <input type="file" id="custom_marker" name="custom_marker">
            <div class="custom-marker-upload-btn"><a class="hero_button_auto green_button rounded_3 size_14">Choose File</a></div>
        </div>
    </form>
</div>
<!--END: upload form-->

<?php
	}
?>