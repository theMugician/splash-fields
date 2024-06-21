import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState, useEffect } from '@wordpress/element'
import PropTypes from 'prop-types'

const SPFCheckboxList = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: JSON.stringify(value) } })
            },
            deleteMetaValue: () => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: null } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey]
        return {
            metaValue: metaValue ? JSON.parse(metaValue) : []
        }
    })
)((props) => {
    const [isInitialLoad, setIsInitialLoad] = useState(true)

    const [value, setValue] = useState(() => {
        if ('undefined' !== typeof props.value) {
            return props.value
        }
        return props.metaValue || props.default || ''
    })

    useEffect(() => {
        if ('' === value && isInitialLoad && 'undefined' !== typeof props.default) {
            setValue(props.default)
        }
        setIsInitialLoad(false)
    }, [isInitialLoad, value, props.default, setIsInitialLoad])

    /**
     * Handle change in checkbox selection.
     *
     * @param {string} option - The option value that was changed.
     */
    const handleChange = (optionValue) => {
        const currentValue = value || []
        const newValue = currentValue.includes(optionValue)
            ? currentValue.filter(item => item !== optionValue)
            : [...currentValue, optionValue]

        setValue(newValue)
        if (props.onChange) {
            props.onChange(newValue)
        } else {
            props.setMetaValue(newValue)
        }
    }

    const optionsArray = Object.entries(props.options).map(([optionValue, optionLabel]) => ({
        value: optionValue,
        label: optionLabel
    }))

    return (
        <div className="spf-plugin-sidebar-field">
            {props.label && <label>{props.label}</label>}
            {optionsArray.map(option => (
                <CheckboxControl
                    key={option.value}
                    label={option.label}
                    checked={value.includes(option.value)}
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