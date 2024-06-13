import { useBlockProps } from '@wordpress/block-editor';
import './style.scss';

export default function save() {
    const blockProps = useBlockProps.save();

    return (
        <div { ...blockProps }>
            <h2>Course List</h2>
            <ul>
                <li>Course 1</li>
                <li>Course 2</li>
                <li>Course 3</li>
            </ul>
        </div>
    );
}
