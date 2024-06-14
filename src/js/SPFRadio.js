import { RadioControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFRadio = compose(
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
        <RadioControl
            label={props.label}
            selected={props.metaValue}
            options={optionsArray}
            onChange={(value) => { props.setMetaValue(value) }}
        />
    )
})

export default SPFRadio
