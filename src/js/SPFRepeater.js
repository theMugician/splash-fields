import { Button, TextControl } from '@wordpress/components'
import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

const SPFRepeater = compose(
	withDispatch((dispatch, props) => {
		return {
			setMetaValue: (value) => {
				dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
			}
		}
	}),
	withSelect((select, props) => {
		return {
			metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey] || []
		}
	})
)((props) => {
	const [items, setItems] = useState(props.metaValue)

	const handleChange = (index, value) => {
		const newItems = [...items]
		newItems[index] = value
		setItems(newItems)
		props.setMetaValue(newItems)
	}

	const addItem = () => {
		const newItems = [...items, '']
		setItems(newItems)
		props.setMetaValue(newItems)
	}

	const removeItem = (index) => {
		const newItems = items.filter((_, i) => i !== index)
		setItems(newItems)
		props.setMetaValue(newItems)
	}

	return (
		<div>
			{props.label && <label>{props.label}</label>}
			{items.map((item, index) => (
				<div key={index} style={{ marginBottom: '10px' }}>
					<TextControl
						value={item}
						onChange={(value) => handleChange(index, value)}
					/>
					<Button isDestructive onClick={() => removeItem(index)}>
						{__('Remove', 'text-domain')}
					</Button>
				</div>
			))}
			<Button variant='isPrimary' onClick={addItem}>
				{__('Add Item', 'text-domain')}
			</Button>
		</div>
	)
})

export default SPFRepeater
