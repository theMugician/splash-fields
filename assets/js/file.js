( function ($) {
	'use strict'

	var file = {}

	/**
	 * Handles a click on add new file.
	 * Expects `this` to equal the clicked element.
	 *
	 * @param event Click event.
	 */
	file.addHandler = function () {
        file.form.trigger('submit')
        console.log($(this))
        console.log(spfFileField)

        $.ajax({
            type: 'POST',
            url: spfFileField.ajaxurl,
            data: { 
                action: 'spf_add_file',
			    field_id: file.inputId,
            },
            success: function( response ) {
                console.log(response)
            }
		})
	}

	/**
	 * Handles a click on delete new file.
	 * Expects `this` to equal the clicked element.
	 *
	 * @param event Click event.
	 */
	file.deleteHandler = function ( event ) {
		event.preventDefault()

		var $this = $( this ),
			$item = $this.closest( 'li' ),
			$uploaded = $this.closest( '.spf-files' ),
			$metaBox = $uploaded.closest( '.spf-meta-box' )

		$item.remove()
		file.updateVisibility.call( $uploaded )

		file.setRequired.call( $uploaded.parent() )

		if ( 1 > $uploaded.data( 'force_delete' ) ) {
			return
		}

		$.post( ajaxurl, {
			action: 'spf_delete_file',
			_ajax_nonce: $uploaded.data( 'delete_nonce' ),
			field_id: $uploaded.data( 'field_id' ),
			field_name: $uploaded.data( 'field_name' ),
			object_type: $metaBox.data( 'object-type' ),
			object_id: $metaBox.data( 'object-id' ),
			attachment_id: $this.data( 'attachment_id' )
		}, function ( response ) {
			if ( !response.success ) {
				alert( response.data )
			}
		}, 'json' )
	}

    file.addEventListeners = function () {
        console.log(file.field)

        const addFile = file.field.find('.spf-field-file__add-file')
        const deleteFile = file.field.find('.spf-field-file__delete-file')

        addFile.on('change', file.addHandler)
        deleteFile.on('click', file.deleteHandler)
    }

    function init() {
        file.field = $('.spf-field-file')
        const input = file.field.find('.spf-field-file__file-id')
        file.inputId = input.attr('id')
        file.form = input.closest('form')
        // file.addEventListeners()
		// var $el = $( e.target ),
		// 	$uploaded = $el.find( '.spf-files' )

		// $uploaded.each( file.sort )
		// $uploaded.each( file.updateVisibility )

		// $el.find( '.spf-file-wrapper, .spf-image-wrapper' ).each( file.setRequired )
	}

    $(document).ready(function() {
        init()
    })
	// 	.on( 'click', '.spf-file-add', file.addHandler )
	// 	.on( 'click', '.spf-file-delete', file.deleteHandler )

})(jQuery)