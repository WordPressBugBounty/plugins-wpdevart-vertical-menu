<?php
//Installing the database
class wpda_vertical_menu_databese{
	public static $table_names;
	public static $popup_settings;
	function __construct(){
		global $wpdb;
		self::$table_names=array(
			'theme'=>$wpdb->prefix.'wpda_vertical_menu_theme'
		);
	}

             /*#################### Fonction for the Theme table ########################*/
	
	public function install_theme_tabel(){
		global $wpdb;
		//Install vertical menu theme database
		$table_name =  self::$table_names['theme'];	
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		  `name` varchar(512) NOT NULL,
		  `option_value` longtext NOT NULL,
		  `default` tinyint(4) NOT NULL,
			UNIQUE KEY id (id)		
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );	
	}	
} 
?>