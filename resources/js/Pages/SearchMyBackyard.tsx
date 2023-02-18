import React from 'react';
import { Head } from '@inertiajs/inertia-react';
import { Wrapper } from "@googlemaps/react-wrapper"
import Map from "@/Components/SearchMyBackyard/Map";
import {render} from "@/utilities/WrapperRender";

export default function SearchMyBackyard() {
    return (
        <>
            <Head title="Search My Backyard" />
            <Wrapper apiKey={import.meta.env.VITE_GOOGLE_API_KEY} render={render}>
                <Map />
            </Wrapper>
        </>
    );
}
