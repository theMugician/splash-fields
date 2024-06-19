import { TextControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState, useEffect } from '@wordpress/element'

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
    const [value, setValue] = useState(() => {
        if (typeof props.value !== 'undefined') {
            return props.value
        }
        return props.metaValue || props.default || ''
    })

    useEffect(() => {
        if (value === '' && isInitialLoad && typeof props.default !== 'undefined') {
            setValue(props.default)
        }
        setIsInitialLoad(false)
    }, [isInitialLoad, value, props.default])

    const handleChange = (newValue) => {
        setValue(newValue)
        if (newValue === '') {
            props.deleteMetaValue()
        } else {
            props.setMetaValue(newValue)
        }
    }

    // Custom change handler that always updates local state
    const handleChangeWithProps = (newValue) => {
        setValue(newValue)
        if (typeof props.onChange !== 'undefined') {
            props.onChange(newValue)
        } else {
            handleChange(newValue)
        }
    }

    return (
        <TextControl
            type='text'
            label={props.label}
            value={value}
            onChange={handleChangeWithProps}
        />
    )
})

export default SPFText
