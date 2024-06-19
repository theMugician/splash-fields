import { Button } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

import SPFCheckbox from './SPFCheckbox'
import SPFCheckboxList from './SPFCheckboxList'
import SPFEditor from './SPFEditor'
import SPFFile from './SPFFile'
import SPFImage from './SPFImage'
import SPFRadio from './SPFRadio'
import SPFSelect from './SPFSelect'
import SPFText from './SPFText'
import SPFTextarea from './SPFTextarea'
import SPFNumber from './SPFNumber'

/**
 * SPFRepeater Component
 *
 * This component provides a repeater field for WordPress, allowing users to dynamically add, edit, and remove items.
 *
 * @param {Object}   props              - The component props.
 * @param {string}   props.label        - The label for the repeater field.
 * @param {string}   props.metaKey      - The meta key used to store the repeater field data.
 * @param {Array}    props.metaValue    - The initial value of the repeater field, parsed from a JSON string.
 * @param {Array}    props.fields       - The fields to be added in each repeater item.
 * @param {function} props.setMetaValue - Function to update the meta value.
 * @return {JSX.Element}                - The rendered component.
 */
const SPFRepeater = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: JSON.stringify(value) } })
            }
        }
    }),
    withSelect((select, props) => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || '[]'
        return {
            metaValue: JSON.parse(metaValue)
        }
    })
)((props) => {
    const [items, setItems] = useState(props.metaValue)

    useEffect(() => {
        setItems(props.metaValue)
    }, [props.metaValue])

    const handleFieldChange = (index, fieldId, value) => {
        const newItems = [...items]
        newItems[index][fieldId].value = value
        setItems(newItems)
        props.setMetaValue(newItems)
    }

    const addItem = () => {
        const newItem = props.fields.reduce((acc, field) => {
            acc[field.id] = { type: field.type, value: field.default || '' }
            return acc
        }, {})
        const newItems = [...items, newItem]
        setItems(newItems)
        props.setMetaValue(newItems)
    }

    const removeItem = (index) => {
        const newItems = items.filter((_, i) => i !== index)
        setItems(newItems)
        props.setMetaValue(newItems)
    }

    const renderComponent = (item, index) => {
        return (
            <div key={index} style={{ marginBottom: '10px' }}>
                {props.fields.map(field => {
                    const fieldProps = {
                        key: `${props.metaKey}-${index}-${field.id}`,
                        label: field.label,
                        value: item[field.id] ? item[field.id].value : '',
                        onChange: (value) => handleFieldChange(index, field.id, value),
                        ...(field.default !== undefined && { default: field.default })
                    }

                    switch (field.type) {
                        case 'checkbox':
                            return <SPFCheckbox {...fieldProps} />
                        case 'checkbox-list':
                            return <SPFCheckboxList {...fieldProps} options={field.options} />
                        case 'editor':
                            return <SPFEditor {...fieldProps} />
                        case 'file':
                            return <SPFFile {...fieldProps} allowedTypes={field.allowedTypes} />
                        case 'image':
                            return <SPFImage {...fieldProps} allowedTypes={field.allowedTypes} />
                        case 'radio':
                            return <SPFRadio {...fieldProps} options={field.options} />
                        case 'select':
                            return <SPFSelect {...fieldProps} options={field.options} />
                        case 'text':
                            return <SPFText {...fieldProps} />
                        case 'textarea':
                            return <SPFTextarea {...fieldProps} />
                        case 'number':
                            return <SPFNumber {...fieldProps} />
                        default:
                            return <TextControl {...fieldProps} />
                    }
                })}
                <Button isDestructive onClick={() => removeItem(index)}>
                    {__('Remove', 'text-domain')}
                </Button>
            </div>
        )
    }

    return (
        <div>
            {props.label && <label>{props.label}</label>}
            {items.map((item, index) => renderComponent(item, index))}
            <Button variant='isPrimary' onClick={addItem}>
                {__('Add Item', 'text-domain')}
            </Button>
        </div>
    )
})

export default SPFRepeater
