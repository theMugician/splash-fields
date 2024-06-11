(function ($) {
    'use strict'

    const optionsPage = {}

    /**
     * Handles a click on delete new optionsPage.
     * Currently not in use because of settings API which handles this.
     * @param event Click event.
     */
    /*
    optionsPage.saveHandler = function (event) {
        event.preventDefault()
        jQuery.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'spf_save_options',
                mb_form: form
            },
            success: function (response) {
            }
        })
    }
    */

    optionsPage.addEventListeners = function () {
        optionsPage.form.on('submit', optionsPage.saveHandler)
    }

    /**
     * Intantiate optionsPage object.
     */
    function init () {
        optionsPage.form = $('.spf-options-form')
        optionsPage.success = $('.spf-options-success')
        optionsPage.error = $('.spf-options-error')
        optionsPage.container = optionsPage.field.find('.spf-optionsPage__optionsPage-container')
        optionsPage.addEventListeners()
    }

    $(document).ready(function () {
        init()
    })

})(jQuery)