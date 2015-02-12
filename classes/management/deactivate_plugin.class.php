<?php
	
	#DEACTIVATE PLUGIN
	class hmapspro_deactivate{
		
		#CLASS VARS
		private $plugin_name;
		
		#CONSTRUCT
		public function __construct($plugin_name){
			//set class vars
			$this->plugin_name = $plugin_name;
		}
		
		#TEARDOWN PLUGIN
		public function teardown_plugin(){
			//access globals
			global $wpdb;
			//deactivate plugin
			/*
				note: There is currently no requirement to take action when the plugin is disabled
			*/
			//mark plugin as inactive
			$wpdb->query("UPDATE `". $wpdb->prefix ."hplugin_root` SET `active` = 0 WHERE `plugin_name` = '". $this->plugin_name ."';");
		}
		
	}