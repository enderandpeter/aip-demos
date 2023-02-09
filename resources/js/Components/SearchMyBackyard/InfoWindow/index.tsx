import React from 'react'
import {SMBMarker} from "@/Components/SearchMyBackyard/Map";

export interface InfoWindowProps {
    marker: SMBMarker
}

export default ({marker}: InfoWindowProps) => {

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
                                data-bind="text: activeMarker().locationDescription, attr: { title: 'Close to Lat: ' + activeMarker().getPosition().lat().toPrecision(5) + ', Long: ' + activeMarker().getPosition().lng().toPrecision(5) }"
                                title={`Close to Lat: ${marker.getPosition()!.lat()}, Long: ${marker.getPosition()!.lng()}`}
                            >
                                {marker.description}
                            </div>
                        ) : (
                            <div>
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
            </header>
        </div>
    )
}
