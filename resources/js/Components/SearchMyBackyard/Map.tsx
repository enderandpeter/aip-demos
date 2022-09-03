import React, {useEffect, useRef, useState} from "react";

export default () => {
    const ref = useRef<HTMLDivElement>(null)
    const [ map, setMap ] = useState<google.maps.Map>();

    const defaultCenter = new window.google.maps.LatLng(44.540, -78.546)

    useEffect(() => {
        if (ref.current && !map) {
            setMap(new window.google.maps.Map(ref.current, {
                center: defaultCenter,
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
        }
    }, [ref, map])

    return <div id={'map'} ref={ref} />
}
