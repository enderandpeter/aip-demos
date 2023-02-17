import React, {useEffect, useState} from 'react'
import {SMBMarker} from "@/Components/SearchMyBackyard/Map";
import Yelp from "@/Components/SearchMyBackyard/InfoWindow/Yelp";
import GoogleStreetView from "@/Components/SearchMyBackyard/InfoWindow/GoogleStreetView";
import Wikipedia from "@/Components/SearchMyBackyard/InfoWindow/Wikipedia";

export interface InfoWindowProps {
    marker: SMBMarker
}

export const services = {
    'yelp': {
        'display_name': 'Yelp'
    },
    'gsv': {
        'display_name': 'Google Street View'
    },
    'wikipedia': {
        'display_name': 'Wikipedia'
    }
}

export default ({marker}: InfoWindowProps) => {
    const [activeTab, setTab] = useState('')

    useEffect(() => {
        if(!activeTab){
            setTab('yelp')
        }
    }, [])

    return (
        <div id={'infowindow'}>
            <header id="infowindow_header">
                <h2 id="infowindow_title" className="autofilled">
                    {marker.getLabel()!.toString()}
                </h2>
                <div id="infowindow_position" className="mb-3">
                    {
                        marker.description ? (
                            <div
                                className={"marker_description_container"}
                            >
                                {marker.description}
                            </div>
                        ) : (
                            <div className={"marker_description_container"}>
                                <div className="lat">Lat: <span id="infowindow_lat" className="autofilled">
                                    {marker.getPosition()!.lat().toPrecision(5)}
                                </span>
                                </div>
                                <div className="lng">Long: <span id="infowindow_lng" className="autofilled" >
                                    {marker.getPosition()!.lng().toPrecision(5)}
                                </span>
                                </div>
                            </div>
                        )
                    }
                </div>
                <ul className="nav nav-tabs">
                    {
                        Object.keys(services).map((service) => {

                            return (
                                <li className="nav-item" key={service}>
                                    <a
                                        className={`nav-link ${activeTab === service ? 'active' : ''}`}
                                        aria-current={`${activeTab === service ? 'page' : 'false'}`} href="#"
                                        onClick={(e) => setTab(service)}
                                    >
                                        {services[service as keyof typeof services].display_name}
                                    </a>
                                </li>
                            )
                        })
                    }
                </ul>
                <div className={'infowindow_content'}>
                    <div id="location_content" className="mt-3">
                    {
                        activeTab === 'yelp' ? <Yelp marker={marker} />
                            : activeTab === 'gsv' ? <GoogleStreetView marker={marker} />
                                : activeTab === 'wikipedia' ? <Wikipedia marker={marker} /> : null
                    }
                    </div>
                </div>
            </header>
        </div>
    )
}
