function Repslider(options) {
	let me = {
		ready: false,
		options: options,
		index: 0, count: 1, slider: null, timer: null,
		init: function() {
			jQuery(document).ready(function($){
				me.ready = true;
				me.slider = $(me.options.slider);
				me.count = $(me.options.slider + ">div").length;
				if (options.time > 0) me.start();
				$(me.options.left).click(me.prev);
				$(me.options.right).click(me.next);
			});
		},
		prev: function() { me.restart(); me.move(-1); },
		next: function() { me.restart(); me.move( 1); },
		move: function(i) {
			me.index += i;
			if (me.index < 0) me.index = me.count - 1;
			if (me.index > me.count - 1) me.index = 0;
			me.slider.css("transform", "translate(" + -100*me.index + "%, 0px)");
		},
		start: function () {
			me.timer = setInterval(function(){
				me.move(1);
			}, me.options.time * 1000);
		},
		restart: function () {
			clearTimeout(me.timer);
			me.start();
		}
	};
	me.init();
	return me;
}