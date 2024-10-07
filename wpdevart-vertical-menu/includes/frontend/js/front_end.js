/*standart countdown protytype_script*/
(function($){
	/// jquery animation for elements	
	$.fn.wpdevart_vertical_menu = function(resive_options) {
		var element = $(this);		
		options={
			open_menu_on:"click",
			open_duration:"400",
			click_image_action:"go_to_link",
			clickable_area:"only_arrow"
		}
		for (var i in resive_options) if (resive_options.hasOwnProperty(i) && options.hasOwnProperty(i)) options[i] = resive_options[i];
		initial();
		function initial(){
			open_close();
		}
		
		function open_close(){
			
			
			switch(options["open_menu_on"]){
				case "hover":
					element.children().hover(function(){						
						$(this).find(".wpdevart_submenu").stop().slideDown(parseInt(options["open_duration"]),function(){
							$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_active")
							$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_hidden")
							
							$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_hidden")
							$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_active")
							
						});						
					},
					function(){
						$(this).find(".wpdevart_submenu").stop().slideUp(parseInt(options["open_duration"]),function(){
							$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_hidden")
							$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_active")
							
							$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_active")
							$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_hidden")
						});
					})
				break;	
				case "click":
					element.find(".wpdevart_menu_link_conteiner a").click(function(e){
						if(options["clickable_area"]=="text_and_arrow_arrow" && jQuery(this).parent().find('.wpdevart_open_icon').length>=1){
							jQuery(this).parent().parent().find(".wpdevart_submenu").stop().slideToggle(parseInt(options["open_duration"]),function() {
								if ($(this).is(':hidden')) {
									$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_hidden")
									$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_active")
									
									$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_active")
									$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_hidden")
									
								} else {
									
									$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_active")
									$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_hidden")
									
									$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_hidden")
									$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_active")
								}							
							})
							return false;						
						}
						e.stopImmediatePropagation();
					})
					element.children().click(function(){
							
						$(this).find(".wpdevart_submenu").stop().slideToggle(parseInt(options["open_duration"]),function() {
							if ($(this).is(':hidden')) {
								$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_hidden")
								$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_active")
								
								$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_active")
								$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_hidden")
								
							} else {
								
								$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_active")
								$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_hidden")
								
								$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_hidden")
								$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_active")
							}							
						})						
					});
				break;	
			}
			if(options["clickable_area"]=="text_and_arrow_arrow"){
				element.find(".wpdevart_menu_link_conteiner a").click(function(e){
					if(jQuery(this).parent().find('.wpdevart_open_icon').length>=1){
						jQuery(this).parent().find(".wpdevart_submenu").stop().slideToggle(parseInt(options["open_duration"]),function() {
								if ($(this).is(':hidden')) {
									$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_hidden")
									$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_active")
									
									$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_active")
									$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_hidden")
									
								} else {
									
									$(this).parent().find(".wpdevart_open_icon").removeClass("wpdevart_active")
									$(this).parent().find(".wpdevart_close_icon").removeClass("wpdevart_hidden")
									
									$(this).parent().find(".wpdevart_open_icon").addClass("wpdevart_hidden")
									$(this).parent().find(".wpdevart_close_icon").addClass("wpdevart_active")
								}							
							})
							return false;
					}
				})
			}
			
		}
		function isScrolledIntoView(){
			var $window = $(window);
			var docViewTop = $window.scrollTop();
			var docViewBottom = docViewTop + $window.height();
			var elemTop = element.offset().top;
			var elemBottom = elemTop + parseInt(element.css('height'));			
			return ( ( (docViewTop<=elemTop+5) && (elemTop-5<=docViewBottom) )  || ( (docViewTop<=elemBottom+5) && (elemBottom-5<=docViewBottom) ) || (docViewTop==0 && docViewBottom==0) || $window.height()==0);
		}
	}

})(jQuery)
	