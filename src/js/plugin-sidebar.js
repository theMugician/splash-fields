import { registerPlugin } from '@wordpress/plugins'
import { PluginSidebar } from '@wordpress/edit-post'
import { PanelBody } from '@wordpress/components'
import SPFCheckbox from './SPFCheckbox'
import SPFText from './SPFText'

registerPlugin('spf-sidebar', {
	render: () => (
		<PluginSidebar name='spf-sidebar' title='SPF Sidebar'>
			<PanelBody>
				{fields.map((field) => {
                    console.log(field)
					switch (field.type) {
					case 'checkbox':
						return (
						    <SPFCheckbox key={field.id} label={field.name} metaKey={field.id} />
						)
					case 'checkbox-list':
						return (
						    <SPFCheckboxList key={field.id} label={field.name} metaKey={field.id} options={field.options} />
						)
                    case 'editor':
                        return (
                            <SPFEditor key={field.id} label={field.name} metaKey={field.id} />
                        )
                    case 'file':
                        return (
                            <SPFFile key={field.id} label={field.name} metaKey={field.id} allowedTypes={field.allowedTypes} />
                        )
                    case 'image':
                        return (
                            <SPFImage key={field.id} label={field.name} metaKey={field.id} allowedTypes={field.allowedTypes} />
                        )
                    case 'radio':
                        return (
                            <SPFRadio key={field.id} label={field.name} metaKey={field.id} options={field.options} />
                        )
                    case 'select':
                        return (
                            <SPFSelect key={field.id} label={field.name} metaKey={field.id} options={field.options} />
                        )
                    case 'text':
                        return (
                            <SPFText key={field.id} label={field.name} metaKey={field.id} />
                        )
                    case 'textarea':
                        return (
                            <SPFTextarea key={field.id} label={field.name} metaKey={field.id} />
                        )
						// Add more cases here for other field types (e.g., text, select)
					default:
						return null
					}
				})}
			</PanelBody>

		</PluginSidebar>
	)
})