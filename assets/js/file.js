(function ($) {
    'use strict'

    const file = {}

    /**
     * Handles an error.
     *
     * @param {Object} event Click event.
     */
    file.errorHandler = function () {
        $.ajax({
            type: 'POST',
            url: spfFileField.ajaxurl,
            data: {
                action: 'spf_file_error',
			    field_id: file.inputId,
            },
            /**
             * Handles a click on delete new file.
             *
             * @param {Object} response returned response.
             */
            success: function (response) {
                console.log(response)
            }
        })
    }


    /**
     * Handles a click on delete new file.
     *
     * @param {Object} event Click event.
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

    /**
     * Initiate file object.
     *
     */
    function init () {
        file.field = $('.spf-field-file')
        file.input = file.field.find('.spf-file__id')
        file.container = file.field.find('.spf-file__file-container')
        file.addEventListeners()
    }

    $(document).ready(function () {
        init()
    })

})(jQuery)