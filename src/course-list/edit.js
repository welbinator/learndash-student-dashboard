import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import './editor.scss';

export default function Edit() {
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
            { content ? (
                <div dangerouslySetInnerHTML={{ __html: content }} />
            ) : (
                <p>{ __( 'Loading...', 'ldsd' ) }</p>
            )}
        </div>
    );
}
