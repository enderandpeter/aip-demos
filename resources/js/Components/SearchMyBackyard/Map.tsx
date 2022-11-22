import React, {useEffect, useRef, useState} from "react";
import {useSelector, useDispatch} from "react-redux";
import {errorMessage} from "@/redux/error/slice";
import { addGeoLocation, geolocations } from "@/redux/geolocations/slice"
import ErrorDialog from "@/Components/SearchMyBackyard/ErrorDialog";
import {defaultCenter, findGeolocation, locationCenter} from "@/redux/location/slice";
import UiControls from "@/Components/SearchMyBackyard/UiControls";
import IntroDialog from "@/Components/SearchMyBackyard/IntroDialog";
import MapMouseEvent = google.maps.MapMouseEvent;

export default () => {
    const dispatch = useDispatch();
    const ref = useRef<HTMLDivElement>(null)
    const [ map, setMap ] = useState<google.maps.Map>();
    const [ markers, setMarkers ] = useState<google.maps.Marker[]>([]);

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
                        dispatch(addGeoLocation({
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng()
                        }))

                        setMarkers((markers) => {
                            labelIndex = markers.length
                            return [
                                ...markers,
                                new google.maps.Marker({
                                    position: e.latLng,
                                    map: newMap,
                                    label : labels[labelIndex % labels.length]
                                }),
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

            return prevMarkers.filter((marker) => userLocations.some((loc) => loc.lat === marker.getPosition()!.lat() && loc.lng === marker.getPosition()!.lng()))
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
