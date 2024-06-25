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
        const field = $(this).closest('.spf-field-file')
        const input = field.find('.spf-file__file-data')
        const container = field.find('.spf-file__file-container')
        const addFileLink = field.find('.spf-file__upload')
        const delFileLink = field.find('.spf-file__delete')

        input.val('')
        container.html('')
        addFileLink.removeClass('hide')
        delFileLink.addClass('hide')
    }

    /**
     * Handles file selection.
     *
     * @param {Object} event Click event.
     */
    file.addHandler = function (event) {
        event.preventDefault()
        const field = $(this).closest('.spf-field-file')

        // If the media frame already exists, reopen it.
        if (field.data('frame')) {
            field.data('frame').open()
            return
        }

        // Create a new media frame
        const frame = wp.media({
            title: 'Select or Upload File',
            button: {
                text: 'Use this file'
            },
            library: {
                type: ''  // Restrict to files only
            },
            multiple: false  // Set to true to allow multiple files to be selected
        })

        // Store the frame in the field data
        field.data('frame', frame)

        // When a file is selected in the media frame...
        frame.on('select', function () {
            // Get media attachment details from the frame state
            const attachment = frame.state().get('selection').first().toJSON()

            // Send the attachment URL to our custom file input field.
            const fileContainer = field.find('.spf-file__file-container')
            fileContainer.html('<div>' + attachment.name + '</div>')

            // Create an object with the file data
            const fileData = {
                id: attachment.id,
                url: attachment.url,
                name: attachment.name,
                type: attachment.type
            }

            // Send the file data to our hidden input as a JSON string
            const fileDataInput = field.find('.spf-file__file-data')
            fileDataInput.val(JSON.stringify(fileData))

            // Hide the add file link
            const addFileLink = field.find('.spf-file__upload')
            addFileLink.addClass('hide')

            // Unhide the remove file link
            const delFileLink = field.find('.spf-file__delete')
            delFileLink.removeClass('hide')
        })

        // Finally, open the modal on click
        frame.open()
    }

    /**
     * Adds event listeners for file field actions.
     */
    file.addEventListeners = function () {
        $('.spf-file__upload').on('click', file.addHandler)
        $('.spf-file__delete').on('click', file.deleteHandler)
    }

    /**
     * Initiates file object.
     */
    function init () {
        file.addEventListeners()
    }

    $(document).ready(function () {
        init()
    })

})(jQuery)
