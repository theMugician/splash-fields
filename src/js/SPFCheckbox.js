import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

/**
 * SPFCheckbox Component
 *
 * This component provides a checkbox input field for WordPress.
 *
 * @param {Object}   props              - The component props.
 * @param {string}   props.label        - The label for the checkbox field.
 * @param {string}   props.metaKey      - The meta key used to store the checkbox field data.
 * @param {boolean}  props.value        - The value of the checkbox field.
 * @param {function} props.onChange     - Function to update the meta value.
 * @param {function} props.setMetaValue - Function to update the meta value from dispatch.
 * @return {JSX.Element} The rendered component.
 */
const SPFCheckbox = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || false
        return {
            metaValue
        }
    })
)((props) => {
    /**
     * Handle changes to the checkbox field.
     *
     * @param {boolean} checked - The new value of the checkbox field.
     */
    const handleChange = (checked) => {
        if (props.onChange) {
            props.onChange(checked)
        } else {
            props.setMetaValue(checked)
        }
    }

    return (
        <CheckboxControl
            label={props.label}
            checked={props.value !== undefined ? props.value : props.metaValue}
            onChange={handleChange}
        />
    )
})

export default SPFCheckbox
