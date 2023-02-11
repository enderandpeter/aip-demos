import React, {useEffect, useState} from 'react'
import {SMBMarker} from "@/Components/SearchMyBackyard/Map";
import Yelp from "@/Components/SearchMyBackyard/InfoWindow/Yelp";

export interface InfoWindowProps {
    marker: SMBMarker
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
                    <li className="nav-item">
                        <a
                            className={`nav-link ${activeTab === 'yelp' ? 'active' : ''}`}
                            aria-current={`${activeTab === 'yelp' ? 'page' : 'false'}`} href="#"
                            onClick={(e) => setTab('yelp')}
                        >
                            Yelp
                        </a>
                    </li>
                </ul>
                <div className={'infowindow_content'}>
                    {
                        activeTab === 'yelp' ? <Yelp marker={marker} /> : null
                    }
                </div>
            </header>
        </div>
    )
}
