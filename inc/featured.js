var featuredSlider;

(function($) {

	featuredSlider = function(elementID) {

		var	$container,
			$items,
			$controllers,
			$activeItem,
			$nextItem;

		$container = $('#' + elementID);
		$items = $container.find('.slider-item');
		$controllers = $container.find('.slider-controllers');
		$activeItem = $container.find('.slider-item:first-child');

		var _openItem = function($item, animate) {
			if(!$item || !$item.length)
				return false;

			$items.removeClass('active');
			$item.addClass('active');

			$controllers.find('li').removeClass('active');
			$controllers.find('[data-postid="' + $item.attr('id') + '"]').addClass('active');

			$next = $item.next();

			if(!$next.length)
				$next = $container.find('.slider-item:nth-child(1)');
		}
		_openItem($activeItem, false);

		var _update = setInterval(function() {
			_openItem($next, true);
		}, 6000);

		$controllers.find('li').click(function() {
			_openItem($container.find('#' + $(this).data('postid')), true);
			clearInterval(_update);
			_update = setInterval(function() {
				_openItem($next, true);
			}, 6000);
		});

	}

})(jQuery);