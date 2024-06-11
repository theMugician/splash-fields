import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { __ } from '@wordpress/i18n'

const SPFImage = compose(
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
	return (
		<div>
			{props.label && <label>{props.label}</label>}
			<MediaUploadCheck>
				<MediaUpload
					onSelect={(media) => props.setMetaValue(media.id)}
					allowedTypes={['image']}
					value={props.metaValue}
					render={({ open }) => (
						<Button onClick={open}>
							{props.metaValue ? __('Change Image') : __('Upload Image')}
						</Button>
					)}
				/>
			</MediaUploadCheck>
			{props.metaValue && (
				<div>
					<img src={wp.media.attachment(props.metaValue).get('url')} alt={__('Selected Image')} style={{ maxWidth: '100%' }} />
					<p>{__('Image ID:', 'text-domain')} {props.metaValue}</p>
				</div>
			)}
		</div>
	)
})

export default SPFImage
