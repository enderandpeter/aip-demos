import React from 'react';
import {Head} from '@inertiajs/inertia-react';
import {Status, Wrapper} from "@googlemaps/react-wrapper"
import Map from "@/Components/SearchMyBackyard/Map";
import {BeatLoader} from "react-spinners";

export default function SearchMyBackyard() {
    const render = (status: Status) => {
        let displayStatus = null
        switch(status){
            case Status.LOADING:
                displayStatus = <BeatLoader color={'blue'} loading={true} />
                break;
            case Status.FAILURE:
                displayStatus =  <h1>Could not load the map!</h1>
                break;
        }
        return (
            <div className={'d-flex h-100 justify-content-center align-items-center'}>
                {displayStatus}
            </div>
        )
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
