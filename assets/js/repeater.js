(function ($) {
    'use strict'

    const repeater = {}

    /**
     * Handles a click to add a repeater row.
     *
     * @param {Array} event Click event.
     */
    repeater.addRepeaterRowHandler = function (event) {
        event.preventDefault()

        // Find the repeater wrapper
        const $wrapper = $(this).siblings('.spf-repeater-wrapper')

        // Get the template and clone it
        const template = $(this).siblings('.spf-repeater-template').html()
        // const template = $('.spf-repeater-template').html()
        const $clone = $(template)

        // Reset the values of the inputs in the new group
        // $clone.find('input, textarea, select').each(function() {
        //     $(this).val('')
        // })

        // Append the new group to the wrapper
        $wrapper.append($clone)

        // Update indexes
        repeater.updateIndexes($wrapper)

        // Reinitialize TinyMCE editors in the new repeater row
        $clone.find('.wp-editor-area').each(function() {
            const id = $(this).attr('id')
            if (typeof tinymce !== 'undefined') {
                tinymce.execCommand('mceRemoveEditor', false, id)
                tinymce.execCommand('mceAddEditor', false, id)
            }
            if (typeof quicktags !== 'undefined') {
                quicktags({ id: id })
            }
            wp.editor.initialize(id, {
                tinymce: true,
                quicktags: true
            })
        })

        // Check for image and file fields in the new repeater row and initialize if present
        if ($clone.find('.spf-field-image').length > 0 && window.spf && typeof window.spf.imageInit === 'function') {
            window.spf.imageInit()
        }
        if ($clone.find('.spf-field-file').length > 0 && window.spf && typeof window.spf.fileInit === 'function') {
            window.spf.fileInit()
        }
    }

    /**
     * Handles a click to delete a repeater row.
     *
     * @param event Click event.
     */
    repeater.deleteRepeaterRowHandler = function (event) {
        event.preventDefault()

        // Remove the repeater group
        const $wrapper = $(this).closest('.spf-repeater-wrapper')
        $(this).closest('.spf-repeater-group').remove()

        // Update indexes
        repeater.updateIndexes($wrapper)
    }

    /**
     * Updates the indexes of the repeater groups.
     *
     * @param $wrapper The wrapper containing the repeater groups.
     */
    repeater.updateIndexes = function ($wrapper) {
        $wrapper.children('.spf-repeater-group').each(function (index) {
            $(this).find('input, textarea, select').each(function () {
                let name = $(this).attr('name')
                if (name) {
                    name = name.replace(/\[\d+\]/, '[' + index + ']')
                    $(this).attr('name', name)
                }
            })
            $(this).find('.spf-repeater-group__number').each(function () {
                $(this).text(index + 1)
            })
        })
    }

    repeater.addEventListeners = function () {
        repeater.addRowButton.on('click', repeater.addRepeaterRowHandler)
        $(document).on('click', '.spf-delete-repeater-row', repeater.deleteRepeaterRowHandler)
    }

    /**
     * Initiates repeater object.
     */
    function init () {
        repeater.addRowButton = $('.spf-add-repeater-row')
        repeater.addEventListeners()
    }

    $(document).ready(function() {
        init()
    })

})(jQuery)
