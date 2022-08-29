import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';

export default function SearchMyBackyard() {
    return (
        <>
            <Head title="Search My Backyard" />
            <p>Stay out of my territory.</p>
            <p>{ import.meta.env.VITE_KEY_PATH }</p>
        </>
    );
}
