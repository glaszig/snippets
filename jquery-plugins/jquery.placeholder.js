/**
 * plugin to simulate html5 placeholders on standard input fields
 *
 * @author glaszig at gmail dot com
 * @link github.com/glaszig/snippets
 * @license see link
 */
$.fn.placeholder = function(options) {
	
	return this.filter('[type=text]').each(function() {
		
		var changed = false;
		
		var setPlaceholder = function(input) {
			if(input.value == '' || !changed) {
				input.value = $(input).addClass('jq-has-placeholder').attr('placeholder');
				changed = false;
			}
		}
		var clearPlaceholder = function(input) {
			if(!changed && input.value == $(input).attr('placeholder')) {
				$(input).removeClass('jq-has-placeholder').val('');
			}
		}
		
		setPlaceholder(this);
		
		$(this).change(function() {
			changed = true;
			setPlaceholder(this);
		}).click(function() {
			clearPlaceholder(this);
		}).blur(function() {
			setPlaceholder(this);
		});
		
	});
	
}

