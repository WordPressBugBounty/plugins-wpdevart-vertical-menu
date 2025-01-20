<?php

class wpda_vertical_menu_admin_panel {
	// previously defined admin constants
	// wpda_vertical_menu_plugin_url
	// wpda_vertical_menu_plugin_path
	private $text_fileds;
	function __construct() {
		$this->admin_filters();
	}

	/*#################### Admin filters function ########################*/

	private function admin_filters() {
		//Hook for admin menu
		add_action('admin_menu', array($this, 'create_admin_menu'));
		add_filter('wp_edit_nav_menu_walker', array($this, 'change_walker_nav_menu_edit'), 99);
		add_filter('plugins_loaded', array($this, 'required_class_for_walker_nav_menu_edit'));
		add_filter('wp_setup_nav_menu_item', array($this, "add_custom_filds_to_calling_nav_menu"));
	}

	/*#################### Function to create the admin menu ########################*/

	public function create_admin_menu() {
		global $submenu;
		/* conect admin pages to wordpress core*/
		$main_page = add_menu_page("Vertical Menu", "Vertical Menu", 'manage_options', "wpda_vertical_menu_themes", array($this, 'create_theme_page'), 'dashicons-list-view');
		$main_page = add_submenu_page("wpda_vertical_menu_themes", "Vertical Menu Theme", "Vertical Menu Theme", 'manage_options', "wpda_vertical_menu_themes", array($this, 'create_theme_page'));
		$featured_plugins = add_submenu_page("wpda_vertical_menu_themes", "Featured Plugins", "Featured Plugins", 'manage_options', "wpda_vertical_menu_featured_plugins", array($this, 'featured_plugins'));
		$featured_themes = add_submenu_page("wpda_vertical_menu_themes", "Featured Themes", "Featured Themes", 'manage_options', "wpda_vertical_menu_featured_themes", array($this, 'featured_themes'));
		$hire_expert = add_submenu_page("wpda_vertical_menu_themes", 'Hire an Expert', '<span style="color:#00ff66" >Hire an Expert</span>', 'manage_options', "wpda_vertical_menu_hire_expert", array($this, 'hire_expert'));
		/*for including page styles and scripts*/
		add_action('admin_print_styles-' . $main_page, array($this, 'create_themes_page_style_js'));
		add_action('admin_print_styles-' . $featured_plugins, array($this, 'featured_plugins_js_css'));
		add_action('admin_print_styles-' . $featured_themes, array($this, 'featured_themes_js_css'));
		add_action('admin_print_styles-' . $hire_expert, array($this, 'create_hire_expert_page_style_js'));
		add_action('admin_print_styles-nav-menus.php', array($this, 'nav_menu_script_styles'));	// include script and style for uploading image every menu item
		if (isset($submenu['wpda_vertical_menu_themes'])){
			add_submenu_page("wpda_vertical_menu_themes", "Support or Any Ideas?", "<span style='color:#00ff66' >Support or Any Ideas?</span>", 'manage_options', "wpdevar_vert_menu_any_ideas", array($this, 'any_ideas'), 156);		
			$count_pages = count($submenu['wpda_vertical_menu_themes']) - 1;
			$submenu['wpda_vertical_menu_themes'][$count_pages][2] = wpda_vertical_menu_support_url;
		}
	}

	public function any_ideas() {
	}

	
	/*#################### Function to create the themes page styles and JS ########################*/

	public function create_themes_page_style_js() {
		//scripts
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-spinner');
		wp_enqueue_script("jquery-ui-date-time-picker-js");
		wp_enqueue_script("jquery-ui-date-time-picker-js");
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('angularejs', wpda_vertical_menu_plugin_url . 'includes/admin/js/angular.min.js');
		//styles
		wp_enqueue_style('jquery-ui');
		wp_enqueue_style('wpda_vertical_menu_theme_page_css', wpda_vertical_menu_plugin_url . 'includes/admin/css/theme_page.css');
		wp_enqueue_script("wpda_contdown_extend_timer_page_js", wpda_vertical_menu_plugin_url . 'includes/admin/js/theme_page.js');
		wp_enqueue_style('jquery-ui-date-time-picker-css');
	}

	public function create_hire_expert_page_style_js() {
		wp_enqueue_style('wpda_vertical_menu_hire_expert_css', wpda_vertical_menu_plugin_url . 'includes/admin/css/hire_expert.css');
	}

	public function featured_plugins_js_css() {
		wp_enqueue_style('wpda_vertical_menu_featured_plugins_css', wpda_vertical_menu_plugin_url . 'includes/admin/css/featured_plugins_css.css');
	}

	public function featured_themes_js_css() {
		wp_enqueue_style('wpda_vertical_featured_themes_page_css', wpda_vertical_menu_plugin_url . 'includes/admin/css/featured_themes_css.css');
	}

