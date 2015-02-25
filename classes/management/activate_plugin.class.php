<?php
		
	#ACTIVATE PLUGIN
	class hmapspro_activate{
		
		#CLASS VARS
		private $plugin_name;
		private $plugin_version;
		private $plugin_old_version;
		
		#CONSTRUCT
		public function __construct($plugin_name,$plugin_version){
			//define class vars
			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			//update check
			$this->update_check();
		}
		
		#CHECK FOR UPGRADE
		private function update_check(){
			global $wpdb;
			$plugin_lookup = $wpdb->get_results("SELECT * FROM `". $wpdb->prefix ."hplugin_root` WHERE `plugin_name` = '". $this->plugin_name ."';");
			if($plugin_lookup){
				$this->plugin_old_version = $plugin_lookup[0]->plugin_version;
				if(version_compare($this->plugin_old_version,$this->plugin_version,'<')){
					$update = new hmapspro_update_plugin($this->plugin_name,$this->plugin_version,$this->plugin_old_version);
					$update->update_plugin();
				}
			}
		}
		
		#ACTIVATE
		private function activate(){
			//access globals
			global $wpdb;
			global $hmapspro_helper;
			//create the hplugin_root table if it doesn't exist
			$sql_create = "
				CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."hplugin_root` (
				  `hplugin_id` int(11) NOT NULL AUTO_INCREMENT,
				  `plugin_name` varchar(45) NOT NULL,
				  `plugin_version` varchar(10) NOT NULL,
				  `plugin_uuid` varchar(36) NOT NULL,
				  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `last_modified` datetime DEFAULT NULL,
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `deleted` tinyint(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`hplugin_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			";
			dbDelta($sql_create);
			//drop trigger if exists
			$sql_drop = "
				DROP TRIGGER IF EXISTS `". $wpdb->prefix ."hplugin_root`;
			";
			$wpdb->query($sql_drop);
			//re-create trigger
			$sql_create = "
				CREATE TRIGGER `". $wpdb->prefix ."hplugin_root`
				BEFORE UPDATE ON `". $wpdb->prefix ."hplugin_root`
				FOR EACH ROW SET NEW.last_modified = NOW();
			";
			dbDelta($sql_create);
			//check if plugin exists in hplugin_root table
			$plugin_lookup = $wpdb->get_results("SELECT * FROM `". $wpdb->prefix ."hplugin_root` WHERE `plugin_name` = '". $this->plugin_name ."';");
			if(!$plugin_lookup){ //add if not exists
				$wpdb->query("INSERT INTO `". $wpdb->prefix ."hplugin_root` (`plugin_name`,`plugin_version`,`plugin_uuid`) VALUES('". $this->plugin_name ."','". $this->plugin_version ."','". $hmapspro_helper->genGUID() ."');");
			}else{ //ensure that deleted = 0
				$wpdb->query("UPDATE `". $wpdb->prefix ."hplugin_root` SET `deleted` = 0, `active` = 1 WHERE `plugin_name` = '". $this->plugin_name ."';");
			}
		}
		
		#SETUP PLUGIN
		public function setup_plugin(){
			//activate plugin
			$this->activate();
			//create plugin tables
			$this->create_marker_categories_table(); //marker categories
			$this->create_markers_table(); //markers
			$this->create_maps_table(); //maps
			$this->create_map_markers_table(); //map markers
		}
		
		#CREATE MARKER CATEGORIES TABLE
		private function create_marker_categories_table(){
			//access globals
			global $wpdb;
			//create the hmapspro_marker_categories table if it doesn't exist
			$sql_create = "
				CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."hmapspro_marker_categories` (
					`category_id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(45) NOT NULL,
					`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`category_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			dbDelta($sql_create);
			return true;
		}
		
		#CREATE MARKERS TABLE
		private function create_markers_table(){
			//access globals
			global $wpdb;
			//create unique constraint values
			$contraint_uid = date('Hidmy'); // this is to prevent FK restraints from adding if on a multisite WP install
			//create the hmapspro_markers table if it doesn't exist
			$sql_create = "
				CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."hmapspro_markers` (
					`marker_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`category_id` int(11) NOT NULL,
					`img_binary` blob NOT NULL,
					`width` int(11) NOT NULL,
					`height` int(11) NOT NULL,
					`left_offset` int(11) NOT NULL,
					`top_offset` int(11) NOT NULL,
					`link` int(11) NOT NULL,
					`primary_colour` varchar(7) NOT NULL,
					`secondary_colour` varchar(7) NOT NULL,
					`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`deleted` tinyint(1) NOT NULL,
					PRIMARY KEY (`marker_id`),
					KEY `category_id_hmapspro_markers_FK_idx` (`category_id`),
					CONSTRAINT `category_id_hmapspro_markers_". $contraint_uid ."_FK` FOREIGN KEY (`category_id`) REFERENCES `". $wpdb->prefix ."hmapspro_marker_categories` (`category_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			dbDelta($sql_create);
			return true;
		}
		
		#CREATE MAPS TABLE
		private function create_maps_table(){
			//access globals
			global $wpdb;
			//create the hmapspro_maps table if it doesn't exist
			$sql_create = "
				CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."hmapspro_maps` (
					`map_id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(50) NOT NULL,
					`responsive` tinyint(1) NOT NULL,
					`width` int(11) NOT NULL,
					`height` int(11) NOT NULL,
					`map_type` varchar(50) NOT NULL,
					`map_theme` varchar(50) NOT NULL,
					`autofit` tinyint(1) NOT NULL,
					`map_center` varchar(255) NOT NULL,
					`rest_zoom` int(11) NOT NULL,
					`mouse_wheel_zoom` tinyint(1) NOT NULL,
					`control_street_view` tinyint(1) NOT NULL,
					`control_street_view_position` varchar(45) NOT NULL,
					`control_map_type` tinyint(1) NOT NULL,
					`control_map_type_position` varchar(45) NOT NULL,
					`control_map_type_style` varchar(45) NOT NULL,
					`control_pan` tinyint(1) NOT NULL,
					`control_pan_position` varchar(45) NOT NULL,
					`control_zoom` tinyint(1) NOT NULL,
					`control_zoom_position` varchar(45) NOT NULL,
					`control_zoom_style` varchar(45) NOT NULL,
					`control_scale` tinyint(1) NOT NULL,
					`control_overview` tinyint(1) NOT NULL,
					`control_overview_style` tinyint(1) NOT NULL,
					`marker_drop_delay` int(11) NOT NULL,
					`marker_animation` varchar(45) NOT NULL,
					`marker_animation_timer` int(11) NOT NULL,
					`marker_tooltip` tinyint(1) NOT NULL,
					`map_load_zoom` int(11) NOT NULL,
					`marker_click_zoom` int(11) NOT NULL,
					`javascript_callback` tinyint(1) NOT NULL,
					`callback_method` varchar(30) DEFAULT NULL,
					`css_class` varchar(50) NOT NULL,
					`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`deleted` tinyint(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`map_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			dbDelta($sql_create);
			return true;
		}
		
		
		#CREATE MAP MARKERS TABLE
		private function create_map_markers_table(){
			//access globals
			global $wpdb;
			//create unique constraint values
			$contraint_uid = date('Hidmy'); // this is to prevent FK restraints from adding if on a multisite WP install
			//create the hmapspro_map_markers table if it doesn't exist
			$sql_create = "
				CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."hmapspro_map_markers` (
					`map_marker_id` int(11) NOT NULL AUTO_INCREMENT,
					`map_id` int(11) NOT NULL,
					`marker_id` bigint(20) NOT NULL,
					`latlng` varchar(255) NOT NULL,
					`title` varchar(50) NOT NULL,
					`info_window_show` tinyint(1) NOT NULL,
					`info_window_content` longtext,
					`link_show` tinyint(1) NOT NULL,
					`link_title` varchar(20) DEFAULT NULL,
					`link` varchar(255) DEFAULT NULL,
					`link_colour` varchar(7) NOT NULL,
					`link_target` varchar(10) NOT NULL,
					`custom_param` varchar(50) DEFAULT NULL,
					`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`deleted` tinyint(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`map_marker_id`),
					KEY `marker_id_hmapspro_map_markers_FK_idx` (`marker_id`),
					KEY `map_id_hmapspro_map_markers_map_id_FK_idx` (`map_id`),
					CONSTRAINT `marker_id_hmapspro_map_markers_map_id_". $contraint_uid ."_FK` FOREIGN KEY (`marker_id`) REFERENCES `". $wpdb->prefix ."hmapspro_markers` (`marker_id`),
					CONSTRAINT `map_id_hmapspro_map_markers_map_id_". $contraint_uid ."_FK` FOREIGN KEY (`map_id`) REFERENCES `". $wpdb->prefix ."hmapspro_maps` (`map_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			dbDelta($sql_create);
			return true;
		}
		
	}