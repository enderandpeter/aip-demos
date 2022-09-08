import React, {useEffect, useRef, useState} from "react";
import {useSelector, useDispatch} from "react-redux";
import {errorMessage} from "@/redux/error/slice";
import ErrorDialog from "@/Components/SearchMyBackyard/ErrorDialog";
import {defaultCenter, findGeolocation, locationCenter} from "@/redux/location/slice";

export default () => {
    const dispatch = useDispatch();
    const ref = useRef<HTMLDivElement>(null)
    const [ map, setMap ] = useState<google.maps.Map>();

    const message = useSelector(errorMessage)

    const center = useSelector(locationCenter)

    useEffect(() => {
        if(center.lat === defaultCenter.lat && center.lng === defaultCenter.lng){
            // @ts-ignore
            dispatch(findGeolocation())
        }

        if (ref.current && !map) {

            setMap(new window.google.maps.Map(ref.current, {
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
            }));
        } else if (ref.current && map){
            map.setCenter(center)
        }
    }, [ref, map, center])

    return (
        <>
            <ErrorDialog message={message} />
            <div id={'map'} ref={ref} />
        </>
    )
}