	/*#################### Function for navigation menu styles ########################*/

	public function nav_menu_script_styles() {
		//scripts
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpdevart_custom_field', wpda_vertical_menu_plugin_url . 'includes/admin/js/nav_menu.js');
		wp_enqueue_style('wpdevart_custom_field_css', wpda_vertical_menu_plugin_url . 'includes/admin/css/nav_menu.css');
		if (function_exists('wp_enqueue_media')) wp_enqueue_media();
	}

	/*#################### Function to create the theme page ########################*/

	public function create_theme_page() {
		$theme_page_object = new wpda_vertical_menu_theme_page();
		$theme_page_object->controller_page();
	}

	/*###################### Change walker navigation menu function ##################*/

	public function change_walker_nav_menu_edit($walker) {
		if (!class_exists("wpda_walker_nav_menu_extend_for_custom_field")) {
			require_once(wpda_vertical_menu_plugin_path . 'includes/admin/walker_nav_menu_edit_extended.php');
			$walker = "wpda_walker_nav_menu_extend_for_custom_field";
		}
		return $walker;
	}

	/*###################### Walker navigation menu function ##################*/

	public function required_class_for_walker_nav_menu_edit() {
		if (!class_exists("wpdevart_add_to_walker_menu_icon_field")) {
			require_once(wpda_vertical_menu_plugin_path . 'includes/admin/class_for_addon_walker_nav_menu_edit.php');
		}
	}

	/*###################### Add custom fields for menu function ##################*/

	public function add_custom_filds_to_calling_nav_menu($menu_item) {
		$menu_item->menu_icon_url = get_post_meta($menu_item->ID, 'menu-item-menu_icon', true);
		return $menu_item;
	}

	/*############################### Featured plugins function ########################################*/

