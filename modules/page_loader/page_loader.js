(function(params){
let domReady = (cb) => {
  document.readyState === 'interactive' || document.readyState === 'complete' ? cb() : document.addEventListener('DOMContentLoaded', cb);
};
domReady(() => {
  document.body.classList.add('loaded');
  setTimeout(function(){ document.getElementById('page_loader').remove(); }, params['transition_speed'] * 1000);
});
})(page_loader_params);