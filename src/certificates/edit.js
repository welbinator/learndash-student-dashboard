import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, PanelRow } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const { blockTitle } = attributes;
    const blockProps = useBlockProps();
    const [content, setContent] = useState('');

    useEffect(() => {
        wp.apiFetch({ path: '/wp/v2/block-renderer/ldsd/certificates?context=edit' }).then((response) => {
            setContent(response.rendered);
        }).catch((error) => {
            console.error('Error fetching server-side rendered content:', error);
        });
    }, []);

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Block Settings', 'ldsd')}>
                    <PanelRow>
                        <TextControl
                            label={__('Block Title', 'ldsd')}
                            value={blockTitle}
                            onChange={(newTitle) => setAttributes({ blockTitle: newTitle })}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            {content ? (
                <div dangerouslySetInnerHTML={{ __html: content }} />
            ) : (
                <p>{__('Loading...', 'ldsd')}</p>
            )}
        </div>
    );
}
