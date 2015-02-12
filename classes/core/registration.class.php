<?php

	#PLUGIN REGISTRATION MANAGEMENT (ajax)
	class hmapspro_registration{
		
		#CLASS VARS
		private $plugin_prefix;
		public $backend;
		public $frontend;
		
		#CONSTRUCT
		public function __construct($plugin_prefix,$backend,$frontend){
			//define class vars
			$this->plugin_prefix = $plugin_prefix;
			$this->backend = $backend;
			$this->frontend = $frontend;
			//register ajax hooks
			$this->register_backend_ajax_calls();
			$this->register_frontend_ajax_calls();
		}
		
		#REGISTER ADMIN AJAX CALLS
		private function register_backend_ajax_calls(){
			//reference global
			global $backend_ajax_calls;
			//construct hooks
			if(isset($backend_ajax_calls) && count($backend_ajax_calls) > 0){
				foreach($backend_ajax_calls as $call){
					add_action('wp_ajax_'. $this->plugin_prefix . $call['action'], array(&$this->backend, $call['method']));
				}
			}
		}
		
		#REGISTER USER AJAX CALLS
		private function register_frontend_ajax_calls(){
			//reference global
			global $frontend_ajax_calls;
			//construct hooks
			if(isset($frontend_ajax_calls) && count($frontend_ajax_calls) > 0){
				foreach($frontend_ajax_calls as $call){
					add_action('wp_ajax_'. $this->plugin_prefix . $call['action'], array(&$this->frontend, $call['method']));
					add_action('wp_ajax_nopriv_'. $this->plugin_prefix . $call['action'], array(&$this->frontend, $call['method']));
				}
			}
		}
		
	}