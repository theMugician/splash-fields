( function ($) {
	'use strict'

	var file = {}

	/**
	 * Handles a click on add new file.
	 *
	 * @param event Click event.
	 */
    /*
	file.addHandler = function () {
        file.form.trigger('submit')

        $.ajax({
            type: 'POST',
            url: spfFileField.ajaxurl,
            data: { 
                action: 'spf_add_file',
			    field_id: file.inputId,
            },
            success: function( response ) {
                console.log(response)
            }
		})
	}
    */

	/**
	 * Handles a click on delete new file.
	 *
	 * @param event Click event.
	 */
	file.deleteHandler = function (event) {
        console.log(event)
        event.preventDefault()
        file.input.val('')
        file.container.html('')
	}

    file.addEventListeners = function () {
        // const addFile = file.field.find('.spf-field-file__add-file')
        const deleteFile = file.field.find('.spf-file__delete')

        // addFile.on('change', file.addHandler)
        deleteFile.on('click', file.deleteHandler)
    }

    function init() {
        file.field = $('.spf-field-file')
        file.input = file.field.find('.spf-field-file__file-id')
        file.container = file.field.find('.spf-field-file__file-container')

        file.addEventListeners()
	}

    $(document).ready(function() {
        init()
    })

})(jQuery)