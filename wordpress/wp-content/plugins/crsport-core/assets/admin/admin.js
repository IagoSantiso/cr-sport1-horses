/* Ficha del caballo: galería ordenable y valoración. */
jQuery(function ($) {
	var $list = $('[data-crs-gallery]');
	var $input = $('[data-crs-gallery-input]');
	var frame;

	function sync() {
		$input.val($list.children('li').map(function () { return $(this).data('id'); }).get().join(','));
	}

	$list.sortable({ update: sync, tolerance: 'pointer' });

	$list.on('click', '.crs-gallery__remove', function () {
		$(this).closest('li').remove();
		sync();
	});

	$('[data-crs-gallery-add]').on('click', function (e) {
		e.preventDefault();
		if (!frame) {
			frame = wp.media({
				title: 'Fotos y vídeos del caballo',
				button: { text: 'Añadir a la galería' },
				library: { type: ['image', 'video'] },
				multiple: 'add'
			});
			frame.on('select', function () {
				frame.state().get('selection').each(function (att) {
					var a = att.toJSON();
					if ($list.children('[data-id="' + a.id + '"]').length) return;
					var thumb = a.type === 'video'
						? '<span class="dashicons dashicons-video-alt3"></span>'
						: '<img src="' + ((a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url) + '" alt="">';
					$list.append('<li data-id="' + a.id + '">' + thumb + '<button type="button" class="crs-gallery__remove" aria-label="Quitar">×</button></li>');
				});
				sync();
			});
		}
		frame.open();
	});

	$('.crs-score input[type=range]').on('input', function () {
		$(this).next('output').text(this.value === '0' ? '—' : this.value);
	});
});
