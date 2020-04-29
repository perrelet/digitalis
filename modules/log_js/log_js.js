(function(ajax){
	
    var throttle = 0;
	
    window.onerror = function(msg, url, line) {
	
        // Return if we've sent more than 10 errors.
        throttle++;
        if (throttle > 10) return;

        // Log the error.
        var req = new XMLHttpRequest();
		
		/* req.onreadystatechange = function() {
			console.log(this.responseText);
		}; */
		
        var params = 'action=js_log_error&msg=' + encodeURIComponent(msg)
			+ '&url=' + encodeURIComponent(url)
			+ "&line=" + line
			+ "&width=" + screen.width
			+ "&height=" + screen.height
			+ "&platform=" + navigator.platform
			+ "&vendor=" + navigator.vendor;
        // Replace spaces with +, browsers expect this for form POSTs.
        params = params.replace(/%20/g, '+');
        req.open( 'POST', ajax.url );
        req.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );

        req.send(params);
		
    };
	
})(admin_ajax_object);