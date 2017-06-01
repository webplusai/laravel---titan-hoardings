@yield('modal')

<script>
	var modal = $('script').last().prev();

	modal.find('form').on('submit', function(event) {
		event.preventDefault();

		modal.find('.alert-danger').remove();
		modal.find('.modal-footer button').attr('disabled', 'disabled');

		if (modal.find('input[type="file"]').size()) {
			var content_type = false;
			var data = new FormData(this);
			var process_data = false;
		} else {
			var content_type = 'application/x-www-form-urlencoded; charset=UTF-8';
			var data = $(this).serialize();
			var process_data = true;
		}

		$.ajax({
			url: $(this).attr('action'),
			method: $(this).attr('method'),
			contentType: content_type,
			data: data,
			processData: process_data,
			success: function(response) {
				@hasSection('onsuccess')
					@yield('onsuccess')
				@else
					modal.modal('hide');
				@endif
			},
			error: function(jqxhr, status, error) {
				var errors = [];

				if (jqxhr.status == 422) {
					for (var field in jqxhr.responseJSON) {
						$.merge(errors, jqxhr.responseJSON[field]);
					}
				} else {
					errors = [error];
				}

				modal.find('.modal-body').append($('<div class="alert alert-danger"></div>').html(errors.join('<br>')));
				modal.find('.modal-footer button').removeAttr('disabled');
			}
		});
	});

	modal.modal('show');

	// Remove this script tag and modal from DOM
	modal.on('hidden.bs.modal', function(event) {
		$(this).next('script').remove();
		$(this).remove();
	});

	@yield('onload')
</script>
