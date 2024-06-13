import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';

export default function Edit() {
    const blockProps = useBlockProps();

    return (
        <div { ...blockProps }>
            <h2>{ __( 'Course List', 'ldsd' ) }</h2>
            <ul>
                <li>{ __( 'Course 1', 'ldsd' ) }</li>
                <li>{ __( 'Course 2', 'ldsd' ) }</li>
                <li>{ __( 'Course 3', 'ldsd' ) }</li>
            </ul>
        </div>
    );
}
