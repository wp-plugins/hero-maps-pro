<?php

	#MARKER PACK PROCESSOR
	class hmapspro_marker_processor{
		
		#CLASS VARS
		private $plugin_dir;
		private $marker_pack_dir = '/_marker_pack_uploads/';
		private $custom_marker_dir = '/_custom_marker_uploads/';
		private $custom_category_name = 'Custom';
		private $custom_primary_colour = '#999999';
		private $custom_secondary_colour = '#333333';
		
		#CONSTRUCT
		public function __construct($plugin_dir){
			//set class vars
			$this->plugin_dir = $plugin_dir;
		}
		
		#PROCESS CUSTOM MARKERS
		public function process_custom_markers(){
			//check directory for PNG's
			$file_mimes = array(
				'image/png'
			);
			//loop through directory and check files
			if($handle = opendir($this->plugin_dir . $this->custom_marker_dir)){
				while(false !== ($file = readdir($handle))){
					if('.' === $file) continue;
					if('..' === $file) continue;
					$path_to_file = $this->plugin_dir . $this->custom_marker_dir . $file;
					//check if PNG
					if(getimagesize($path_to_file)){
						$img_info = getimagesize($path_to_file);
						if(in_array($img_info['mime'], $file_mimes)){ //png
							//process marker image
							$marker_width = $img_info[0];
							$marker_height = $img_info[1];
							$marker_left_offset = round(($marker_width/2),0); //assume left offset = half image width
							$marker_top_offset = $marker_height; //assume top offset = marker height
							//access globals
							global $wpdb;
							//check if "Custom" marker category exists
							$check_result = $wpdb->get_results("
								SELECT
									*
								FROM
									`". $wpdb->prefix ."hmapspro_marker_categories`
								WHERE
									`name` = '". $this->custom_category_name ."';
							");
							if(count($check_result) == 0){
								//create category
								$wpdb->query("
									INSERT INTO `". $wpdb->prefix ."hmapspro_marker_categories` (`name`)
									VALUES ('". $this->custom_category_name ."');
								");
								//get category id
								$category_id = $wpdb->insert_id;
							}else{
								$category_id = $check_result[0]->category_id;
							}
							//get marker image binary
							$fp = fopen($path_to_file, 'r');
							$img_data = fread($fp, filesize($path_to_file));
							$img_data = addslashes($img_data);
							fclose($fp);
							//insert custom marker
							$wpdb->query("
								INSERT INTO `". $wpdb->prefix ."hmapspro_markers` (`category_id`,`img_binary`,`width`,`height`,`left_offset`,`top_offset`,`link`,`primary_colour`,`secondary_colour`)
								VALUES (". $category_id .",'". $img_data ."',". intval($marker_width) .",". intval($marker_height) .",". intval($marker_left_offset) .",". intval($marker_top_offset) .",0,'". $this->custom_primary_colour ."','". $this->custom_secondary_colour ."');
							");							
							//remove file
							unlink($path_to_file);
						}
					}
				}
				closedir($handle);
			}
			echo json_encode(true);
			exit();
		}
		
		#PROCESS MARKER PACKS
		public function process_marker_packs(){
			//loop through directory
			if($handle = opendir($this->plugin_dir . $this->marker_pack_dir)){
				while(false !== ($file = readdir($handle))){
					if('.' === $file) continue;
					if('..' === $file) continue;
					//unzip marker pack
					$this->unzip_marker_pack($file);
					//remove zip file
					$this->remove_zip($file);
					//check if marker pack is valid
					if($this->check_valid_marker_pack(basename($file,'.zip'))){
						//process the marker pack
						$this->process_marker_pack(basename($file,'.zip'));
					}
					//remove directory
					$this->remove_directory(basename($file,'.zip'));
				}
				closedir($handle);
			}
			//respond when processing complete
			echo json_encode(true);
			exit();
		}
		
		#UNZIP DIR
		private function unzip_marker_pack($file){
			$zip = new ZipArchive;
			if($zip->open($this->plugin_dir . $this->marker_pack_dir . $file) === TRUE){
				$zip->extractTo($this->plugin_dir . $this->marker_pack_dir . basename($file,'.zip'));
				$zip->close();
			}
		}
		
		#REMOVE ZIP
		private function remove_zip($file){
			if(is_file($this->plugin_dir . $this->marker_pack_dir . $file)){
				unlink($this->plugin_dir . $this->marker_pack_dir . $file);
			}
		}
		
		#CHECK FOR VALID MARKER PACK
		private function check_valid_marker_pack($dir_name){
			//check for config file and image directory
			if(is_file($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/pack_config.js') && is_dir($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/img')){
				return true;
			}
			return false;
		}
		
		#PROCESS MARKER PACK
		private function process_marker_pack($dir_name){
			//access globals
			global $wpdb;
			//read pack config
			$pack_data = json_decode(file_get_contents($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/pack_config.js'));
			$pack_name = $pack_data->config->name;
			$marker_height = $pack_data->config->height;
			$marker_width = $pack_data->config->width;
			$marker_left_offset = $pack_data->config->left_offset;
			$marker_top_offset = $pack_data->config->top_offset;
			//check if pack name exists
			$check_result = $wpdb->get_results("
				SELECT
					*
				FROM
					`". $wpdb->prefix ."hmapspro_marker_categories`
				WHERE
					`name` = '". $pack_name ."';
			");
			if(count($check_result) == 0){
				//create category
				$wpdb->query("
					INSERT INTO `". $wpdb->prefix ."hmapspro_marker_categories`(`name`)
					VALUES('". $pack_name ."');
				");
				//get category id
				$category_id = $wpdb->insert_id;
				//loop through markers and persist
				foreach($pack_data->markers as $marker_set){
					$link = $marker_set->link;
					$primary_colour = $marker_set->primary_colour;
					$secondary_colour = $marker_set->secondary_colour;
					if($handle = opendir($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/img/'. $link)){
						while(false !== ($file = readdir($handle))){
							if('.' === $file) continue;
							if('..' === $file) continue;
							$fp = fopen($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/img/'. $link .'/'. $file, 'r');
							$img_data = fread($fp, filesize($this->plugin_dir . $this->marker_pack_dir . $dir_name .'/img/'. $link .'/'. $file));
							$img_data = addslashes($img_data);
							fclose($fp);
							$wpdb->query("
								INSERT INTO `". $wpdb->prefix ."hmapspro_markers` (`category_id`,`img_binary`,`width`,`height`,`left_offset`,`top_offset`,`link`,`primary_colour`,`secondary_colour`)
								VALUES (". $category_id .",'". $img_data ."',". intval($marker_width) .",". intval($marker_height) .",". intval($marker_left_offset) .",". intval($marker_top_offset) .",". intval($link) .",'". $primary_colour ."','". $secondary_colour ."');
							");
						}
						closedir($handle);
					}
				}
			}
			return false;		
		}
		
		#REMOVE DIRECTORY AND CONTENTS
		private function remove_directory($dir){
			$it = new RecursiveDirectoryIterator($this->plugin_dir . $this->marker_pack_dir . $dir);
			$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
			foreach($files as $file){
				if($file->getFilename() === '.' || $file->getFilename() === '..'){
					continue;
				}
				if($file->isDir()){
					rmdir($file->getRealPath());
				}else{
					unlink($file->getRealPath());
				}
			}
			rmdir($this->plugin_dir . $this->marker_pack_dir . $dir);
		}		
		
	}