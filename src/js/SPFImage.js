import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { __ } from '@wordpress/i18n'

const SPFImage = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                const newValue = JSON.stringify(value)
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: newValue } })
            }
        }
    }),
    withSelect((select, props) => {
        let metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        let parsedValue = []
        try {
            parsedValue = metaValue ? JSON.parse(metaValue) : []
            if (!Array.isArray(parsedValue)) {
                parsedValue = []
            }
        } catch (e) {
            console.error('Failed to parse metaValue', e)
            parsedValue = []
        }
        return {
            metaValue: parsedValue
        }
    })
)((props) => {
    const addImage = (media) => {
        const newImage = {
            id: media.id,
            url: media.url,
            name: media.title,
            alt: media.alt
        }
        const newValue = [...props.metaValue, newImage]
        props.setMetaValue(newValue)
    }

    const removeImage = (id) => {
        const newValue = props.metaValue.filter((image) => image.id !== id)
        props.setMetaValue(newValue)
    }

    return (
        <div>
            {props.label && <label>{props.label}</label>}
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={addImage}
                    allowedTypes={['image']}
                    value={props.metaValue.map((image) => image.id)}
                    render={({ open }) => (
                        <Button onClick={open}>
                            {__('Upload Image')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
            {props.metaValue.length > 0 && (
                <div>
                    {props.metaValue.map((image) => (
                        <div key={image.id}>
                            <img src={image.url} alt={image.alt || __('Selected Image')} style={{ maxWidth: '100%' }} />
                            <p>{__('Image ID:', 'text-domain')} {image.id}</p>
                            <p>{__('Image Name:', 'text-domain')} {image.name}</p>
                            <p>{__('Image Alt:', 'text-domain')} {image.alt}</p>
                            <Button onClick={() => removeImage(image.id)}>
                                {__('Remove Image')}
                            </Button>
                        </div>
                    ))}
                </div>
            )}
        </div>
    )
})

export default SPFImage
