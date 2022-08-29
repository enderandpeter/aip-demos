import React from 'react';
import { Head } from '@inertiajs/inertia-react';
import { Wrapper, Status } from "@googlemaps/react-wrapper"
import Map from "@/Components/SearchMyBackyard/Map";

export default function SearchMyBackyard() {
    const render = (status: Status) => {
        return <h1>{status}</h1>;
    }

    return (
        <>
            <Head title="Search My Backyard" />
            <Wrapper apiKey={import.meta.env.VITE_GOOGLE_API_KEY} render={render}>
                <Map />
            </Wrapper>
        </>
    );
}