	public function hire_expert() {
		$plugins_array = array(
			'custom_site_dev' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/1.png',
				'title'			=>	'Custom WordPress Development',
				'description'	=>	'Hire a WordPress expert and make any custom development for your WordPress website.'
			),
			'custom_plug_dev' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/2.png',
				'title'			=>	'WordPress Plugin Development',
				'description'	=>	'Our developers can create any WordPress plugin from zero. Also, they can customize any plugin and add any functionality.'
			),
			'custom_theme_dev' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/3.png',
				'title'			=>	'WordPress Theme Development',
				'description'	=>	'If you need an unique theme or any customizations for a ready theme, then our developers are ready.'
			),
			'custom_theme_inst' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/4.png',
				'title'			=>	'WordPress Theme Installation and Customization',
				'description'	=>	'If you need a theme installation and configuration, then just let us know, our experts configure it.'
			),
			'gen_wp_speed' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/5.png',
				'title'			=>	'General WordPress Support',
				'description'	=>	'Our developers can provide general support. If you have any problem with your website, then our experts are ready to help.'
			),
			'speed_op' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/6.png',
				'title'			=>	'WordPress Speed Optimization',
				'description'	=>	'Hire an expert from WpDevArt and let him take care of your website speed optimization.'
			),
			'mig_serv' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/7.png',
				'title'			=>	'WordPress Migration Services',
				'description'	=>	'Our developers can migrate websites from any platform to WordPress.'
			),
			'page_seo' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/hire_expert/8.png',
				'title'			=>	'WordPress On-Page SEO',
				'description'	=>	'On-page SEO is an important part of any website. Hire an expert and they will organize the on-page SEO for your website.'
			)
		);
		$content = '';

		$content .= '<h1 class="wpdev_hire_exp_h1"> Hire an Expert from WpDevArt </h1>';
		$content .= '<div class="hire_expert_main">';
		foreach ($plugins_array as $key => $plugin) {
			$content .= '<div class="wpdevart_hire_main"><a target="_blank" class="wpdev_hire_buklet" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">';
			$content .= '<div class="wpdevart_hire_image"><img src="' . $plugin["image_url"] . '"></div>';
			$content .= '<div class="wpdevart_hire_information">';
			$content .= '<div class="wpdevart_hire_title">' . $plugin["title"] . '</div>';
			$content .= '<p class="wpdevart_hire_description">' . $plugin["description"] . '</p>';
			$content .= '</div></a></div>';
		}
		$content .= '<div><a target="_blank" class="wpdev_hire_button" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">Hire an Expert</a></div>';
		$content .= '</div>';

		echo $content;
	}
	/*############################### Featured plugins function ########################################*/
	public function featured_plugins() {
		$plugins_array = array(
			'gallery_album' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/gallery-album-icon.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-gallery-plugin',
				'title'			=>	'WordPress Gallery plugin',
				'description'	=>	'Gallery plugin is an useful tool that will help you to create Galleries and Albums. Try our nice Gallery views and awesome animations.'
			),
			'countdown-extended' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/icon-128x128.png',
				'site_url'		=>	'https://wpdevart.com/wordpress-countdown-extended-version/',
				'title'			=>	'WordPress Countdown Extended',
				'description'	=>	'Countdown extended is an fresh and extended version of countdown timer. You can easily create and add countdown timers to your website.'
			),
			'coming_soon' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/coming_soon.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
				'title'			=>	'Coming soon and Maintenance mode',
				'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
			),
			'Contact forms' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/contact_forms.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-contact-form-plugin/',
				'title'			=>	'Contact Form Builder',
				'description'	=>	'Contact Form Builder plugin is an handy tool for creating different types of contact forms on your WordPress websites.'
			),
			'Booking Calendar' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/Booking_calendar_featured.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
				'title'			=>	'WordPress Booking Calendar',
				'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
			),
			'Pricing Table' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/Pricing-table.png',
				'site_url'		=>	'https://wpdevart.com/wordpress-pricing-table-plugin/',
				'title'			=>	'WordPress Pricing Table',
				'description'	=>	'WordPress Pricing Table plugin is a nice tool for creating beautiful pricing tables. Use WpDevArt pricing table themes and create tables just in a few minutes.'
			),
			'chart' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/chart-featured.png',
				'site_url'		=>	'https://wpdevart.com/wordpress-organization-chart-plugin/',
				'title'			=>	'WordPress Organization Chart',
				'description'	=>	'WordPress organization chart plugin is a great tool for adding organizational charts to your WordPress websites.'
			),
			'youtube' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/youtube.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
				'title'			=>	'WordPress YouTube Embed',
				'description'	=>	'YouTube Embed plugin is an convenient tool for adding videos to your website. Use YouTube Embed plugin for adding YouTube videos in posts/pages, widgets.'
			),
			'facebook-comments' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/facebook-comments-icon.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
				'title'			=>	'Wpdevart Social comments',
				'description'	=>	'WordPress Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
			),
			'countdown' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/countdown.jpg',
				'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
				'title'			=>	'WordPress Countdown plugin',
				'description'	=>	'WordPress Countdown plugin is an nice tool for creating countdown timers for your website posts/pages and widgets.'
			),
			'lightbox' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/lightbox.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-lightbox-plugin',
				'title'			=>	'WordPress Lightbox plugin',
				'description'	=>	'WordPress Lightbox Popup is an high customizable and responsive plugin for displaying images and videos in popup.'
			),
			'facebook' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/facebook.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
				'title'			=>	'Social Like Box',
				'description'	=>	'Facebook like box plugin will help you to display Facebook like box on your website, just add Facebook Like box widget to sidebar or insert it into posts/pages and use it.'
			),
			'poll' => array(
				'image_url'		=>	wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_plugins/poll.png',
				'site_url'		=>	'http://wpdevart.com/wordpress-polls-plugin',
				'title'			=>	'WordPress Polls system',
				'description'	=>	'WordPress Polls system is an handy tool for creating polls and survey forms for your visitors. You can use our polls on widgets, posts and pages.'
			),

		);
		$html = '';
		$html .= '<h1 class="wpda_featured_plugins_title">Featured Plugins</h1>';
		foreach ($plugins_array as $plugin) {
			$html .= '<div class="featured_plugin_main">';
			$html .= '<div class="featured_plugin_image"><a target="_blank" href="' . $plugin['site_url'] . '"><img src="' . $plugin['image_url'] . '"></a></div>';
			$html .= '<div class="featured_plugin_information">';
			$html .= '<div class="featured_plugin_title">';
			$html .= '<h4><a target="_blank" href="' . $plugin['site_url'] . '">' . $plugin['title'] . '</a></h4>';
			$html .= '</div>';
			$html .= '<p class="featured_plugin_description">' . $plugin['description'] . '</p>';
			$html .= '<a target="_blank" href="' . $plugin['site_url'] . '" class="blue_button">Check The Plugin</a>';
			$html .= '</div>';
			$html .= '<div style="clear:both"></div>';
			$html .= '</div>';
		}
		echo $html;
	}

	public function featured_themes() {
		$themes_array = array(
			'tistore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/tistore.jpg',
				'site_url' => 'https://wpdevart.com/tistore-best-ecommerce-theme-for-wordpress/',
				'title' => 'TiStore',
				'description' => 'TiStore is one of the best eCommerce WordPress themes that is fully integrated with WooCommerce.',
			),
			'megastore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/megastore.jpg',
				'site_url' => 'https://wpdevart.com/megastore-best-woocommerce-theme-for-wordpress/',
				'title' => 'MegaStore',
				'description' => 'MegaStore is one of the best WooCommerce themes available for WordPress.',
			),
			'jevstore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/jevstore.jpg',
				'site_url' => 'https://wpdevart.com/jewstore-best-wordpress-jewelry-store-theme/',
				'title' => 'JewStore',
				'description' => 'JewStore is a WordPress WooCommerce theme designed for jewelry stores and blogs.',
			),
			'cakeshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/cakeshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-cake-shop-theme/',
				'title' => 'Cake Shop',
				'description' => 'WordPress Cake Shop is a multi-purpose WooCommerce-ready theme.',
			),
			'flowershop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/flowershop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-flower-shop-theme/',
				'title' => 'Flower Shop',
				'description' => 'WordPress Flower Shop is a responsive and WooCommerce-ready theme developed by our team.',
			),
			'coffeeshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/coffeeshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-coffee-shop-cafe-theme/',
				'title' => 'Coffee Shop',
				'description' => 'It is a responsive and user-friendly theme designed specifically for coffee shop or cafe websites.',
			),
			'weddingplanner' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/weddingplanner.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-wedding-planner-theme/',
				'title' => 'Wedding Planner',
				'description' => 'Wedding Planner is a responsive WordPress theme that is fully integrated with WooCommerce.',
			),
			'Amberd' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/Amberd.jpg',
				'site_url' => 'https://wpdevart.com/amberd-wordpress-online-store-theme/',
				'title' => 'AmBerd',
				'description' => 'AmBerd has all the necessary features and functionality to create a beautiful WordPress website.',
			),
			'bookshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/bookshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-book-shop-theme/',
				'title' => 'Book Shop',
				'description' => 'The Book Shop WordPress theme is a fresh and well-designed theme for creating bookstores or book blogs.',
			),
			'ecommercemodernstore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/ecommercemodernstore.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-ecommerce-modern-store-theme/',
				'title' => 'Ecommerce Modern Store',
				'description' => 'WordPress Ecommerce Modern Store theme is one of the best solutions if you want to create an online store.',
			),
			'electrostore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/electrostore.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-electronics-store-electro-theme/',
				'title' => 'ElectroStore',
				'description' => 'This is a responsive and WooCommerce-ready electronic store theme.',
			),
			'jewelryshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/jewelryshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-jewelry-shop-theme/',
				'title' => 'Jewelry Shop',
				'description' => 'WordPress Jewelry Shop theme is designed specifically for jewelry websites, but of course, you can use this theme for other types of websites as well.',
			),
			'fashionshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/fashionshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-fashion-shop-theme/',
				'title' => 'Fashion Shop',
				'description' => 'The Fashion Shop is one of the best responsive WordPress WooCommerce themes for creating a fashion store website.',
			),
			'barbershop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/barbershop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-barbershop-theme/',
				'title' => 'Barbershop',
				'description' => 'WordPress Barbershop is another responsive and functional theme developed by our team.',
			),
			'furniturestore' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/furniturestore.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-furniture-store-theme/',
				'title' => 'Furniture Store',
				'description' => 'This is a great option to quickly create an online store using our theme and the WooCommerce plugin. Our theme is fully integrated with WooCommerce.',
			),
			'clothing' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/clothing.jpg',
				'site_url' => 'https://wpdevart.com/tistore-best-ecommerce-theme-for-wordpress/',
				'title' => 'Clothing',
				'description' => 'The Clothing WordPress theme is one of the best responsive eCommerce themes available for WordPress.',
			),
			'weddingphotography' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/weddingphotography.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-wedding-photography-theme/',
				'title' => 'Wedding Photography',
				'description' => 'WordPress Wedding Photography theme is one of the best themes specially designed for wedding photographers or photography companies.',
			),
			'petshop' => array(
				'image_url' => wpda_vertical_menu_plugin_url . 'includes/admin/images/featured_themes/petshop.jpg',
				'site_url' => 'https://wpdevart.com/wordpress-pet-shop-theme/',
				'title' => 'Pet Shop',
				'description' => 'Pet Shop is a powerful and well-designed WooCommerce WordPress theme.',
			),

		);
		$html = '';
		$html .= '<div class="wpdevart_main"><h1 class="wpda_featured_themes_title">Featured Themes</h1>';

		$html .= '<div class="div-container">';
		foreach ($themes_array as $theme) {
			$html .= '<div class="theme" data-slug="tistore"><div class="theme-img">';
			$html .= ' <img src="' . $theme['image_url'] . '" alt="' . $theme['title'] . '">';
			$html .= '</div>';
			$html .= '<div class="theme-description">' . $theme['description'] . '</div>';
			$html .= '<div class="theme-name-container">';
			$html .= '<h2 class="theme-name">' . $theme['title'] . '</h2>';
			$html .= '<div class="theme-actions">';
			$html .= '<a target="_blank" aria-label="Check theme" class="button button-primary load-customize" href="' . $theme['site_url'] . '">Check Theme</a>';
			$html .= '</div></div></div>';
		}
		$html .= '</div></div>';
		echo $html;
	}
}
?>