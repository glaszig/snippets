/**
 * periodical image updater plugin
 *
 * To reload an image every 10 seconds:
 *
 * $('img.updatable').updatable({period:10});
 *
 * For more options have a look at the config hash.
 *
 * Copyright (c) http://github.com/glaszig
 */
(function($) {

	$.fn.updatable = function(config) {
	
		var config = $.extend({
			debug: false,
			period: 30,
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
		
		var parseQueryString = function(str) {
		  var obj = {};
		  if (str.length) {
		    $.each(str.split('&'), function(k, v) {
		      var p = v.split('=');
		      obj[p[0]] = p[1] || null;
		    });
		  }
		  return obj;
		}
		
		var makeQueryString = function(obj) {
		  var str = [];
		  $.each(obj, function(k, v) {
		    str.push(v == null ? k : k+'='+v);
		  });
		  return str.join('&');
		}
		
		var periodicalUpdater = function(imgElement, period) {
			setInterval(function() {
				config.onBeforeLoad.call(imgElement);
				// todo: update image src url
				log('old src: '+imgElement.attr('src'));
				var oldSrc = imgElement.attr('src').split('?');
				var qs = parseQueryString(oldSrc[1] || '');
				qs[config.queryKey] = config.queryValue();
				// set src to initiate reload
				imgElement.attr('src', oldSrc[0]+'?'+makeQueryString(qs));
				log('new src: '+imgElement.attr('src'));
			}, period*1000);
		};
		
		return this.each(function() {
			
			var img = $(this);
			
			img.load(function() {
				config.onAfterLoad.call(this);
			});
			
			// update periodical
			if(config.period) {
				periodicalUpdater(img, config.period);
			}
			
		});
	};

})(jQuery);
