import React, {useCallback, useEffect, useRef, useState} from "react";
import { createRoot } from 'react-dom/client';
import {useSelector, useDispatch, Provider} from "react-redux";
import {errorMessage} from "@/redux/error/slice";
import {
    addGeoLocation,
    controlGeoLocation,
    editGeoLocation,
    GeoLocationData,
    geolocations
} from "@/redux/geolocations/slice"
import ErrorDialog from "@/Components/SearchMyBackyard/ErrorDialog";
import {defaultCenter, findGeolocation, locationCenter} from "@/redux/location/slice";
import UiControls from "@/Components/SearchMyBackyard/UiControls";
import IntroDialog from "@/Components/SearchMyBackyard/IntroDialog";
import MapMouseEvent = google.maps.MapMouseEvent;
import Marker = google.maps.Marker;
import InfoWindow from "@/Components/SearchMyBackyard/InfoWindow";
import {v4 as uuidv4} from 'uuid';
import store from "@/redux/store";

export interface CanSetMarkers {
    setMarkers:  React.Dispatch<React.SetStateAction<SMBMarker[]>>
}

/**
 * Search My Backyard marker
 */
export interface SMBMarkerProps {
    id: string;
    showInList: boolean;
    selected: boolean;
    hovering: boolean;
    editing: boolean;
    pano?: string;
}
export interface SMBMarker extends SMBMarkerProps, Marker {
    description: string;
    goToLocation: () => void;
    updateInfowindow: () => void;
    openInfowindow: () => void;
    callGoToLocation?: boolean;
    callUpdateInfowindow?: boolean;
    callOpenInfowindow?: boolean;
    initialized: boolean;
}
let markerCreated = false
export default () => {
    const dispatch = useDispatch();
    const ref = useRef<HTMLDivElement>(null)
    const infowindowRef = useRef<google.maps.InfoWindow>(new google.maps.InfoWindow)
    const [ map, setMap ] = useState<google.maps.Map>();
    const [ markers, setMarkers ] = useState<SMBMarker[]>([]);

    const message = useSelector(errorMessage)

    const center = useSelector(locationCenter)

    const userLocations = useSelector(geolocations);

    useEffect(() => {
        if(center.lat === defaultCenter.lat && center.lng === defaultCenter.lng){
            // @ts-ignore
            dispatch(findGeolocation())
        }

        if (ref.current && !map) {

            setMap( () => {
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
                    if(e.latLng){ // The presence of this indicates the user clicked on a charted location
                        // Create the location that will soon correspond with a marker

                        dispatch(addGeoLocation({
                            id: uuidv4(),
                            location: {
                                lat: e.latLng.lat(),
                                lng: e.latLng.lng()
                            },
                            showInList: true,
                            selected: false,
                            hovering: false,
                            editing: false,
                        }))
                    }
                }

                newMap.addListener('click', mapMarkerListener)

                infowindowRef.current.addListener('closeclick', () => {
                    newMap.setOptions({gestureHandling : 'auto'});
                });

                return newMap;
            });
        } else if (ref.current && map){

            map.setCenter(center)
        }
    }, [ref, map, center, userLocations])


    useEffect(() => {
        let newMarker: SMBMarker | undefined;

        // Edit these markers based on changes to corresponding GeoLocationData
        setMarkers((prevMarkers) => {

            // Remove markers not listed in userLocations
            prevMarkers.filter((marker) => {
                const foundMarker = userLocations.find((gLocation: GeoLocationData) => gLocation.id === marker.id)

                return foundMarker === undefined
            }).forEach((marker) => {
                marker.setMap(null)
            })

            return userLocations.map((gLocation: GeoLocationData) => {
                let marker = prevMarkers.find((marker) => marker.id === gLocation.id) ?? newMarker

                if(marker === undefined) {
                    // create this marker
                    const sv = new google.maps.StreetViewService()

                    marker = new google.maps.Marker({
                        position: gLocation.location,
                        map,
                        label: gLocation.label
                    }) as SMBMarker

                    newMarker = marker

                    marker.id = gLocation.id
                    marker.showInList = gLocation.showInList;
                    marker.selected = gLocation.selected;
                    marker.hovering = gLocation.hovering;
                    marker.editing = gLocation.editing;

                    sv.getPanorama({location: gLocation.location})
                        // @ts-ignore
                        .then(({data: {location: {description, pano}}}: google.maps.StreetViewResponse) => {
                            marker!.description = description
                            marker!.pano = pano;
                        }).catch((e) => {

                    })

                    marker.goToLocation = () => {
                        (marker!.getMap() as google.maps.Map).panTo(marker!.getPosition()!)
                        marker!.updateInfowindow()
                        marker!.openInfowindow()
                    }
                    marker.updateInfowindow = () => {
                        const container = document.createElement('div')
                        container.id = 'infowindowContainer'
                        const root = createRoot(container)
                        root.render(
                            <Provider store={store}>
                                <InfoWindow marker={marker!}/>
                            </Provider>
                        )
                        infowindowRef.current.setContent(container)
                    }
                    marker.openInfowindow = () => {
                        infowindowRef.current.open(marker!.getMap(), marker)
                        marker!.getMap()!.setOptions({gestureHandling: 'cooperative'});
                    }

                    marker.addListener('mouseover', (e: MapMouseEvent) => {
                        dispatch(editGeoLocation({
                            id: marker!.id,
                            hovering: true
                        }))
                    })
                    marker.addListener('mouseout', (e: MapMouseEvent) => {
                        dispatch(editGeoLocation({
                            id: marker!.id,
                            hovering: false
                        }))
                    })

                    marker.addListener('click', (e: MapMouseEvent) => {
                        marker!.goToLocation()
                    })
                } else {
                    // edit this marker

                    // This gLocation corresponds with an existing marker
                    const {
                        label,
                        editing,
                        visible,
                        hovering,
                        selected,
                        showInList,
                        callGoToLocation,
                        callOpenInfowindow,
                        callUpdateInfowindow,
                    } = gLocation

                    marker.setLabel(label)
                    marker.editing = editing
                    marker.setVisible(visible!)
                    marker.hovering = hovering
                    marker.selected = selected
                    marker.showInList = showInList

                    marker.callGoToLocation = !!callGoToLocation;

                    marker.callOpenInfowindow = !!callOpenInfowindow;

                    marker.callUpdateInfowindow = !!callUpdateInfowindow;
                }

                return marker!
            })
        })

    }, [ userLocations, setMarkers ])

    useEffect( () => {
        if(markers){
            markers.forEach((marker) => {
                if(marker.hovering){
                    marker.setAnimation(google.maps.Animation.BOUNCE)
                } else {
                    marker.setAnimation(null)
                }
            })
        }

        markers.forEach((marker) => {
            if(marker.callOpenInfowindow){
                marker.openInfowindow()

                dispatch(controlGeoLocation({
                    id: marker.id,
                    callOpenInfowindow: false,
                }))
            }

            if(marker.callGoToLocation){
                marker.goToLocation()

                dispatch(controlGeoLocation({
                    id: marker.id,
                    callGoToLocation: false,
                }))
            }

            if(marker.callUpdateInfowindow){
                marker.updateInfowindow()

                dispatch(controlGeoLocation({
                    id: marker.id,
                    callUpdateInfowindow: false,
                }))
            }
        })
    }, [markers])

    return (
        <>
            <ErrorDialog message={message} />
            <IntroDialog />
            <div id={'map'} ref={ref} />
            <UiControls />
        </>
    )
}
