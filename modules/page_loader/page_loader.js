(function(params){
  
    const loaded = () => {
      document.body.classList.add('loaded');
      setTimeout(function(){ document.getElementById('page_loader').remove(); }, params['transition_speed'] * 1000);
    };

    const readystatechange = (event) => {
      if (document.readyState === 'interactive' || document.readyState === 'complete') {
        document.removeEventListener("readystatechange", readystatechange);
        loaded();
      }
    }

    (document.readyState === 'complete') ? loaded() : document.addEventListener("readystatechange", readystatechange);
  
  })(page_loader_params);