<?php 

	#PLUGIN INFORMATION
	/*
		Plugin Name: Hero Maps Pro
		Plugin URI: http://www.heroplugins.com
		Description: Easily create your own Google Maps with a simple drag and drop interface
		Version: 2.0.7
		Author: Hero Plugins
		Author URI: http://www.heroplugins.com
		License: GPLv2 or later
	*/
	
	#LICENSE INFORMATION
	/*  
		Copyright 2015  Hero Plugins (email : info@heroplugins.com)
	
		This program is free software; you can redistribute it and/or
		modify it under the terms of the GNU General Public License
		as published by the Free Software Foundation; either version 2
		of the License, or (at your option) any later version.
		
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
		GNU General Public License for more details.
		
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/
	
	#PLUGIN INCLUDES
	require_once('classes/helper/check.helper.php');
	require_once('classes/management/activate_plugin.class.php');
	require_once('classes/management/update_plugin.class.php');
	require_once('classes/management/deactivate_plugin.class.php');
	require_once('classes/core/plugin_setup.class.php');
	require_once('classes/core/display.class.php');
	require_once('classes/core/shortcode.class.php');
	require_once('classes/core/registration.class.php');
	require_once('classes/core/auto_generate.class.php');
	require_once('classes/core/frame_sec.class.php');
	require_once('classes/marker_processor.class.php');
	require_once('classes/backend.class.php');
	require_once('classes/frontend.class.php');
	require_once('inc/ajax.calls.php');
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	#DEFINE HELPER CLASS POINTER
	$hmapspro_helper;
	
	#PLUGIN ROOT
	class heroplugin_hmapspro{

		#PLUGIN CONFIG
		private $plugin_name = 'hmapspro';
		private $plugin_dir_name = 'hero-maps-pro';
		private $plugin_friendly_name = 'Hero Maps Pro';
		private $plugin_friendly_description = 'Easily create your own Google Maps with a simple drag and drop interface';
		private $plugin_version = '2.0.7';
		private $plugin_prefix = 'hmapspro_';
		private $first_release = '2014-11-24';
		private $last_update = '2015-02-25';
		private $api_version = '2.0.1';
		
		#CLASS VARS
		private $plugin_dir;
		private $plugin_url;
		private $plugin_basename;
		private $plugin_old_version;
		private $plugin_uuid;

		#CONSTRUCT
		public function __construct(){

			//define plugin vars
			$this->plugin_dir = dirname(__FILE__);
			$this->plugin_basename = plugin_basename(__FILE__);
			$this->plugin_url = plugins_url($this->plugin_dir_name) .'/';
			
			//instantiate helper class
			global $hmapspro_helper;
			$hmapspro_helper = new hmapspro_helper($this->plugin_prefix);
			
			//register management hooks
			register_activation_hook(__FILE__,array(new hmapspro_activate($this->plugin_name, $this->plugin_version), 'setup_plugin')); //activate
			register_deactivation_hook(__FILE__,array(new hmapspro_deactivate($this->plugin_name), 'teardown_plugin')); //deactivate
			
			//detect if update required
			global $wpdb;
			if($this->plugin_old_version == NULL && $hmapspro_helper->onAdmin()){ //only make the DB call if required
				$plugin_lookup = $wpdb->get_results("SELECT * FROM `". $wpdb->prefix ."hplugin_root` WHERE `plugin_name` = '". $this->plugin_name ."';");
				if($plugin_lookup){
					$this->plugin_old_version = $plugin_lookup[0]->plugin_version;
					$this->plugin_uuid = $plugin_lookup[0]->plugin_uuid; //define plugin uuid for check-in
				}
				if(version_compare($this->plugin_old_version,$this->plugin_version,'<')){
					$update = new hmapspro_update_plugin($this->plugin_name,$this->plugin_version,$this->plugin_old_version);
					$update->update_plugin();
				}
			}

			//instantiate plugin setup
			new hmapspro_setup($this->plugin_name,$this->plugin_dir,$this->plugin_url,$this->plugin_friendly_name,$this->plugin_version,$this->plugin_prefix,$this->first_release, $this->last_update, $this->plugin_friendly_description);
			
			//instantiate admin class
			$backend = new hmapspro_backend(); //this instance can be used by WP for ajax implementations
			
			//instantiate front-end class
			$frontend = new hmapspro_frontend(); //this instance can be used by WP for ajax implementations
			
			//instantiate the frame security class
			$frame_sec = new hmapspro_frame_sec($this->plugin_dir);
			
			//instantiate the marker pack processor
			$marker_processor = new hmapspro_marker_processor($this->plugin_dir);
			
			//bind admin ajax listeners
			add_action('wp_ajax_hmapspro_get_security_code', array(&$frame_sec, 'get_security_code')); //admin: get frame security code
			add_action('wp_ajax_hmapspro_process_marker_packs', array(&$marker_processor, 'process_marker_packs')); //admin: process marker packs
			add_action('wp_ajax_hmapspro_process_custom_markers', array(&$marker_processor, 'process_custom_markers')); //admin: process custom markers
			
			//instantiate registrations class (register all plugin-related ajax hooks)
			new hmapspro_registration($this->plugin_prefix, $backend, $frontend);
			
			//configure auto-generation class and hooks (used for development purposes)
			$autogenerate = new hmapspro_autogenerate($this->plugin_dir);
			add_action('wp_ajax_hmapspro_autoGenerateViews', array(&$autogenerate, 'create_views')); //admin: auto-generate views
			
		}
		
	}
	
	#INITIALISE THE PLUGIN CODE WHEN WP INITIALISES
	new heroplugin_hmapspro();