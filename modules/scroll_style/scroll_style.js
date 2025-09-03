(function (params) {
    // params: { offset: number | (() => number), hysteresis?: number }
    let atTop = true;
    let lastY = window.pageYOffset || document.documentElement.scrollTop;
    let lastDelta = 0;
    let ticking = false;

    const H = Number(Math.max(0, params.hysteresis ?? 0)); // px buffer to prevent thrash
    const getOffset = typeof params.offset === 'function'
        ? params.offset
        : () => params.offset || 0;

    function applyState(y) {

        const delta = lastY - y;
        const offset = Number(getOffset());

        if (atTop) {
            if (y > offset + H) {
                atTop = false;
                document.body.classList.add('scrolled');
            }
        } else {
            if (y <= offset - H) {
                atTop = true;
                document.body.classList.remove('scrolled');
            }
        }

        if (y > 0 && Math.abs(delta) > 0) {
            if (Math.sign(delta) !== Math.sign(lastDelta)) {
                if (delta > 0) {
                    document.body.classList.add('scroll-up');
                    document.body.classList.remove('scroll-down');
                } else {
                    document.body.classList.add('scroll-down');
                    document.body.classList.remove('scroll-up');
                }
            }
            lastDelta = delta;
        }

        lastY = y;

    }

    function onScroll() {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                ticking = false;
                const y = window.pageYOffset || document.documentElement.scrollTop;
                applyState(y);
            });
        }
    }

    requestAnimationFrame(() => {
        const y = window.pageYOffset || document.documentElement.scrollTop;
        lastDelta = 0;
        applyState(y);
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

})(scroll_style_params);
