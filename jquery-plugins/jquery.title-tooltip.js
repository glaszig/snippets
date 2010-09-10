/**
 * simple plugin to simultaneously toggle slide and toggle fade
 * @author martin glaß, glaszig at gmail dot com
 */
(function($) {

	$.fn.tooltip = function(config) {
	
		config = $.extend({
			debug: false,
			container: '#tooltip',
			content: '.content',
			position: 'bottom',
			arrowTemplate: '<span id="tooltip-arrow" class="tooltip-arrow arrow-#{class}"></span>',
			positionClassRegexp: /yt-tooltip-(.*)/i
		}, config);
		
		var tooltip = $(config.container).css({position:'absolute'}),
			msg = $(config.content, tooltip);
		
		var bottom = function() {
			tooltip.css({
				left: $(this).offset().left + $(this).outerWidth()/2 + 'px',
				top: $(this).offset().top + $(this).outerHeight() + 10 + 'px'
			});
		};
		
		var top = function() {
			tooltip.css({
				left: $(this).offset().left + $(this).outerWidth()/2 + 'px',
				top: $(this).offset().top - tooltip.outerHeight() - 10 + 'px'
			});
		};
		
		// called in context of the hovered button
		var left = function() {
			tooltip.css({
				left: $(this).offset().left - tooltip.outerWidth() - 10 + 'px',
				top: $(this).offset().top + ($(this).outerHeight() - tooltip.outerHeight()) + 'px'
			});
		}
		
		var right = function() {
			tooltip.css({
				left: $(this).offset().left + $(this).outerWidth() + 10 + 'px',
				top: $(this).offset().top + ($(this).outerHeight() - tooltip.outerHeight()) + 'px'
			});
		}
				
		return this.each(function() {
			
			if(!this.title) return;
			
			var title = this.title,
				position = this.className.match(config.positionClassRegexp),
				position = position == null ? config.position : position[1];
			
			$(this).mouseover(function() {
			
				$('#tooltip-arrow', tooltip).remove();
				$(config.arrowTemplate.replace(/#\{class\}/i, position)).appendTo(tooltip);
				
				tooltip.removeClass(function() {
					var p = ['top', 'bottom', 'left', 'right'], ret = [];
					for(var i=0; i < p.length; i++)
						ret.push('tooltip-position-'+p[i]);
					return ret.join(' ');
				});
				tooltip.addClass(function(idx, cls) {
					return 'tooltip-position-'+position;	
				});
				
				$(this).removeAttr('title');
				msg.html(title);
				
				switch(position) {
					case 'top': top.call(this); break;
					case 'bottom': bottom.call(this); break;
					case 'left': left.call(this); break;
					case 'right': right.call(this); break;
				}
				
				/*tooltip.css({
					'left': left + 'px',
					'top': top + 'px'
				}).show();
				*/
				tooltip.show();
			});
			
			$(this).mouseout(function() {
				msg.empty();
				tooltip.hide();
				$(this).attr('title', title);
			});
			
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