import { TextareaControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFTextarea = compose(
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
        <TextareaControl
            label={props.label}
            value={props.metaValue}
            onChange={(content) => { props.setMetaValue(content) }}
        />
    )
})

export default SPFTextarea