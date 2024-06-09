import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFCheckbox = compose(
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
		<CheckboxControl
			label={props.label}
			checked={props.metaValue}
			onChange={(checked) => { props.setMetaValue(checked) }}
		/>
	)
})

export default SPFCheckbox