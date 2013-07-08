/*
 * Site JS
 */

 (function($) {

 	/*
 	 * Change dropdown hover position based on screen offset
 	 */

 	 $(document).ready(fixDropdown);
 	 $(window).scroll(fixDropdown);

 	 function fixDropdown() {

 	 	var scrollTop = $('body').scrollTop();
 	 	var windowHeight = $(window).height();

 	 	$('.dropdown').each(function() {

 	 		var topOffset = $(this).offset().top;
 	 		var height = $(this).height() + $(this).find('.list').height();

 	 		var windowPosition = (topOffset + height) - scrollTop - windowHeight;

 	 		$(this).removeClass('go-up');
 	 		$(this).removeClass('go-down');

 	 		if(windowPosition < 0) {
 	 			$(this).addClass('go-down');
 	 		} else {
 	 			$(this).addClass('go-up');
 	 		}

 	 	});

 	 }

 })(jQuery);