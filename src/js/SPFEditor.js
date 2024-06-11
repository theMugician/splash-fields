import { RichText } from '@wordpress/block-editor'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFEditor = compose(
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
			<RichText
				value={props.metaValue}
				onChange={(content) => { props.setMetaValue(content) }}
				placeholder={props.placeholder}
			/>
		</div>
	)
})

export default SPFEditor
