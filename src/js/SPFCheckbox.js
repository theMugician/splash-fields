import { CheckboxControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState } from '@wordpress/element'
import useCommonLogic from './useCommonLogic'

const SPFCheckbox = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            },
            deleteMetaValue: () => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: null } })
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
    const [isInitialLoad, setIsInitialLoad] = useState(true)
    const { value, handleChange } = useCommonLogic(props, props.setMetaValue, props.deleteMetaValue, isInitialLoad, setIsInitialLoad)

    return (
        <CheckboxControl
            className="spf-plugin-sidebar-field"
            label={props.label}
            checked={value}
            onChange={handleChange}
        />
    )
})

export default SPFCheckbox
