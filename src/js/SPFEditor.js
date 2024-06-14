import { RichText } from '@wordpress/block-editor'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFEditor = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                console.log('Setting meta value:', value)
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        console.log('Retrieved meta value:', metaValue)
        return {
            metaValue: metaValue || '' // Ensure metaValue is a string
        }
    })
)((props) => {
    return (
        <div>
            {props.label && <label>{props.label}</label>}
            <RichText
                value={props.metaValue}
                onChange={(content) => { props.setMetaValue(content) }}
                // placeholder={props.placeholder || 'Enter text here...'}
            />
        </div>
    )
})

export default SPFEditor
