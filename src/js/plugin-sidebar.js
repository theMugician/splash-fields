import { registerPlugin } from '@wordpress/plugins'
import { PluginSidebar } from '@wordpress/edit-post'
import { PanelBody } from '@wordpress/components'
import SPFCheckbox from './SPFCheckbox'
import SPFText from './SPFText'

registerPlugin('spf-sidebar', {
	render: () => (
		<PluginSidebar name='spf-sidebar' title='SEO'>
            <PanelBody>
                {fields.map((field) => {
                    switch (field.type) {
                        case 'checkbox':
                            <SPFCheckbox key={field.id} label={field.name} metaKey={field.id} />

                        // Add more cases here for other field types (e.g., text, select)
                        default:
                            return null
                    }
                })}
            </PanelBody>

		</PluginSidebar>
	)
})