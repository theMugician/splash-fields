(function ($) {
    'use strict'

    const file = {}

    file.getIcon = function (attachment) {
        /*
        if ('image' === attachment.type) {
            return `<img src="${attachment.url}" alt="${attachment.name}" style="width:48px;height:64px;">`
        } else {
            fetch(spfFileData.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_file_icon&id=${attachment.id}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.data.url) // This outputs the correct URL
                        return `<img src="${data.data.url}" alt="${attachment.name}" style="width:48px;height:48px;">` // Adjust the path to your generic icon
                    } else {
                        console.error('Error fetching image')
                    }
                })
                .catch(error => console.error('Error:', error))
        }
        */
        return new Promise((resolve, reject) => {
            if ('image' === attachment.type) {
                resolve(`<img src="${attachment.url}" alt="${attachment.name}" style="width:48px;height:48px;">`)
            } else if (spfFileData.defaultIcon) {
                resolve(`<img src="${spfFileData.defaultIcon}" alt="${attachment.name}" style="width:48px;height:64px;">`)
            } else {
                fetch(spfFileData.ajaxUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=get_file_icon&id=${attachment.id}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data.data.url)
                            resolve(`${data.data.url}`)
                        } else {
                            console.error('Error fetching image')
                            reject('Error fetching image')
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error)
                        reject(error)
                    })
            }
        })
    }

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
            // <img src="${attachment.url}" alt="${attachment.name}" style="width:48px;height:48px;">

            // Send the attachment URL to our custom file input field.
            const fileContainer = field.find('.spf-file__file-container')
            // fileContainer.html('<div>' + attachment.name + '</div>')
            /*
            fileContainer.html(
                `<div class="spf-file">
                    <div class="spf-file__icon">
                        ${file.getIcon(attachment)}
                    </div>
                    <div class="spf-file__info">
                        <a href="${attachment.url}" target="_blank" class="spf-file__title">${attachment.name}</a>
                        <div class="spf-file__name">${attachment.name}</div>
                    </div>
                </div>
                `
            )
            */
            file.getIcon(attachment).then(iconHtml => {
                fileContainer.html(`
                    <div class="spf-file">
                        <div class="spf-file__icon">${iconHtml}</div>
                        <div class="spf-file__info">
                            <a href="${attachment.url}" target="_blank" class="spf-file__title">${attachment.name}</a>
                            <div class="spf-file__name">${attachment.name}</div>
                        </div>
                    </div>
                `)
            })
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

    // Expose init function globally
    window.spf = window.spf || {}
    window.spf.fileInit = init

})(jQuery)
