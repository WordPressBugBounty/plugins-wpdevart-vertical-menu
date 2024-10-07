<?php	 
class wpdevart_vertical_menu_frontend{
// previous defined admin constants
// wpda_vertical_menu_plugin_url
// wpda_vertical_menu_plugin_path
	function __construct(){	
		$this->call_filters();
	}
	
             /*#################### Function for calling the filters ########################*/		
	
	private function call_filters(){			
		add_filter('wp_head',array($this,'include_scripts'),0);			
	}
	
             /*#################### Function for including the scripts ########################*/
	
	public function include_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpdevart_vertical_menu_js',wpda_vertical_menu_plugin_url.'includes/frontend/js/front_end.js');
		wp_enqueue_style('FontAwesome_5');		
		wp_enqueue_style('wpdevart_vertical_menu_front',wpda_vertical_menu_plugin_url.'includes/frontend/css/front_end.css');
	}	
}






?>