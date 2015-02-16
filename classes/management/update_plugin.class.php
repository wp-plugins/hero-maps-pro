<?php
	
	#UPDATE PLUGIN
	class hmapspro_update_plugin{
		
		#CLASS VARS
		private $plugin_name;
		private $plugin_version;
		private $plugin_old_version;
		
		#CONSTRUCT
		public function __construct($plugin_name,$plugin_version,$plugin_old_version){
			//set class vars
			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_old_version = $plugin_old_version;
		}
		
		#UPDATE PLUGIN
		public function update_plugin(){
			//access globals
			global $wpdb;
			//update plugin tables
			/*
				note: no table mods have been applied to the plugin
			*/
			//mark the upgrade as successful
			$this->mark_update_complete();
		}
		
		#MARK UPDATE COMPLETE
		private function mark_update_complete(){
			//access globals
			global $wpdb;
			//once updates are complete, mark the plugin version in the DB
			$wpdb->query("UPDATE `". $wpdb->prefix ."hplugin_root` SET `plugin_version` = '". $this->plugin_version ."' WHERE `plugin_name` = '". $this->plugin_name ."';");
		}
		
	}