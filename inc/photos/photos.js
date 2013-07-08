(function($) {

	var $form;

	/*
	 * Manage form submit
	 */

	$(document).ready(function() {

		$form = $('#photo-form');

	 	$form.ajaxForm({
			beforeSend: initProgressBar,
			uploadProgress: updateProgressBar,
			success: success,
			data: { action: 'save_images' },
			url: fdt_photos.ajaxurl,
			dataType: 'json',
			type: 'POST'
		});

		var progressBar;

		function initProgressBar() {
			progressBar = '<div class="progress-bar"><div class="progress"></div><span class="percentage">0%</span></div>';
			formMessage('', progressBar, true);
		}

		function updateProgressBar(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			var text = percentVal;
			progressBar.find('.progress').width(percentVal);
			if(percentVal == '100%')
				text = fdt_photos.crunching_message;
			progressBar.find('.percentage').text(text);
			formMessage('', progressBar, true, false);
		}

		function success(data) {

			formMessage(data.status, data.message);

		}
	});

	function formMessage(status, message, newNode, scroll) {

		$form.find('.message').remove();

		if(typeof newNode === 'undefined')
			var $message = $('<div class="' + status + ' message"><p>' + message + '</p></div>');
		else
			var $message = $('<div class="message">' + message + '</div>');
		
		if(status == 'success')
			$form.empty();

		$form.prepend($message);

		// scroll user to message
		if(scroll !== false) {
			var topOffset = $form.offset().top;
			$('body,html').animate({
				scrollTop: topOffset - 50
			});
		}

	}

	/* 
	 * Multiple photos UI
	 */

	var container;

	$(document).ready(function() {

		container = $('#images_box');

		if(!container.length)
			return false;

		var list = container.find('.image-list');
		var template = container.find('.template');

		// create first input
		newInput();

		list.find('.remove').click(function() {
			$(this).parents('li').remove();
			updateAttrs();
			return false;
		});

		container.find('.new-image').click(function() {

			newInput();

			return false;

		});

		function newInput() {

			var item = template.clone().removeClass('template');

			item.find('.remove').click(function() {
				$(this).parents('li').remove();
				updateAttrs();
				return false;
			});

			list.append(item);

			updateAttrs();

		}

		function updateAttrs() {
			list.find('li').each(function(i) {
				var id = 'image-' + i;
				if(i === 0)
					$(this).find('.remove').hide();
				$(this).find('.image-title').attr('name', 'images[' + id + '][title]');
				$(this).find('.image-file').attr('name', 'image_files[]');
				$(this).find('.image-id').attr('name', 'images[' + id + '][id]');
				$(this).find('.image-id').val(id);
			});
		}

	});

})(jQuery);