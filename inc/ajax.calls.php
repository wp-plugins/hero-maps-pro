<?php

	#ADMIN AJAX CALLS
	$backend_ajax_calls = array(
		//plugin ajax calls
		array('action' => 'generate_new_map', 'method' => 'generate_new_map'), //generate a new map
		array('action' => 'get_custom_markers', 'method' => 'get_custom_markers'), //get custom markers
		array('action' => 'update_custom_marker_offset', 'method' => 'update_custom_marker_offset'), //update custom marker offset(s)
		array('action' => 'remove_custom_marker', 'method' => 'remove_custom_marker'), //remove custom marker
		array('action' => 'get_markers', 'method' => 'get_markers'), //get markers
		array('action' => 'get_all_maps', 'method' => 'get_all_maps'), //get maps
		array('action' => 'delete_map', 'method' => 'delete_map'), //delete map
		array('action' => 'get_map_data', 'method' => 'get_map_data'), //get map data
		array('action' => 'update_map_object', 'method' => 'update_map_object') //update map
	);
	
	#USER AJAX CALLS
	$frontend_ajax_calls = array(); //not currently required