(function($) {

	var $form;

	$(document).ready(function() {

		$form = $('#event-form');

		$form.submit(function() {

			var serialized = $(this).serialize();

			formMessage('', fdt_events.loading_msg);

			$.post(fdt_events.ajaxurl + '?' + serialized, { action: 'save_event' }, function(data) {
				formMessage(data.status, data.message);
			}, 'json');

			return false;

		});
	});

	function formMessage(status, message) {

		$form.find('.message').remove();

		var $message = $('<div class="' + status + ' message"><p>' + message + '</p></div>');
		
		if(status == 'success')
			$form.empty();

		$form.prepend($message);

		// scroll user to message
		var topOffset = $form.offset().top;
		$('body,html').animate({
			scrollTop: topOffset - 50
		});

	}

})(jQuery);