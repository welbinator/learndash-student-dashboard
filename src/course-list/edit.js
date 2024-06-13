import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps();
    const [content, setContent] = useState('');

    useEffect(() => {
        // Fetch the server-side rendered content with the context parameter set to 'edit'
        wp.apiFetch({ path: '/wp/v2/block-renderer/ldsd/course-list?context=edit' }).then((response) => {
            console.log('API fetch response:', response); // Add logging here
            setContent(response.rendered);
        }).catch((error) => {
            console.error('Error fetching server-side rendered content:', error);
        });
    }, []);

    return (
        <div { ...blockProps }>
            <InspectorControls>
                <PanelBody title={ __('Block Settings', 'ldsd') }>
                    <TextControl
                        label={ __('Block Title', 'ldsd') }
                        value={ attributes.blockTitle }
                        onChange={(value) => setAttributes({ blockTitle: value })}
                    />
                </PanelBody>
            </InspectorControls>
            { content ? (
                <div dangerouslySetInnerHTML={{ __html: content }} />
            ) : (
                <p>{ __( 'Loading...', 'ldsd' ) }</p>
            )}
        </div>
    );
}
