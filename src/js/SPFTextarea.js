import { TextareaControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState, useEffect } from '@wordpress/element'
import useCommonLogic from './useCommonLogic' // Adjust the path as needed

/**
 * SPFTextarea Component
 *
 * This component provides a textarea input field for WordPress.
 *
 * @param {Object}   props                - The component props.
 * @param {string}   props.label          - The label for the textarea field.
 * @param {string}   props.metaKey        - The meta key used to store the textarea field data.
 * @param {string}  [props.value]         - The value of the textarea field.
 * @param {string}  [props.default]       - The default value of the textarea field.
 * @param {function} [props.onChange]     - Function to update the meta value.
 * @param {function} props.setMetaValue   - Function to update the meta value from dispatch.
 * @param {function} props.deleteMetaValue - Function to delete the meta value from dispatch.
 * @returns {JSX.Element}                 - The rendered component.
 */
const SPFTextarea = compose(
    withDispatch((dispatch, props) => {
        return {
            /**
             * Set the meta value for the textarea field.
             *
             * @param {string} value - The new value of the textarea field.
             */
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            },
            /**
             * Delete the meta value for the textarea field.
             */
            deleteMetaValue: () => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: undefined } })
                // Use the REST API to delete the meta value from the database
                wp.apiFetch({
                    path: `/wp/v2/posts/${wp.data.select('core/editor').getCurrentPostId()}/meta/${props.metaKey}`,
                    method: 'DELETE'
                })
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
    const [isInitialLoad, setIsInitialLoad] = useState(true)
    const { getValue, handleChange } = useCommonLogic(props, props.setMetaValue, props.deleteMetaValue, isInitialLoad)

    useEffect(() => {
        setIsInitialLoad(false)
    }, [])

    return (
        <TextareaControl
            label={props.label}
            value={getValue()}
            onChange={handleChange}
        />
    )
})

export default SPFTextarea
