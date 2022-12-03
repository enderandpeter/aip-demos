import React, {useEffect, useRef, useState} from "react";
import {useSelector, useDispatch} from "react-redux";
import {errorMessage} from "@/redux/error/slice";
import { addGeoLocation, geolocations } from "@/redux/geolocations/slice"
import ErrorDialog from "@/Components/SearchMyBackyard/ErrorDialog";
import {defaultCenter, findGeolocation, locationCenter} from "@/redux/location/slice";
import UiControls from "@/Components/SearchMyBackyard/UiControls";
import IntroDialog from "@/Components/SearchMyBackyard/IntroDialog";
import MapMouseEvent = google.maps.MapMouseEvent;
import Marker = google.maps.Marker;

export interface CanSetMarkers {
    setMarkers:  React.Dispatch<React.SetStateAction<SMBMarker[]>>
}

export interface SMBMarker extends Marker {
    showInList: boolean;
    selected: boolean;
}

export default () => {
    const dispatch = useDispatch();
    const ref = useRef<HTMLDivElement>(null)
    const [ map, setMap ] = useState<google.maps.Map>();
    const [ markers, setMarkers ] = useState<SMBMarker[]>([]);

    const message = useSelector(errorMessage)

    const center = useSelector(locationCenter)

    const userLocations = useSelector(geolocations);

    const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    useEffect(() => {
        if(center.lat === defaultCenter.lat && center.lng === defaultCenter.lng){
            // @ts-ignore
            dispatch(findGeolocation())
        }

        if (ref.current && !map) {

            setMap( () => {
                let labelIndex = 0;
                // @ts-ignore
                const newMap = new window.google.maps.Map(ref.current, {
                    center,
                    zoom : 10,
                    zoomControl : true,
                    mapTypeControl : true,
                    mapTypeControlOptions : {
                        style : window.google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    scaleControl : true,
                    streetViewControl : true,
                    rotateControl : true,
                    fullscreenControl : true,
                    fullscreenControlOptions : {
                        position : window.google.maps.ControlPosition.BOTTOM_CENTER
                    }
                })

                const mapMarkerListener = (e: MapMouseEvent) => {
                    if(e.latLng){
                        let newMarker: SMBMarker | null = null

                        dispatch(addGeoLocation({
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng()
                        }))

                        setMarkers((markers) => {
                            labelIndex = markers.length

                            if(!newMarker){
                                newMarker = new google.maps.Marker({
                                    position: e.latLng,
                                    map: newMap,
                                    label : labels[labelIndex % labels.length]
                                }) as SMBMarker
                            }

                            newMarker.showInList = true
                            newMarker.selected = false

                            return [
                                ...markers,
                                newMarker,
                            ]
                        })
                    }
                }

                newMap.addListener('click', mapMarkerListener)

                return newMap;
            });
        } else if (ref.current && map){

            map.setCenter(center)
        }
    }, [ref, map, center])

    useEffect(() => {
        setMarkers((prevMarkers) => {
            return userLocations.map((location) => {
                let mappedMarker: SMBMarker
                const hasMarker = prevMarkers.some((marker) => {
                    let hasMarker = location.lat === marker.getPosition()!.lat() && location.lng === marker.getPosition()!.lng()

                    if(hasMarker){
                        mappedMarker = marker
                    }
                    return hasMarker
                })

                return mappedMarker!
            })

        })
    }, [ userLocations ])

    return (
        <>
            <ErrorDialog message={message} />
            <IntroDialog />
            <div id={'map'} ref={ref} />
            <UiControls markers={markers} setMarkers={setMarkers} />
        </>
    )
}
