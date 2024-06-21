import { TextareaControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState } from '@wordpress/element'
import useCommonLogic from './useCommonLogic' // Adjust the path as needed

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
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: null } })
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
    const { value, handleChange } = useCommonLogic(props, props.setMetaValue, props.deleteMetaValue, isInitialLoad, setIsInitialLoad)

    return (
        <TextareaControl
            className='spf-plugin-sidebar-field'
            label={props.label}
            value={value}
            onChange={handleChange}
        />
    )
})

export default SPFTextarea
