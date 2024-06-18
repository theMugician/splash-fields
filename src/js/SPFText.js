import { TextControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'

/**
 * SPFText Component
 *
 * This component provides a text input field for WordPress.
 *
 * @param {Object}   props              - The component props.
 * @param {string}   props.label        - The label for the text field.
 * @param {string}   props.metaKey      - The meta key used to store the text field data.
 * @param {string}   props.value        - The value of the text field.
 * @param {function} props.onChange     - Function to update the meta value.
 * @param {function} props.setMetaValue - Function to update the meta value from dispatch.
 * @return {JSX.Element} The rendered component.
 */
const SPFText = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || ''
        return {
            metaValue
        }
    })
)((props) => {
    /**
     * Handle changes to the text field.
     *
     * @param {string} value - The new value of the text field.
     */
    const handleChange = (value) => {
        if (props.onChange) {
            props.onChange(value)
        } else {
            props.setMetaValue(value)
        }
    }

    return (
        <TextControl
            type='text'
            label={props.label}
            value={props.value || props.metaValue}
            onChange={handleChange}
        />
    )
})

export default SPFText
