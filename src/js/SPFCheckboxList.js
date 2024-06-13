import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import PropTypes from 'prop-types'

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

    const optionsArray = Object.entries(props.options).map(([value, label]) => ({
        value,
        label
    }))

    return (
        <div>
            {props.label && <label>{props.label}</label>}
            {optionsArray.map(option => (
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

SPFCheckboxList.propTypes = {
    label: PropTypes.string,
    options: PropTypes.objectOf(PropTypes.string).isRequired,
    metaKey: PropTypes.string.isRequired,
    metaValue: PropTypes.array.isRequired,
    setMetaValue: PropTypes.func.isRequired
}

SPFCheckboxList.defaultProps = {
    options: {}
}

export default SPFCheckboxList