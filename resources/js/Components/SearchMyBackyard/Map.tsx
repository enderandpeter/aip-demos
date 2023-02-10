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
    callGoToLocation?: boolean;
    callUpdateInfowindow?: boolean;
    callOpenInfowindow?: boolean;
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
                    let markerCreated = false
                    if(e.latLng){ // The presence of this indicates the user click on a charted location

                        const sv = new google.maps.StreetViewService()

                        let newMarker: SMBMarker

                        setMarkers((markers) => {

                            if(!markerCreated){
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
                                    dispatch(editGeoLocation({
                                        id: newMarker.id,
                                        hovering: true
                                    }))
                                })
                                newMarker.addListener('mouseout', (e: MapMouseEvent) => {
                                    dispatch(editGeoLocation({
                                        id: newMarker.id,
                                        hovering: false
                                    }))
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
                                markerCreated = true
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
            return markers.filter((marker) => {
                const gLocation = userLocations.find((location) => location.id === marker.id)

                if(gLocation === undefined){
                    // This marker is being removed
                    marker.setMap(null)
                } else {

                }

                return !!gLocation;
            }).map((marker) => {
                const gLocation = userLocations.find((location) => location.id === marker.id)

                if(gLocation !== undefined){
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
                return marker
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

    useEffect(() => {

        setMarkers((markers) => {
            const someUninitialized = markers.some((marker) => !marker.initialized)

            if(someUninitialized){
                return markers.filter((marker) => !marker.initialized)
                    .map((marker) => {
                        marker.initialized = true

                        return marker
                    })
            } else {
                return markers
            }
        })


        markers.forEach((marker) => {
            if(!marker.initialized){
                const gLocation = userLocations.find((gLocation) => gLocation.id === marker.id)

                if(gLocation === undefined){
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
                }
            }
        })

    }, [markers, setMarkers])


    return (
        <>
            <ErrorDialog message={message} />
            <IntroDialog />
            <div id={'map'} ref={ref} />
            <UiControls />
        </>
    )
}
