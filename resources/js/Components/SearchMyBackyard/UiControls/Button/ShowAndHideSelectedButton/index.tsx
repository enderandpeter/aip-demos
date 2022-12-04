import React, {useRef, useState} from 'react'

import {Visibility as VisibilityIcon, VisibilityOff as VisibilityOffIcon} from "@mui/icons-material";
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";

export interface ShowAndHideSelectedButtonProps extends CanSetMarkers {
    markers: SMBMarker[]
}

export default ({markers, setMarkers}: ShowAndHideSelectedButtonProps) => {
    const showAllRef = useRef(true);

    return (
        <div data-bind="if: canHideAllSelection()" className="button_container">
            <button title="Hide selected markers"
                    className="btn btn-light"
                    onClick={(e) => {
                        e.preventDefault()

                        let click = true

                        setMarkers((prevMarkers) => {
                            if(click){
                                showAllRef.current = prevMarkers.some((marker) => marker.getVisible())

                                prevMarkers.forEach((marker) => marker.setVisible(!showAllRef.current))

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
