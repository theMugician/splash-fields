import { registerPlugin } from '@wordpress/plugins'
import { PluginSidebar } from '@wordpress/edit-post'
import { PanelBody } from '@wordpress/components'
import SPFCheckbox from './SPFCheckbox'
import SPFCheckboxList from './SPFCheckboxList'
import SPFEditor from './SPFEditor'
import SPFFile from './SPFFile'
import SPFImage from './SPFImage'
import SPFRadio from './SPFRadio'
import SPFRepeater from './SPFRepeater'
import SPFSelect from './SPFSelect'
import SPFText from './SPFText'
import SPFTextarea from './SPFTextarea'
import SPFNumber from './SPFNumber'

const fields = (window.pluginSidebar && window.pluginSidebar.fields) || []
const { id, title } = (window.pluginSidebar && { ...window.pluginSidebar })

/**
 * Render a field component based on the field type.
 *
 * @param {Object} field                - The field configuration object.
 * @param {string} field.id             - The unique identifier for the field.
 * @param {string} field.name           - The display name for the field.
 * @param {string} field.type           - The type of the field (e.g., 'checkbox', 'text', 'select').
 * @param {Array}  [field.options]      - The options for fields like select, radio, and checkbox-list.
 * @param {string} [field.default]      - The default value for the field.
 * @param {string} [field.placeholder]  - The placeholder text for the field.
 * @param {Array}  [field.fields]       - The nested fields for the repeater field type.
 * @param {Array}  [field.allowedTypes] - The allowed file types for the file and image fields.
 * @return {JSX.Element|null} The rendered field component or null if the field type is not recognized.
 */
const renderField = (field) => {
    const fieldProps = {
        key: field.id,
        label: field.name,
        metaKey: field.id,
        ...('undefined' !== typeof field.default && { default: field.default }),
        ...('undefined' !== typeof field.placeholder && { placeholder: field.placeholder })
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
        case 'repeater':
            return <SPFRepeater {...fieldProps} fields={field.fields} />
        case 'select':
            return <SPFSelect {...fieldProps} options={field.options} />
        case 'text':
            return <SPFText {...fieldProps} />
        case 'textarea':
            return <SPFTextarea {...fieldProps} />
        case 'number':
            return <SPFNumber {...fieldProps} />
        default:
            return null
    }
}

registerPlugin(id, {
    render: () => (
        <PluginSidebar name={id} title={title}>
            <PanelBody>
                {fields.map(renderField)}
            </PanelBody>
        </PluginSidebar>
    )
})
