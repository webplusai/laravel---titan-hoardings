modalform = {

	/**
	 * These are the default options. You can override them when you call dialog().
	 */
	options: {
		// Array of bootbox button names which should submit the form.
		submit_buttons: ['submit'],

		// Success callback to run once the form has been submitted and a successful response is received.
		success: function(data, status, jqxhr) {
			bootbox.hideAll();
			document.location.reload();
		},

		// Whether to allow the form to be submitted by pressing enter in a field.
		allow_enter_submit: true,

		// Whether to autofocus the first field or not.
		// Also accepts a string containing an element name (eg. "first_name") to focus that field instead.
		autofocus: true,

		// Function to run after the dialog has been opened.
		after_init: function() {}
	},

	dialog: function(options) {
		if (typeof bootbox == 'undefined') {
			console.error('bootbox.js must be loaded before calling modalform.dialog().');
			return;
		}

		jQuery.extend(true, this.options, options);

		// Determine which modal buttons should use the submit behaviour
		for (var i in this.options.submit_buttons) {
			var name = this.options.submit_buttons[i];
			this.options.bootbox.buttons[name].callback = modalform.button_callback;
		}

		if (this.options.autofocus) {
			this.options.bootbox.animate = false;
		}

		// Open the bootbox modal
		bootbox.dialog(this.options.bootbox);

		$('.modal form').on('submit', modalform.onsubmit);

		if (this.options.allow_enter_submit) {
			$('.modal form').append($('<input type="submit">').hide());
		}

		if (this.options.autofocus == true) {
			if ( $('.modal form')[0] )
				$('.modal form')[0].elements[0].focus();
		}

		if (typeof this.options.autofocus == 'string') {
			$('.modal form [name="' + this.options.autofocus + '"]').select();
		}

		this.options.after_init();
	},

	/**
	 * This is run when a submit button is clicked.
	 *
	 * Returning false stops the modal from closing automatically.
	 */
	button_callback: function() {
		$('.modal form').trigger('submit');
		return false;
	},

	/**
	 * Handles submitting the form.
	 */
	onsubmit: function(event) {
		event.preventDefault();

		$('.modal-body .alert-danger').remove();
		$('.modal-footer button').attr('disabled','disabled');

		$.ajax({
			url: $(this).attr('action'),
			method: $(this).attr('method'),
			data: $(this).serialize(),
			success: modalform.options.success,
			error: function(jqxhr, status, error) {
				var errors = [];

				if (jqxhr.status == 422) {
					for (var field in jqxhr.responseJSON) {
						$.merge(errors, jqxhr.responseJSON[field]);
					}
				} else {
					errors = [error];
				}

				$('.modal-body').append($('<div class="alert alert-danger"></div>').html(errors.join('<br>')));
				$('.modal-footer button').removeAttr('disabled');
			}
		});
	}
};
