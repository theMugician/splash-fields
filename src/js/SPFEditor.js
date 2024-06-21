import { withDispatch, withSelect } from '@wordpress/data'
import { compose } from '@wordpress/compose'
import { useEffect, useRef, useState } from '@wordpress/element'
import { Spinner } from '@wordpress/components'
import useCommonLogic from './useCommonLogic'

/**
 * ClassicEditor component to initialize TinyMCE editor.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.value    The content of the editor.
 * @param {Function} props.onChange Callback function to handle content change.
 * @return {JSX.Element} The ClassicEditor component.
 */
const ClassicEditor = ({ value, onChange }) => {
    const editorRef = useRef()

    useEffect(() => {
        if (window.tinymce) {
            tinymce.init({
                target: editorRef.current,
                setup: (editor) => {
                    editor.on('init', () => {
                        editor.setContent(value || '')
                    })
                    editor.on('change keyup setcontent', () => {
                        onChange(editor.getContent())
                    })
                },
                menubar: false,
                toolbar: 'formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link',
                plugins: 'link',
                block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
            })

            return () => {
                if (window.tinymce) {
                    tinymce.remove(editorRef.current)
                }
            }
        }
    }, [value])

    return (
        <div>
            {!window.tinymce && <Spinner />}
            <textarea ref={editorRef} />
        </div>
    )
}

const SPFEditor = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } })
            }
        }
    }),
    withSelect((select, props) => {
        const meta = select('core/editor').getEditedPostAttribute('meta')
        const metaValue = meta ? meta[props.metaKey] : ''
        return {
            metaValue: metaValue || '',
        }
    })
)((props) => {
    const [isInitialLoad, setIsInitialLoad] = useState(true)
    const { value, handleChange } = useCommonLogic(props, props.setMetaValue, props.deleteMetaValue, isInitialLoad, setIsInitialLoad)

    return (
        <div className="spf-plugin-sidebar-field">
            {props.label && <label>{props.label}</label>}
            <ClassicEditor
                value={value}
                onChange={handleChange}
                // value={props.metaValue}
                // onChange={(content) => props.setMetaValue(content)}
            />
        </div>
    )
})

export default SPFEditor
