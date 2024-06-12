(function ($) {
    'use strict'

    const file = {}

    /**
     * Handles a click on delete file.
     *
     * @param {Object} event Click event.
     */
    file.deleteHandler = function (event) {
        event.preventDefault()
        file.input.val('')
        file.container.html('')
        file.addFileLink.removeClass('hide')
        file.deleteFileLink.addClass('hide')
    }

    /**
     * Handles file selection.
     *
     * @param {Object} event File input change event.
     */
    file.fileChangeHandler = function (event) {
        const fileInput = event.target
        if (0 < fileInput.files.length) {
            const selectedFile = fileInput.files[0]
            const fileData = {
                id: '',  // This will be filled by the server-side processing
                url: '', // This will be filled by the server-side processing
                name: selectedFile.name,
                type: selectedFile.type
            }

            // Send the file data to our hidden input as a JSON string
            file.input.val(JSON.stringify(fileData))
            file.container.html('<div>' + fileData.name + '</div>')
            file.addFileLink.addClass('hide')
            file.deleteFileLink.removeClass('hide')
        }
    }

    file.addEventListeners = function () {
        file.deleteFileLink.on('click', file.deleteHandler)
        file.fileInput.on('change', file.fileChangeHandler)
    }

    /**
     * Initiate file object.
     */
    function init () {
        file.field = $('.spf-field-file')
        file.input = file.field.find('.spf-file__file-data')
        file.container = file.field.find('.spf-file__file-container')
        file.addFileLink = file.field.find('.spf-file__upload')
        file.deleteFileLink = file.field.find('.spf-file__delete')
        file.fileInput = file.field.find('.spf-file__add')

        file.addEventListeners()
    }

    $(document).ready(function () {
        init()
    })

})(jQuery)
