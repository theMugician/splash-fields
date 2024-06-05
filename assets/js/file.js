( function ($) {
	'use strict'

	let file = {}

	/**
	 * Handles a click on delete new file.
	 *
	 * @param event Click event.
	 */
	file.deleteHandler = function (event) {
        event.preventDefault()
        file.input.val('')
        file.container.html('')
	}

    file.addEventListeners = function () {
        const deleteFile = file.field.find('.spf-file__delete')
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