import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

const SPFCheckboxList = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || []
        }
    })
)((props) => {
    const handleChange = (option) => {
        const newValue = props.metaValue.includes(option)
            ? props.metaValue.filter(item => item !== option)
            : [...props.metaValue, option]
        props.setMetaValue(newValue)
    }

    return (
        <div>
            {props.label && <label>{props.label}</label>}
            {props.options.map(option => (
                <CheckboxControl
                    key={option.value}
                    label={option.label}
                    checked={props.metaValue.includes(option.value)}
                    onChange={() => handleChange(option.value)}
                />
            ))}
        </div>
    )
})

export default SPFCheckboxList
