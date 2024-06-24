import { SelectControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState } from '@wordpress/element'
import useCommonLogic from './useCommonLogic'

const SPFRadio = compose(
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

    const optionsArray = Object.keys(props.options).map(key => ({
        label: props.options[key],
        value: key
    }))

    // Add placeholder option if it exists
    if (props.placeholder) {
        optionsArray.unshift({ label: props.placeholder, value: '' })
    }

    return (
        <SelectControl
            className="spf-plugin-sidebar-field"
            label={props.label}
            options={optionsArray}
            value={value}
            onChange={handleChange}
        />
    )
})

export default SPFRadio
