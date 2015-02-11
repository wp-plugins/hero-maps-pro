<?php

	#PLUGIN FRONT-END MANAGEMENT
	/*
		note: used for all front-end output
	*/
	class hmapspro_frontend{
		
		#IMPLEMENT SHORTCODE LISTENER
		public function get_shortcode_content($atts){
			if(isset($atts['id'])){
				return $this->get_map_object(intval($atts['id']));
			}
			return 'hmapspro: shortcode malformed';
		}
		
		#GET MAP OBJECT
		private function get_map_object($map_id){
			//access globals
			global $wpdb;
			global $hmapspro_helper;
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
					`map_id` = ". $map_id ."
					AND `deleted` = 0;
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
			}else{
				return 'hmapspro: map not found';
			}
			//get marker data
			$marker_data = $wpdb->get_results("
				SELECT
					*,
					`mm`.`link` AS 'href'
				FROM
					`". $wpdb->prefix ."hmapspro_map_markers` `mm`
					INNER JOIN `". $wpdb->prefix ."hmapspro_markers` `m` ON(`m`.`marker_id` = `mm`.`marker_id`)
				WHERE
					`mm`.`map_id` = ". $map_id ."
					AND `mm`.`deleted` = 0;
			");
			if(count($marker_data) > 0){
				foreach($marker_data as $marker){
					$map_object['map_markers'][intval($marker->map_marker_id)] = array(
						'marker_id' => intval($marker->marker_id),
						'latlng' => $marker->latlng,
						'title' => $marker->title,
						'info_window_show' => (bool)$marker->info_window_show,
						'info_window_content' => nl2br($marker->info_window_content),
						'link_show' => (bool)$marker->link_show,
						'link_title' => $marker->link_title,
						'href' => $marker->href,
						'link_colour' => $marker->link_colour,
						'link_target' => $marker->link_target,
						'custom_param' => $marker->custom_param,
						'img_binary' => base64_encode($marker->img_binary),
						'width' => intval($marker->width),
						'height' => intval($marker->height),
						'top_offset' => intval($marker->top_offset),
						'left_offset' => intval($marker->left_offset)
					);
				}
			}
			//construct script
			$map_uid = 'muid'.str_replace('-','',$hmapspro_helper->genGUID());
			$map = '
				<script type="text/javascript">
					var hmapspro_map_object_'. $map_uid .' = '. json_encode($map_object) .';
					jQuery(function(){
						hmapspro_create_map(hmapspro_map_object_'. $map_uid .',\''. $map_uid .'\');
					});
				</script>
				<div id="hmapspro_map_'. $map_uid .'" class="hmapspro_map_container"></div>
			';
			//return map object
			return $map;
		}
		
	}