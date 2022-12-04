import React, {useRef} from 'react'

import {Visibility as VisibilityIcon, VisibilityOff as VisibilityOffIcon} from "@mui/icons-material";
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";

export interface ShowAndHideSelectedButtonProps extends CanSetMarkers {
    markers: SMBMarker[]
}

export default ({markers, setMarkers}: ShowAndHideSelectedButtonProps) => {
    const showAllRef = useRef(true);

    return (
        <div className="button_container">
            <button title="Hide selected markers"
                    className="btn btn-light"
                    onClick={(e) => {
                        e.preventDefault()

                        let click = true

                        setMarkers((prevMarkers) => {
                            if(click){
                                const selectedMarkers = prevMarkers.filter((marker) => marker.selected)
                                showAllRef.current = selectedMarkers.some((marker) => marker.getVisible())

                                selectedMarkers.forEach((marker) => marker.setVisible(!showAllRef.current))

                                click = false
                            }

                            return [
                                ...prevMarkers
                            ]
                        })
                    }}
            >
                {
                    showAllRef.current ? <VisibilityIcon /> : <VisibilityOffIcon />
                }
            </button>
        </div>
    )
}
