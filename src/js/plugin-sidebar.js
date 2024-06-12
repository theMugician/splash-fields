import { registerPlugin } from '@wordpress/plugins'
import { PluginSidebar } from '@wordpress/edit-post'
import { PanelBody } from '@wordpress/components'
import SPFCheckbox from './SPFCheckbox'
import SPFCheckboxList from './SPFCheckboxList'
import SPFEditor from './SPFEditor'
// import MinimalRichText from './MinimalRichText'
import SPFFile from './SPFFile'
import SPFImage from './SPFImage'
import SPFRadio from './SPFRadio'
import SPFRepeater from './SPFRepeater'
import SPFSelect from './SPFSelect'
import SPFText from './SPFText'
import SPFTextarea from './SPFTextarea'
import SPFNumber from './SPFNumber'

// Assuming 'fields' is localized by wp_localize_script and is available globally
const fields = window.fields || []

registerPlugin('spf-sidebar', {
    render: () => (
        <PluginSidebar name='spf-sidebar' title='SPF Sidebar'>
            <PanelBody>
                {fields.map((field) => {
                    switch (field.type) {
                        case 'checkbox':
                            return <SPFCheckbox key={field.id} label={field.name} metaKey={field.id} />
                        case 'checkbox-list':
                            return <SPFCheckboxList key={field.id} label={field.name} metaKey={field.id} options={field.options} />
                        case 'editor':
                            return <SPFEditor key={field.id} label={field.name} metaKey={field.id} />
                        case 'file':
                            return <SPFFile key={field.id} label={field.name} metaKey={field.id} allowedTypes={field.allowedTypes} />
                        case 'image':
                            return <SPFImage key={field.id} label={field.name} metaKey={field.id} allowedTypes={field.allowedTypes} />
                        case 'radio':
                            return <SPFRadio key={field.id} label={field.name} metaKey={field.id} options={field.options} />
                        case 'repeater':
                            return <SPFRepeater key={field.id} label={field.name} metaKey={field.id} fields={field.fields} />
                        case 'select':
                            return <SPFSelect key={field.id} label={field.name} metaKey={field.id} options={field.options} />
                        case 'text':
                            return <SPFText key={field.id} label={field.name} metaKey={field.id} />
                        case 'textarea':
                            return <SPFTextarea key={field.id} label={field.name} metaKey={field.id} />
                        case 'number':
                            return <SPFNumber key={field.id} label={field.name} metaKey={field.id} />
                        default:
                            return null
                    }
                })}
            </PanelBody>
        </PluginSidebar>
    )
})
