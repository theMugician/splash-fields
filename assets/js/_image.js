// Deprecated
jQuery(function ($) {
    // Set all variables to be used in scope
    let frame

    const field = $('.spf-field-image'),
        addImgLink = field.find('.spf-image__upload'),
        delImgLink = field.find('.spf-image__delete'),
        imgContainer = field.find('.spf-image__image-container'),
        imgDataInput = field.find('.spf-image__image-data')

    // ADD IMAGE LINK
    addImgLink.on('click', function (event) {
        event.preventDefault()

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open()
            return
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            library: {
                type: 'image'  // Restrict to images only
            },
            multiple: false  // Set to true to allow multiple files to be selected
        })

        // When an image is selected in the media frame...
        frame.on('select', function () {
            // Get media attachment details from the frame state
            const attachment = frame.state().get('selection').first().toJSON()

            // Send the attachment URL to our custom image input field.
            imgContainer.html('<img src="' + attachment.url + '" alt="' + attachment.alt + '" />')

            // Create an object with the image data
            const imageData = {
                id: attachment.id,
                url: attachment.url,
                name: attachment.title,
                alt: attachment.alt
            }

            // Send the image data to our hidden input as a JSON string
            imgDataInput.val(JSON.stringify(imageData))

            // Log the data being set
            console.log('Image Data:', imageData)

            // Hide the add image link
            addImgLink.addClass('hide')

            // Unhide the remove image link
            delImgLink.removeClass('hide')
        })

        // Finally, open the modal on click
        frame.open()
    })

    // DELETE IMAGE LINK
    delImgLink.on('click', function (event) {
        event.preventDefault()

        // Clear out the preview image
        imgContainer.html('')

        // Un-hide the add image link
        addImgLink.removeClass('hide')

        // Hide the delete image link
        delImgLink.addClass('hide')

        // Delete the image data from the hidden input
        imgDataInput.val('')
    })
})
