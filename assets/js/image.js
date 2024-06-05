jQuery(function ($) {
    // const metaBoxId = mediaField.id

    // Set all variables to be used in scope
    var frame,
        field = $('.spf-field-image'),
        addImgLink = field.find('.spf-image__upload'),
        delImgLink = field.find( '.spf-image__delete'),
        imgContainer = field.find( '.spf-image__image-container'),
        imgIdInput = field.find( '.spf-image__image-id' )

    // ADD IMAGE LINK
    addImgLink.on('click', function (event) {
        console.log(this)
        event.preventDefault()
        
        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open()
            return
        }
        
        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        })
  
        // When an image is selected in the media frame...
        frame.on('select', function () {
            
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON()
    
            // Send the attachment URL to our custom image input field.
            imgContainer.append('<img src="' + attachment.url + '" alt="" />')
    
            // Send the attachment id to our hidden input
            imgIdInput.val(attachment.id)
    
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
    
        // Delete the image id from the hidden input
        imgIdInput.val('')
  
    })
  
})