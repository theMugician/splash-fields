import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { __ } from '@wordpress/i18n'

const SPFFile = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        }
    })
)((props) => {
    const handleSelect = (media) => {
        const fileData = {
            id: media.id,
            url: media.url,
            name: media.title,
            type: media.mime
        }
        props.setMetaValue(JSON.stringify(fileData))
    }

    const fileData = props.metaValue ? JSON.parse(props.metaValue) : null

    return (
        <div>
            {props.label && <label>{props.label}</label>}
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={handleSelect}
                    allowedTypes={props.allowedTypes}
                    value={fileData ? fileData.id : ''}
                    render={({ open }) => (
                        <Button variant='primary' onClick={open}>
                            {fileData ? __('Change File') : __('Upload File')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
            {fileData && (
                <div>
                    <p>{__('File:', 'text-domain')}</p>
                    <p>{__('ID:', 'text-domain')} {fileData.id}</p>
                    <p>{__('URL:', 'text-domain')} <a href={fileData.url} target="_blank" rel="noopener noreferrer">{fileData.url}</a></p>
                    <p>{__('Name:', 'text-domain')} {fileData.name}</p>
                    <p>{__('Type:', 'text-domain')} {fileData.type}</p>
                </div>
            )}
        </div>
    )
})

export default SPFFile
