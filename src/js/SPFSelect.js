import { SelectControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFSelect = compose(
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
    const optionsArray = Object.keys(props.options).map(key => ({
        label: props.options[key],
        value: key
    }))

    return (
        <SelectControl
            label={props.label}
            value={props.metaValue}
            options={optionsArray}
            onChange={(content) => { props.setMetaValue(content) }}
        />
    )
})

export default SPFSelect
