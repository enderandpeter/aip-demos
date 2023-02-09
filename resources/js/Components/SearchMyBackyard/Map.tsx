import React, {useEffect, useRef, useState} from "react";
import { createRoot } from 'react-dom/client';
import {useSelector, useDispatch} from "react-redux";
import {errorMessage} from "@/redux/error/slice";
import {addGeoLocation, controlGeoLocation, editGeoLocation, geolocations} from "@/redux/geolocations/slice"
import ErrorDialog from "@/Components/SearchMyBackyard/ErrorDialog";
import {defaultCenter, findGeolocation, locationCenter} from "@/redux/location/slice";
import UiControls from "@/Components/SearchMyBackyard/UiControls";
import IntroDialog from "@/Components/SearchMyBackyard/IntroDialog";
import MapMouseEvent = google.maps.MapMouseEvent;
import Marker = google.maps.Marker;
import InfoWindow from "@/Components/SearchMyBackyard/InfoWindow";
import {v4 as uuidv4} from 'uuid';

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
}
export interface SMBMarker extends SMBMarkerProps, Marker {
    description: string;
    goToLocation: () => void;
    updateInfowindow: () => void;
    openInfowindow: () => void;

    initialized: boolean;
}

export default () => {
    const dispatch = useDispatch();
    const ref = useRef<HTMLDivElement>(null)
    const infowindowRef = useRef<google.maps.InfoWindow>(new google.maps.InfoWindow)
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
                    if(e.latLng){ // The presence of this indicates the user click on a charted location

                        const sv = new google.maps.StreetViewService()

                        setMarkers((markers) => {
                            // See if a marker at this location is already present
                            let newMarker: SMBMarker | undefined = markers.find((marker) => {
                                return marker.getPosition()?.lat() === e.latLng!.lat()
                                    && marker.getPosition()?.lng() === e.latLng!.lng()
                            })

                            if(!newMarker){
                                // A marker at the click location was not found, so make a new one

                                labelIndex = markers.length

                                let label = labels[labelIndex % labels.length]

                                newMarker = new google.maps.Marker({
                                    position: e.latLng,
                                    map: newMap,
                                    label
                                }) as SMBMarker

                                newMarker.id = uuidv4()
                                newMarker.showInList = true;
                                newMarker.selected = false;
                                newMarker.hovering = false;
                                newMarker.editing = false;

                                newMarker.goToLocation = () => {
                                    (newMarker!.getMap() as google.maps.Map).panTo(newMarker!.getPosition()!)
                                    newMarker!.updateInfowindow()
                                    newMarker!.openInfowindow()
                                }
                                newMarker.updateInfowindow = () => {
                                    const container = document.createElement('div')
                                    container.id = 'infowindowContainer'
                                    const root = createRoot(container)
                                    root.render(<InfoWindow marker={newMarker!}/>)
                                    infowindowRef.current.setContent(container)
                                }
                                newMarker.openInfowindow = () => {
                                    infowindowRef.current.open(newMarker!.getMap(), newMarker)
                                    newMarker!.getMap()!.setOptions({gestureHandling : 'cooperative'});
                                }

                                newMarker.addListener('mouseover', (e: MapMouseEvent) => {
                                    newMarker!.hovering = true
                                    setMarkers( (prevMarkers) => [ ...prevMarkers])
                                })
                                newMarker.addListener('mouseout', (e: MapMouseEvent) => {
                                    newMarker!.hovering = false
                                    setMarkers( (prevMarkers) => [ ...prevMarkers])
                                })

                                newMarker.addListener('click', (e: MapMouseEvent) => {
                                    newMarker!.goToLocation()
                                })

                                sv.getPanorama({location: e.latLng})
                                    // @ts-ignore
                                    .then(({data: {location: {description}}}: google.maps.StreetViewResponse) => {
                                        newMarker!.description = description
                                    }).catch((e) => {

                                })
                            }

                            if(newMarker){
                                return [
                                    ...markers,
                                    newMarker,
                                ]
                            } else {
                                return [
                                    ...markers,
                                ]
                            }
                        })
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
    }, [ref, map, center])

    useEffect(() => {
        setMarkers((markers) => {
            /*
            React to changes in userLocations
             */
            let currentMarkers = markers.filter((marker) => {
                const gLocation = userLocations.find((location) => location.id === marker.id)

                if(gLocation === undefined){
                    // This marker is being removed
                    marker.setMap(null)
                } else {
                    // This gLocation corresponds with an existing marker
                    const {
                        label,
                        editing,
                        visible,
                        hovering,
                        selected,
                        showInList,
                        callGoToLocation,
                        goToLocationCalled,
                        callOpenInfowindow,
                        openInfowindowCalled,
                        callUpdateInfowindow,
                        updateInfowindowCalled
                    } = gLocation

                    marker.setLabel(label)
                    marker.editing = editing
                    marker.setVisible(visible!)
                    marker.hovering = hovering
                    marker.selected = selected
                    marker.showInList = showInList

                    if(callGoToLocation && !goToLocationCalled){
                        marker.goToLocation();
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            goToLocationCalled: true,
                            callGoToLocation: false
                        }))
                    } else if (goToLocationCalled){
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            goToLocationCalled: false,
                        }))
                    }

                    if(callOpenInfowindow && !openInfowindowCalled){
                        marker.openInfowindow();
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            openInfowindowCalled: true,
                            callOpenInfowindow: false
                        }))
                    } else if (openInfowindowCalled){
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            openInfowindowCalled: false,
                        }))
                    }

                    if(callUpdateInfowindow && !updateInfowindowCalled){
                        marker.openInfowindow();
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            updateInfowindowCalled: true,
                            callUpdateInfowindow: false
                        }))
                    } else if (updateInfowindowCalled){
                        dispatch(controlGeoLocation({
                            id: gLocation.id,
                            updateInfowindowCalled: false,
                        }))
                    }
                }

                return !!gLocation;
            })

            return [
                ...currentMarkers
            ]
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
    }, [markers])

    useEffect(() => {

        setMarkers((markers) => {
            const someUninitialized = markers.some((marker) => !marker.initialized)

            if(someUninitialized){
                return markers.filter((marker) => !marker.initialized)
                    .map((marker) => {
                        dispatch(addGeoLocation({
                            id: marker.id,
                            location: {
                                lat: marker.getPosition()!.lat(),
                                lng: marker.getPosition()!.lng()
                            },
                            showInList: marker.showInList,
                            selected: marker.selected,
                            hovering: marker.hovering,
                            editing: marker.editing,
                            label: marker.getLabel()!.toString(),
                        }))

                        marker.initialized = true

                        return marker
                    })
            } else {
                return markers
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
