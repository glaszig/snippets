/**
 * plugin to dynamically add a clear handler to input fields
 * 
 * @author glaszig at gmail dot com
 */
(function($) {
	
	$.fn.clearable = function(config) {
		
		config = $.extend({
			template: '<a href="#"></a>',
			className: 'jq-clear',
			onClick: function() {}
		}, config);
		
		// called in context of the clear button
		var clearAndRemove = function(input) {
			$(input).val('').focus();
			$(this).remove();
		}
		/**
		 * adds a clear button after the input
		 */
		var addClearButton = function(id) {
			$(config.template).attr('id', id).click((function(input) {
				return function() {
					clearAndRemove.call(this, input);
					return false;
				}
			})(this)).click(config.onClick).addClass(config.className)
			.insertAfter(this);
		}
		
		return this.each(function(index, item) {
			
			var id = 'clear-input-'+index;
			
			// check if the input already has a value
			if($(this).val() != '') {
				addClearButton.call(this, id);
			}
			
			// add the clear button on keyup and only
			// if it doesn't already exist
			$(this).keyup(function() {
				if($(this).val() != '' && $('#'+id).length < 1) {
					addClearButton.call(this, id);
				}
			});
			
		});
	}
	
})(jQuery);