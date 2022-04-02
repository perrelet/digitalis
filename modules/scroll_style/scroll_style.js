(function(params){

	/* document.addEventListener("DOMContentLoaded", function() { */
		
		var at_top = true;
		var scroll_y_prev = window.pageYOffset || document.documentElement.scrollTop;
		var delta_prev = 0;
		
		var scroll_style = throttle(function() {
			
			var scroll_y = window.pageYOffset || document.documentElement.scrollTop;
			var delta = scroll_y_prev - scroll_y;
			
			if ((scroll_y <= params['offset']) != at_top) {
				
				at_top = (scroll_y <= params['offset']);
				
				if (at_top) {
					document.body.classList.remove("scrolled");
				} else {
					document.body.classList.add("scrolled");
				}
				
			}
			
			if (scroll_y > 0 && Math.abs(delta) > 0) { // Required for mobile browsers that 'bounce' scroll at top (safari)
				
				if (Math.sign(delta) != Math.sign(delta_prev)) {
					
					if (delta > 0) {
						document.body.classList.add("scroll-up");
						document.body.classList.remove("scroll-down");
					} else {
						document.body.classList.add("scroll-down");
						document.body.classList.remove("scroll-up");
					}
				}
				
				scroll_y_prev = scroll_y;
				delta_prev = delta;
			
			}
			
		}, 200);
		
		window.addEventListener('scroll', scroll_style);
		
	/* }); */
	
})(scroll_style_params);