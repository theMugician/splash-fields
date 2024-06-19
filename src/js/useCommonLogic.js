import { useState, useEffect } from '@wordpress/element'

/**
 * Custom hook for handling common logic for value and change events.
 *
 * @param {Object}   props            - The component props.
 * @param {Function} setMetaValue     - Function to update the meta value in the store.
 * @param {Function} deleteMetaValue  - Function to delete the meta value in the store.
 * @param {boolean}  isInitialLoad    - Flag indicating if it is the initial load.
 * @param {Function} setIsInitialLoad - Function to set the initial load flag.
 * @return {Object} An object containing the current value and the handleChange function.
 */
const useCommonLogic = (props, setMetaValue, deleteMetaValue, isInitialLoad, setIsInitialLoad) => {
    /**
     * Initializes the value state.
     *
     * @return {string} - The initial value.
     */
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
     * Handles change events and updates the meta value.
     *
     * @param {string} newValue - The new value from the input.
     */
    const handleChange = (newValue) => {
        setValue(newValue)
        if ('' === newValue) {
            deleteMetaValue()
        } else {
            setMetaValue(newValue)
        }
    }

    /**
     * Handles change events with support for custom onChange prop.
     *
     * @param {string} newValue - The new value from the input.
     */
    const handleChangeWithProps = (newValue) => {
        setValue(newValue)
        if ('undefined' !== typeof props.onChange) {
            props.onChange(newValue)
        } else {
            handleChange(newValue)
        }
    }

    return { value, handleChange: handleChangeWithProps }
}

export default useCommonLogic
