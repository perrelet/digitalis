(function(params){

    const loaded = () => {
        document.body.classList.add('loaded');
        setTimeout(() => {
            if (params['exit']) {
                document.querySelector(`#page_loader`).style.display = 'none';
            } else {
                document.querySelector(`#page_loader`).remove();
            }
        }, params['entry_speed'] * 1000);
    };

    const readystatechange = (e) => {
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            document.removeEventListener("readystatechange", readystatechange);
            loaded();
        }
    }

    const onbeforeunload = (e) => {
        document.body.classList.add('unloaded')
        document.querySelector(`#page_loader`).style.display = 'flex';
        document.dispatchEvent(new CustomEvent('Digitalis/Module/Page_Loader/Unload', {detail: {}}));
    }

    if (params['entry']) (document.readyState === 'complete') ? loaded() : document.addEventListener("readystatechange", readystatechange);
    if (params['exit'])  window.onbeforeunload = onbeforeunload;
  
})(page_loader_params[0]);