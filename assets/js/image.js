(function ($) {
    'use strict'

    const image = {}

    /**
     * Handles a click on delete image.
     *
     * @param {Object} event Click event.
     */
    image.deleteHandler = function (event) {
        console.log('Delete handler triggered')
        console.log($(this))
        event.preventDefault()
        const field = $(this).closest('.spf-field-image')
        const input = field.find('.spf-image__image-data')
        const container = field.find('.spf-image__image-container')
        const addImgLink = field.find('.spf-image__upload')
        const delImgLink = field.find('.spf-image__delete')

        input.val('')
        container.html('')
        addImgLink.removeClass('hide')
        delImgLink.addClass('hide')
    }

    /**
     * Handles image selection.
     *
     * @param {Object} event Click event.
     */
    image.addHandler = function (event) {
        console.log('Add handler triggered')
        event.preventDefault()
        const field = $(this).closest('.spf-field-image')

        // If the media frame already exists, reopen it.
        if (field.data('frame')) {
            field.data('frame').open()
            return
        }

        // Create a new media frame
        const frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            library: {
                type: 'image'  // Restrict to images only
            },
            multiple: false  // Set to true to allow multiple files to be selected
        })

        // Store the frame in the field data
        field.data('frame', frame)

        // When an image is selected in the media frame...
        frame.on('select', function () {
            // Get media attachment details from the frame state
            const attachment = frame.state().get('selection').first().toJSON()

            // Send the attachment URL to our custom image input field.
            const imgContainer = field.find('.spf-image__image-container')
            imgContainer.html('<img src="' + attachment.url + '" alt="' + attachment.alt + '" />')

            // Create an object with the image data
            const imageData = {
                id: attachment.id,
                url: attachment.url,
                name: attachment.title,
                alt: attachment.alt
            }

            // Send the image data to our hidden input as a JSON string
            const imgDataInput = field.find('.spf-image__image-data')
            imgDataInput.val(JSON.stringify(imageData))

            // Hide the add image link
            const addImgLink = field.find('.spf-image__upload')
            addImgLink.addClass('hide')

            // Unhide the remove image link
            const delImgLink = field.find('.spf-image__delete')
            delImgLink.removeClass('hide')
        })

        // Finally, open the modal on click
        frame.open()
    }

    /**
     * Adds event listeners for image field actions.
     */
    image.addEventListeners = function () {
        $('.spf-image__upload').on('click', image.addHandler)
        $('.spf-image__delete').on('click', image.deleteHandler)
    }

    /**
     * Initiates image object.
     */
    function init () {
        image.addEventListeners()
    }

    $(document).ready(function () {
        init()
    })

})(jQuery)

console.log('image.js loaded')