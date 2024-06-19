import { useState, useEffect } from '@wordpress/element'

/**
 * Custom hook to handle common logic for form components.
 *
 * @param {Object} props                  - The component props.
 * @param {function} setMetaValue         - Function to set meta value.
 * @param {function} deleteMetaValue      - Function to delete meta value.
 * @param {boolean} isInitialLoad         - Flag to check initial load.
 * @returns {Object}                     - An object containing value and change handler.
 */
const useCommonLogic = (props, setMetaValue, deleteMetaValue, isInitialLoad) => {
    const [value, setValue] = useState(props.metaValue || props.default || '')

    useEffect(() => {
        if (isInitialLoad) {
            setValue(props.metaValue || props.default || '')
        }
    }, [props.metaValue, props.default, isInitialLoad])

    const handleChange = (newValue) => {
        if (newValue === '') {
            deleteMetaValue()
        } else {
            setMetaValue(newValue)
        }
        setValue(newValue)
    }

    return { value, handleChange }
}

export default useCommonLogic
