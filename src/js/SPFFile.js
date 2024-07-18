import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { useState } from '@wordpress/element'
import { compose } from '@wordpress/compose'
import { __ } from '@wordpress/i18n'

const SPFFile = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: JSON.stringify(value) } })
            },
            deleteMetaValue: () => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: null } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        // return {
        //     metaValue: metaValue ? JSON.parse(metaValue) : null
        // }
        let parsedMetaValue = null

        try {
            parsedMetaValue = metaValue ? JSON.parse(metaValue) : null
        } catch (error) {
            console.error("Failed to parse metaValue:", error)
        }

        return {
            metaValue: parsedMetaValue
        }
    })
)((props) => {
    /**
     * Initializes the value state.
     *
     * @return {string} - The initial value.
     */

    // const fileData = props.metaValue ? JSON.parse(props.metaValue) : null

    const [value, setValue] = useState(() => {
        // if ('undefined' !== typeof props.value) {
        //     return props.value || null
        // }
        // console.log(props.metaValue)
        // return props.metaValue ? JSON.parse(props.metaValue) : null
        // try {
        //     if ('undefined' !== typeof props.value) {
        //         return props.value || null
        //     }
        //     return props.metaValue ? JSON.parse(props.metaValue) : null
        // } catch (error) {
        //     console.error("Failed to parse metaValue:", error)
        //     return null
        // }
        if ('undefined' !== typeof props.value) {
            return props.value || null
        }
        return props.metaValue
    })

    /**
     * Handle file select change and upload.
     *
     * @param {Object} media - The media file data taken from the media library.
     */
    const handleSelect = (media) => {
        const fileData = {
            id: media.id,
            url: media.url,
            name: media.title,
            type: media.mime
        }
        setValue(fileData)
        if ('undefined' !== typeof props.onChange) {
            props.onChange(fileData)
        } else {
            props.setMetaValue(fileData)
        }
    }

    /**
     * Handle delete file.
     *
     */
    const handleDelete = () => {
        setValue(null)
        props.deleteMetaValue()
    }

    return (
        <div className="spf-plugin-sidebar-field">
            {props.label && <label>{props.label}</label>}
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={handleSelect}
                    allowedTypes={props.allowedTypes}
                    // value={fileData ? fileData.id : ''}
                    value={value ? value.id : ''}
                    render={({ open }) => (
                        <Button variant='primary' onClick={open}>
                            {value ? __('Change File') : __('Upload File')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
            {value && (
                <div>
                    <p>{__('File:', 'text-domain')}</p>
                    <p>{__('ID:', 'text-domain')} {value.id}</p>
                    <p>{__('URL:', 'text-domain')} <a href={value.url} target="_blank" rel="noopener noreferrer">{value.url}</a></p>
                    <p>{__('Name:', 'text-domain')} {value.name}</p>
                    <p>{__('Type:', 'text-domain')} {value.type}</p>
                    <Button variant='primary' onClick={handleDelete}>
                        Remove File
                    </Button>
                </div>
            )}
        </div>
    )
})

export default SPFFile
