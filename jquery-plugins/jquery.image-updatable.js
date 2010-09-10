(function($) {

	$.fn.updatable = function(config) {
	
		config = $.extend({
			debug: false,
			period: 3000,
			queryKey: '_jq-update',
			queryValue: function() {
				return new Date().getTime();
			},
			loadingClass: 'jq-updatable-loading',
			onBeforeLoad: function() {
				$(this).addClass(config.loadingClass);
			},
			onAfterLoad: function() {
				$(this).removeClass(config.loadingClass);
			}
		}, config);
		
		var log = function() {
			if(config.debug && console && console.log) {
				console.log.apply(console.log, arguments);
			}
		}
		
		var periodicalUpdater = function(imgElement, period) {
			var myself = arguments.callee;
			setTimeout(function() {
				config.onBeforeLoad.call(imgElement);
				// todo: update image src url
				log('old src: '+imgElement.attr('src'));
				var oldSrc = imgElement.attr('src').split('?');
				var qs = oldSrc[1] || ''; //config.queryKey+'=now';
				// clean up
				qs = qs.replace(new RegExp(config.queryKey+'=[^&]*&?', 'ig'), '')+'&';
				// set src to initiate reload
				imgElement.attr('src', oldSrc[0]+'?'+qs+config.queryKey+'='+config.queryValue());
				log('new src: '+imgElement.attr('src'));
				// repeating...
				myself.call(myself, imgElement, period);
			}, period);
		};
		
		return this.each(function() {
			
			var img = $(this);
			
			img.load(function() {
				config.onAfterLoad.call($(this));
			});
			
			// update periodical
			if(config.period) {
				periodicalUpdater(img, config.period*1000);
			}
			
		});
	};

})(jQuery);
/**
 * usage example
 */
/*
$('img').updatable({
	debug: true,
	period:3000
});
*/
