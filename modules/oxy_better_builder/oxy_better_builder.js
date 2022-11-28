jQuery(window).load(function () {  

    console.log("better builder");

    setTimeout(function () {

        function debounce(callback, delay) {

            var timeout;
            return function () {
                var context = this;
                var args = arguments;
                if (timeout) {
                    clearTimeout(timeout);
                }
                timeout = setTimeout(function () {
                    timeout = null;
                    callback.apply(context, args);
                }, delay);
            }

        }

        function reload_styles () {

            var links = document.getElementsByTagName("link");
            console.log('reloading stylesheets...');

            for (var cl in links) {

                var link = links[cl];
                if (link.rel === "stylesheet") link.href += "";

            }

        } 

        function keydown_handler (event) {

            if (event.originalEvent.repeat) return;                         // Stop event processing if it is repeating
            if (!event.ctrlKey && !event.metaKey) return;                   // Stop event processing if Control or Command keys are inactive
            if (event.target.nodeName != 'BODY') return;                    // Stop event processing if it's target is not the body element
            //if ($parentScope.isActiveActionTab('contentEditing')) return;   // Stop event processing if content editor is active

            var processed = false;
            var key = event.key.toLowerCase();

            switch (key) {

                case " ":
                    reload_styles();
                    processed = true;

            }

            if (processed) {
                event.stopImmediatePropagation();
                event.preventDefault();
            }

        }

        var keydown_callback = debounce(keydown_handler, 250);

        angular.element('body').on('keydown', keydown_callback);            // iframe
        parent.angular.element('body').on('keydown', keydown_callback);     // builder

    }, 1000); // We need this to be able to override.

});