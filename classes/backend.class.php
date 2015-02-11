<?php

	#PLUGIN BACK-END MANAGEMENT
	/*
		note: used for all admin panel calls
	*/
	class hmapspro_backend{
		
		#UPDATE MAP OBJECT
		public function update_map_object(){
			//access globals
			global $wpdb;
			//get post data
			$map_object = $_POST['map_object'];
			//update map settings
			$wpdb->query("
				UPDATE `". $wpdb->prefix ."hmapspro_maps` SET
					`name` = '". $map_object['map_setup']['map_name'] ."',
					`responsive` = ". $map_object['map_setup']['responsive'] .",
					`width` = ". $map_object['map_setup']['map_width'] .",
					`height` = ". $map_object['map_setup']['map_height'] .",
					`map_type` = '". $map_object['map_settings']['map_type'] ."',
					`map_theme` = '". $map_object['map_settings']['map_theme'] ."',
					`autofit` = ". $map_object['map_settings']['autofit'] .",
					`map_center` = '". $map_object['map_settings']['map_center'] ."',
					`rest_zoom` = ". $map_object['map_settings']['rest_zoom'] .",
					`mouse_wheel_zoom` = ". $map_object['map_settings']['mouse_wheel_zoom'] .",
					`control_street_view` = ". $map_object['map_controls']['street_view'] .",
					`control_street_view_position` = '". $map_object['map_controls']['street_view_position'] ."',
					`control_map_type` = ". $map_object['map_controls']['map_type'] .",
					`control_map_type_position` = '". $map_object['map_controls']['map_type_position'] ."',
					`control_map_type_style` = '". $map_object['map_controls']['map_type_style'] ."',
					`control_pan` = ". $map_object['map_controls']['pan'] .",
					`control_pan_position` = '". $map_object['map_controls']['pan_position'] ."',
					`control_zoom` = ". $map_object['map_controls']['zoom'] .",
					`control_zoom_position` = '". $map_object['map_controls']['zoom_position'] ."',
					`control_zoom_style` = '". $map_object['map_controls']['zoom_style'] ."',
					`control_scale` = ". $map_object['map_controls']['scale'] .",
					`control_overview` = ". $map_object['map_controls']['overview'] .",
					`control_overview_style` = ". $map_object['map_controls']['overview_style'] .",				
					`marker_drop_delay` = ". $map_object['map_advanced']['marker_drop_delay'] .",
					`marker_animation` = '". $map_object['map_advanced']['marker_animation'] ."',
					`marker_animation_timer` = ". $map_object['map_advanced']['marker_animation_timer'] .",
					`marker_tooltip` = ". $map_object['map_advanced']['marker_tooltip'] .",
					`map_load_zoom` = ". $map_object['map_advanced']['map_load_zoom'] .",
					`marker_click_zoom` = ". $map_object['map_advanced']['marker_click_zoom'] .",					
					`javascript_callback` = ". $map_object['map_developers']['javascript_callback'] .",
					`callback_method` = '". $map_object['map_developers']['callback_method'] ."',
					`css_class` = '". $map_object['map_developers']['css_class'] ."'
				WHERE
					`map_id` = ". $map_object['map_setup']['map_id'] .";
			");
			//update markers
			if(isset($map_object['map_markers'])){
				foreach($map_object['map_markers'] as $marker){
					if($marker['new'] == 'true' && $marker['deleted'] == 'false'){ //add new marker
						$info_window_show = ($marker['info_window_show'] == 'false') ? 0 : 1;
						$link_show = ($marker['link_show'] == 'false') ? 0 : 1;
						$result = $wpdb->query("
							INSERT INTO `". $wpdb->prefix ."hmapspro_map_markers` (
								`map_id`,
								`marker_id`,
								`latlng`,
								`title`,
								`info_window_show`,
								`info_window_content`,
								`link_show`,
								`link_title`,
								`link`,
								`link_colour`,
								`link_target`,
								`custom_param`
							)
							VALUES (
								". intval($map_object['map_setup']['map_id']) .",
								". intval($marker['marker_id']) .",
								'". $marker['latlng'] ."',
								'". $marker['title'] ."',
								". $info_window_show .",
								'". $marker['info_window_content'] ."',
								". $link_show .",
								'". $marker['link_title'] ."',
								'". $marker['link'] ."',
								'". $marker['link_colour'] ."',
								'". $marker['link_target'] ."',
								'". $marker['custom_param'] ."'
							);
						");
					}elseif($marker['new'] == 'false' && $marker['deleted'] == 'true'){ //remove existing marker
						$wpdb->query("
							UPDATE `". $wpdb->prefix ."hmapspro_map_markers`
							SET `deleted` = 1
							WHERE `map_marker_id` = ". $marker['map_marker_id'] .";
						");
					}elseif($marker['new'] == 'false' && $marker['deleted'] == 'false'){ //update existing marker
						$info_window_show = ($marker['info_window_show'] == 'false') ? 0 : 1;
						$link_show = ($marker['link_show'] == 'false') ? 0 : 1;
						$wpdb->query("
							UPDATE `". $wpdb->prefix ."hmapspro_map_markers` SET
								`marker_id` = ". intval($marker['marker_id']) .",
								`latlng` = '". $marker['latlng'] ."',
								`title` = '". $marker['title'] ."',
								`info_window_show` = ". $info_window_show .",
								`info_window_content` = '". $marker['info_window_content'] ."',
								`link_show` = ". $link_show .",
								`link_title` = '". $marker['link_title'] ."',
								`link` = '". $marker['link'] ."',
								`link_colour` = '". $marker['link_colour'] ."',
								`link_target` = '". $marker['link_target'] ."',
								`custom_param` = '". $marker['custom_param'] ."'
							WHERE
								`map_marker_id` = ". intval($marker['map_marker_id']) .";
						");
					}
				}
			}
			//return updated map marker data
			echo json_encode($this->get_map_marker_data(intval($map_object['map_setup']['map_id'])));
			exit();
		}
		
		#GET MAP MARKER DATA
		private function get_map_marker_data($map_id){
			//access globals
			global $wpdb;
			global $hmapspro_helper;
			//create marker object
			$map_object = array(
				'map_markers' => array('default' => true)
			);
			//get marker data
			$marker_data = $wpdb->get_results("
				SELECT
					*
				FROM
					`". $wpdb->prefix ."hmapspro_map_markers`
				WHERE
					`map_id` = ". $map_id ."
					AND `deleted` = 0;
			");
			if(count($marker_data) > 0){
				foreach($marker_data as $marker){
					$map_object['map_markers']['grs'.str_replace('-','',$hmapspro_helper->genGUID())] = array(
						'map_marker_id' => intval($marker->map_marker_id),
						'marker_id' => intval($marker->marker_id),
						'latlng' => $marker->latlng,
						'title' => $marker->title,
						'info_window_show' => (bool)$marker->info_window_show,
						'info_window_content' => $marker->info_window_content,
						'new' => false,
						'gmp' => NULL,
						'link_show' => (bool)$marker->link_show,
						'link_title' => $marker->link_title,
						'link' => $marker->link,
						'link_colour' => $marker->link_colour,
						'link_target' => $marker->link_target,
						'custom_param' => $marker->custom_param,
						'deleted' => false
					);
				}
			}
			//return 
			return $map_object['map_markers'];
		}
		
		#GET MAP DATA
		public function get_map_data(){
			//access globals
			global $wpdb;
			global $hmapspro_helper;
			//get post data
			$map_id = intval($_POST['map_id']);
			//create map object
			$map_object = array(
				'map_setup' => array(),
				'map_markers' => array('default' => true),
				'map_settings' => array(),
				'map_controls' => array(),
				'map_advanced' => array(),
				'map_developers' => array()
			);
			//get map data
			$map_data = $wpdb->get_results("
				SELECT
					*
				FROM
					`". $wpdb->prefix ."hmapspro_maps` `m`
				WHERE
					`map_id` = ". $map_id .";
			");
			if(count($map_data) > 0){
				$map_object['map_setup'] = array(
					'map_id' => intval($map_data[0]->map_id),
					'map_name' => $map_data[0]->name,
					'responsive' => (bool)$map_data[0]->responsive,
					'map_width' => intval($map_data[0]->width),
					'map_height' => intval($map_data[0]->height)
				);
				$map_object['map_settings'] = array(
					'map_type' => $map_data[0]->map_type,
					'map_theme' => $map_data[0]->map_theme,
					'autofit' => (bool)$map_data[0]->autofit,
					'map_center' => $map_data[0]->map_center,
					'rest_zoom' => intval($map_data[0]->rest_zoom),
					'mouse_wheel_zoom' => (bool)$map_data[0]->mouse_wheel_zoom
				);
				$map_object['map_controls'] = array(
					'street_view' => (bool)$map_data[0]->control_street_view,
					'street_view_position' => $map_data[0]->control_street_view_position,
					'map_type' => (bool)$map_data[0]->control_map_type,
					'map_type_position' => $map_data[0]->control_map_type_position,
					'map_type_style' => $map_data[0]->control_map_type_style,
					'pan' => (bool)$map_data[0]->control_pan,
					'pan_position' => $map_data[0]->control_pan_position,
					'zoom' => (bool)$map_data[0]->control_zoom,
					'zoom_position' => $map_data[0]->control_zoom_position,
					'zoom_style' => $map_data[0]->control_zoom_style,
					'scale' => (bool)$map_data[0]->control_scale,
					'overview' => (bool)$map_data[0]->control_overview,
					'overview_style' => (bool)$map_data[0]->control_overview_style
				);
				$map_object['map_advanced'] = array(
					'marker_drop_delay' => intval($map_data[0]->marker_drop_delay),
					'marker_animation' => $map_data[0]->marker_animation,
					'marker_animation_timer' => intval($map_data[0]->marker_animation_timer),
					'marker_tooltip' => (bool)$map_data[0]->marker_tooltip,
					'map_load_zoom' => intval($map_data[0]->map_load_zoom),
					'marker_click_zoom' => intval($map_data[0]->marker_click_zoom)
				);
				$map_object['map_developers'] = array(
					'javascript_callback' => (bool)$map_data[0]->javascript_callback,
					'css_class' => $map_data[0]->css_class,
					'callback_method' => $map_data[0]->callback_method
				);
			}
			//get marker data
			$marker_data = $wpdb->get_results("
				SELECT
					*
				FROM
					`". $wpdb->prefix ."hmapspro_map_markers`
				WHERE
					`map_id` = ". $map_id ."
					AND `deleted` = 0;
			");
			if(count($marker_data) > 0){
				foreach($marker_data as $marker){
					$map_object['map_markers']['grs'.str_replace('-','',$hmapspro_helper->genGUID())] = array(
						'map_marker_id' => intval($marker->map_marker_id),
						'marker_id' => intval($marker->marker_id),
						'latlng' => $marker->latlng,
						'title' => $marker->title,
						'info_window_show' => (bool)$marker->info_window_show,
						'info_window_content' => $marker->info_window_content,
						'new' => false,
						'gmp' => NULL,
						'link_show' => (bool)$marker->link_show,
						'link_title' => $marker->link_title,
						'link' => $marker->link,
						'link_colour' => $marker->link_colour,
						'link_target' => $marker->link_target,
						'custom_param' => $marker->custom_param,
						'deleted' => false
					);
				}
			}
			//return map object
			echo json_encode($map_object);
			exit();
		}
		
		#GENERATE NEW MAP
		public function generate_new_map(){
			//access globals
			global $wpdb;
			//get map name
			$map_name = $_POST['map_name'];
			//generate new map
			$wpdb->query("
				INSERT INTO `". $wpdb->prefix ."hmapspro_maps` (
					`name`,
					`responsive`,
					`width`,
					`height`,
					`map_type`,
					`map_theme`,
					`autofit`,
					`map_center`,
					`rest_zoom`,
					`mouse_wheel_zoom`,
					`control_street_view`,
					`control_street_view_position`,
					`control_map_type`,
					`control_map_type_position`,
					`control_map_type_style`,
					`control_pan`,
					`control_pan_position`,
					`control_zoom`,
					`control_zoom_position`,
					`control_zoom_style`,
					`control_scale`,
					`control_overview`,
					`control_overview_style`,
					`marker_drop_delay`,
					`marker_animation`,
					`marker_animation_timer`,
					`marker_tooltip`,
					`map_load_zoom`,
					`marker_click_zoom`,
					`javascript_callback`,
					`callback_method`,
					`css_class`
				)
				VALUES (
					'". $map_name ."',
					true,
					100,
					400,
					'ROADMAP',
					'',
					true,
					'0,0',
					3,
					false,
					true,
					'DEFAULT',
					false,
					'DEFAULT',
					'DEFAULT',
					false,
					'DEFAULT',
					true,
					'LEFT_CENTER',
					'LARGE',
					false,
					false,
					false,
					1000,
					'DROP',
					100,
					true,
					2,
					17,
					false,
					NULL,
					''
				);
			");
			//get map id
			$map_id = $wpdb->insert_id;
			//return true
			echo json_encode($map_id);
			exit();
		}
		
		#GET ALL MAPS
		public function get_all_maps(){
			//access globals
			global $wpdb;
			//get custom markers
			$maps = array();
			$map_results = $wpdb->get_results("
				SELECT
					*
				FROM
					`". $wpdb->prefix ."hmapspro_maps` `m`
				WHERE
					`deleted` = 0
				ORDER BY
					`m`.`name` ASC;
			");
			if(count($map_results) > 0){
				//loop through markers and base64 encode image data
				foreach($map_results as $marker){
					array_push($maps, array(
						'map_id' => intval($marker->map_id),
						'name' => str_replace('\'','&#39;',$marker->name)
					));
				}
			}else{
				$maps = false;
			}
			//respond
			echo json_encode($maps);
			exit();
		}
		
		#DELETE MAP
		public function delete_map(){
			//access globals
			global $wpdb;
			//get post data
			$map_id = intval($_POST['map_id']);
			//remove map markers
			$wpdb->query("UPDATE `". $wpdb->prefix ."hmapspro_map_markers` SET `deleted` = 1 WHERE `map_id` = ". $map_id .";");
			//remove map
			$wpdb->query("UPDATE `". $wpdb->prefix ."hmapspro_maps` SET `deleted` = 1 WHERE `map_id` = ". $map_id .";");
			//respond
			echo json_encode(true);
			exit();
		}
		
		#GET MARKERS
		public function get_markers(){
			//access globals
			global $wpdb;
			//get markers
			$markers = array(
				'categories' => array()
			);
			$markers_object = $wpdb->get_results("
				SELECT
					`mc`.`category_id`,
					`mc`.`name` AS 'category',
					`m`.`marker_id`,
					`m`.`img_binary`,
					`m`.`width`,
					`m`.`height`,
					`m`.`left_offset`,
					`m`.`top_offset`,
					`m`.`link`,
					`m`.`primary_colour`,
					`m`.`secondary_colour`
				FROM
					`". $wpdb->prefix ."hmapspro_markers` `m`
					INNER JOIN `". $wpdb->prefix ."hmapspro_marker_categories` `mc` ON(`mc`.`category_id` = `m`.`category_id`)
				AND `m`.`deleted` = 0;
			");
			//check marker object
			if(count($markers_object) > 0){
				//extract categories
				foreach($markers_object as $marker){
					$markers['categories'][intval($marker->category_id)] = array(
						'category_id' => intval($marker->category_id),
						'category' => $marker->category,
						'links' => array()
					);
				}
				//extract links per category
				foreach($markers_object as $marker){
					$markers['categories'][intval($marker->category_id)]['links'][intval($marker->link)] = array(
						'primary_colour' => $marker->primary_colour,
						'secondary_colour' => $marker->secondary_colour,
						'link' => intval($marker->link),
						'markers' => array()
					);
				}
				//link markers to category links
				foreach($markers_object as $marker){
					array_push($markers['categories'][intval($marker->category_id)]['links'][intval($marker->link)]['markers'], array(
						'marker_id' => intval($marker->marker_id),
						'img_binary' => base64_encode($marker->img_binary),
						'width' => intval($marker->width),
						'height' => intval($marker->height),
						'left_offset' => intval($marker->left_offset),
						'top_offset' => intval($marker->top_offset)
					));
				}
			}else{
				$markers = false;
			}
			//respond
			echo json_encode($markers);
			exit();
		}
		
		#GET CUSTOM MARKERS
		public function get_custom_markers(){
			//access globals
			global $wpdb;
			//get custom markers
			$custom_markers = array();
			$custom_marker_results = $wpdb->get_results("
				SELECT
					`m`.*,
					COUNT(`mm`.`map_marker_id`) AS 'used'
				FROM
					`". $wpdb->prefix ."hmapspro_markers` `m`
					INNER JOIN `". $wpdb->prefix ."hmapspro_marker_categories` `mc` ON(`mc`.`category_id` = `m`.`category_id` AND `mc`.`name` = 'Custom')
					LEFT JOIN `". $wpdb->prefix ."hmapspro_map_markers` `mm` ON(`mm`.`marker_id` = `m`.`marker_id` AND `mm`.`deleted` = 0)
				WHERE
					`m`.`deleted` = 0
				GROUP BY
					`m`.`marker_id`
				ORDER BY
					`m`.`created` DESC;
			");
			if(count($custom_marker_results) > 0){
				//loop through markers and base64 encode image data
				foreach($custom_marker_results as $marker){
					array_push($custom_markers, array(
						'marker_id' => intval($marker->marker_id),
						'width' => intval($marker->width),
						'height' => intval($marker->height),
						'left_offset' => intval($marker->left_offset),
						'top_offset' => intval($marker->top_offset),
						'img_binary' => base64_encode($marker->img_binary),
						'used' => (bool)$marker->used
					));
				}
			}else{
				$custom_markers = false;
			}
			//respond
			echo json_encode($custom_markers);
			exit();
		}
		
		#UPDATE CUSTOM MARKER OFFSET
		public function update_custom_marker_offset(){
			//access globals
			global $wpdb;
			//get post data
			$marker_id = intval($_POST['marker_id']);
			$top_offset = intval($_POST['top_offset']);
			$left_offset = intval($_POST['left_offset']);
			//update marker
			$wpdb->query("
				UPDATE `". $wpdb->prefix ."hmapspro_markers`
				SET `left_offset` = ". $left_offset .", `top_offset` = ". $top_offset ."
				WHERE `marker_id` = ". $marker_id .";
			");
			echo json_encode(true);
			exit();	
		}
		
		#REMOVE CUSTOM MARKER
		public function remove_custom_marker(){
			//access globals
			global $wpdb;
			//get post data
			$marker_id = intval($_POST['marker_id']);
			//remove marker
			$wpdb->query("
				UPDATE `". $wpdb->prefix ."hmapspro_markers`
				SET `deleted` = 1
				WHERE `marker_id` = ". $marker_id .";
			");
			echo json_encode(true);
			exit();
		}
		
	}