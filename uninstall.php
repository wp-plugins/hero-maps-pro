<?php

	#UNINSTALL PLUGIN
	if(!defined('WP_UNINSTALL_PLUGIN')){
		exit();
	}
	
	//access globals
	global $wpdb;
	
	//flag deleted
	$wpdb->query("UPDATE `". $wpdb->prefix ."hplugin_root` SET `deleted` = 1 WHERE `plugin_name` = 'hmapspro';"); //flag deleted
	
	//clean up
	$wpdb->query("DROP TABLE IF EXISTS `". $wpdb->prefix ."hmapspro_map_markers`;"); //map markers
	$wpdb->query("DROP TABLE IF EXISTS `". $wpdb->prefix ."hmapspro_maps`;"); //maps
	$wpdb->query("DROP TABLE IF EXISTS `". $wpdb->prefix ."hmapspro_markers`;"); //markers
	$wpdb->query("DROP TABLE IF EXISTS `". $wpdb->prefix ."hmapspro_marker_categories`;"); //marker categories