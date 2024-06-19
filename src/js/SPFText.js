import { TextControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState } from '@wordpress/element'
import useCommonLogic from './useCommonLogic'

const SPFText = compose(
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
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || ''
        return {
            metaValue
        }
    })
)((props) => {
    const [isInitialLoad, setIsInitialLoad] = useState(true)
    const { value, handleChange } = useCommonLogic(props, props.setMetaValue, props.deleteMetaValue, isInitialLoad, setIsInitialLoad)

    return (
        <TextControl
            type='text'
            label={props.label}
            value={value}
            onChange={handleChange}
        />
    )
})

export default SPFText
