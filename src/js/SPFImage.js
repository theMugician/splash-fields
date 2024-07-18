import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { useState } from '@wordpress/element'
import { compose } from '@wordpress/compose'
import { __ } from '@wordpress/i18n'

const SPFImage = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                const newValue = value ? JSON.stringify(value) : null
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: newValue } })
            },
            deleteMetaValue: () => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: null } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        let parsedValue = null
        try {
            parsedValue = metaValue ? JSON.parse(metaValue) : null
            if ('object' !== typeof parsedValue || Array.isArray(parsedValue)) {
                parsedValue = null
            }
        } catch (e) {
            console.error('Failed to parse metaValue', e)
            parsedValue = null
        }
        return {
            metaValue: parsedValue
        }
    })
)((props) => {
    const [value, setValue] = useState(() => {
        if ('undefined' !== typeof props.value) {
            return props.value || null
        }
        return props.metaValue
    })

    /**
     * Handle image select change and upload.
     *
     * @param {Object} media - The media file data taken from the media library.
     */
    const addImage = (media) => {
        const newImage = {
            id: media.id,
            url: media.url,
            name: media.title,
            alt: media.alt
        }
        setValue(newImage)
        if ('undefined' !== typeof props.onChange) {
            props.onChange(newImage)
        } else {
            props.setMetaValue(newImage)
        }
    }

    /**
     * Handle image remove.
     */
    const removeImage = () => {
        setValue(null)
        props.deleteMetaValue()
    }

    return (
        <div className="spf-plugin-sidebar-field">
            {props.label && <label>{props.label}</label>}
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={addImage}
                    allowedTypes={['image']}
                    value={value ? value.id : ''}
                    render={({ open }) => (
                        <Button variant="secondary" onClick={open}>
                            {value ? __('Replace Image') : __('Upload Image')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
            {value && (
                <div className="spf-plugin-sidebar-file-info">
                    <img src={value.url} alt={value.alt || __('Selected Image')} style={{ maxWidth: '100%' }} />
                    <p>{__('Image ID:', 'text-domain')} {value.id}</p>
                    <p>{__('Image Name:', 'text-domain')} {value.name}</p>
                    <p>{__('Image Alt:', 'text-domain')} {value.alt}</p>
                    <Button variant="secondary" isDestructive onClick={removeImage}>
                        {__('Remove Image')}
                    </Button>
                </div>
            )}
        </div>
    )
})

export default SPFImage
