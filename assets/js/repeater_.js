( function ($) {
	'use strict'

	let repeater = {}

	/**
	 * Handles a click to add a repeater row.
	 *
	 * @param event Click event.
	 */
	repeater.addRepeaterRowHandler = function (event) {
        event.preventDefault()
        var $wrapper = $(this).siblings('.spf-repeater-wrapper');
        var $clone = $wrapper.children('.spf-repeater-group:first').clone();
        $clone.find('input').val('');
        $wrapper.append($clone);
	}

	/**
	 * Handles a click to delete a repeater row.
	 *
	 * @param event Click event.
	 */
	repeater.deleteRepeaterRowHandler = function (event) {
        console.log('this works')
        event.preventDefault()
        $(this).closest('.spf-repeater-group').remove();
	}

    repeater.addEventListeners = function () {
        repeater.addRowButton.on('click', repeater.addRepeaterRowHandler)
        repeater.deleteRowButton.on('click', repeater.deleteRepeaterRowHandler)
    }

    function init() {
        repeater.addRowButton = $('.spf-add-repeater-row')
        repeater.deleteRowButton = $('.spf-delete-repeater-row')
        console.log(repeater.deleteRowButton)
        repeater.addEventListeners()
	}

    $(document).ready(function() {
        init()
    })

})(jQuery)