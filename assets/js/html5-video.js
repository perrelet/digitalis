(function(params) {
	document.addEventListener("DOMContentLoaded", function() {
		var cover = (params['cover'] == "" ? false : true), videos = document.querySelectorAll('video'), i;
		nextPromise(0);
		function nextPromise(i) {
			if (i < videos.length) {
				var promise = videos[i].play();
				if (cover) var ele_cover = videos[i].nextSibling;	
				if (promise !== undefined) {
					promise.then(function(values) {
						if (cover) ele_cover.style.opacity = 0;
					}).catch(function(error) {
						console.log(error);
					});
				}
				nextPromise(i + 1);
			}
		}
	});
})(html5_video_params);