/**
 * simple plugin to simultaneously toggle slide and toggle fade
 * @author martin glaß, glaszig at gmail dot com
 */
(function($) {

	$.fn.toggleFadeSlide = function(speed, fn) {
		
		var aniAttribs = {height: 'toggle', opacity: 'toggle'};
				aniOptions = {};
		
		return this.each(function() {
			
			$(this).animate(aniAttribs, {
				duration: speed || 'normal',
				complete: fn
			});
			
		});
	}

})(jQuery)