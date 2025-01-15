<?php
//Installing the database
class wpda_vertical_menu_databese {
	public static $table_names;
	public static $popup_settings;
	function __construct() {
		global $wpdb;
		self::$table_names = array(
			'theme' => $wpdb->prefix . 'wpda_vertical_menu_theme'
		);
	}

	/*#################### Fonction for the Theme table ########################*/

	public function install_theme_tabel() {
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
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public function install_default_theme() {
		global $wpdb;
		$isset_theme = $wpdb->get_col('SELECT `id` FROM ' . self::$table_names['theme'] . ' ORDER BY `id` ASC');
		$isset_defoult_theme = $wpdb->get_var("SELECT `id` FROM " . self::$table_names['theme'] . " WHERE `default`=1");
		$default = 0;
		if (!$isset_defoult_theme) {
			$default = 1;
		}
		if ($isset_theme == null) {
			$theme_value = '{"name":"Default","open_menu_on":"click","open_duration":"400","click_image_action":"open_submenu","submenu_opened_type":"when_subemnu_item_active","menu_clickable_area":"only_arrow","menu_link_area":"only_text","padding":{"top":"5","right":"10","bottom":"5","left":"5"},"image_sizes":{"width":"35","height":"35"},"image_margin":{"top":"0","right":"10","bottom":"0","left":"5"},"background_color":{"color1":"","color2":"","gradient":"none"},"font_size":"18","font_family":"Arial,Helvetica Neue,Helvetica,sans-serif","font_style":"normal","text_color":"#4e4e4e","background_color_hover":{"color1":"#eaeaea","color2":"","gradient":"none"},"font_size_hover":"18","font_family_hover":"Arial,Helvetica Neue,Helvetica,sans-serif","font_style_hover":"normal","text_color_hover":"#000000","background_color_active":{"color1":"","color2":"","gradient":"none"},"font_size_active":"18","font_family_active":"Arial,Helvetica Neue,Helvetica,sans-serif","font_style_active":"normal","text_color_active":"#4e4e4e","border_width":{"top":"0","right":"0","bottom":"1","left":"0"},"border_color":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"border_color_hover":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"border_color_active":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"show_menu_last_border":"hide","open_icon":"fas fa-angle-right","close_icon":"fas fa-angle-down","open_icon_color":"#8a8a8a","open_icon_color_hover":"#2d2d2d","close_icon_color":"#8a8a8a","close_icon_color_hover":"#2d2d2d","icon_size":"22","open_icon_padding":{"top":"0","right":"0","bottom":"0","left":"0"},"close_icon_padding":{"top":"0","right":"0","bottom":"1","left":"0"},"submenu_padding":{"top":"4","right":"0","bottom":"4","left":"12"},"submenu_background_color":{"color1":"#ffffff","color2":"","gradient":"none"},"submenu_font_size":"16","submenu_font_family":"Arial,Helvetica Neue,Helvetica,sans-serif","submenu_font_style":"normal","submenu_text_color":"#4e4e4e","submenu_background_color_hover":{"color1":"#f4f4f4","color2":"","gradient":"none"},"submenu_font_size_hover":"16","submenu_font_family_hover":"Arial,Helvetica Neue,Helvetica,sans-serif","submenu_font_style_hover":"normal","submenu_text_color_hover":"#000000","submenu_background_color_active":{"color1":"#ffffff","color2":"","gradient":"none"},"submenu_font_size_active":"16","submenu_font_family_active":"Arial,Helvetica Neue,Helvetica,sans-serif","submenu_font_style_active":"normal","submenu_text_color_active":"#000000","submenu_border_width":{"top":"0","right":"0","bottom":"1","left":"0"},"submenu_border_color":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"submenu_border_color_hover":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"submenu_border_color_active":{"top":"#ffffff","right":"#ffffff","bottom":"#d8d8d8","left":"#ffffff"},"show_submenu_last_border":"hide"}';
			$theme_name = 'Default';
			$wpdb->insert(
				self::$table_names['theme'],
				array(
					'id' => 50,
					'name' => $theme_name,
					'option_value' => $theme_value,
					'default' => $default,
				),
				array(
					'%d',
					'%s',
					'%s',
					'%d',
				)
			);
		}
	}
}
