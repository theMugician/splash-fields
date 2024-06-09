import { TextControl } from '@wordpress/components'
import { withDispatch, withSelect, compose } from '@wordpress/data'

const SPFText = compose(
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
        <TextControl
            type='text'
            label={props.label}
            value={props.metaValue}
            onChange={(content) => { props.setMetaValue(content) }}
        />
    )
})

export default SPFText