import React from 'react'

import DeleteIcon from '@mui/icons-material/Delete';
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";
import {useDispatch} from "react-redux";
import {removeGeolocation} from "@/redux/geolocations/slice";

export interface DeleteSelectedButtonProps extends CanSetMarkers {
    markers: SMBMarker[]
}

export default ({markers, setMarkers}: DeleteSelectedButtonProps) => {
    const dispatch = useDispatch()

    return (
        <button title="Remove selected markers"
                className="btn btn-light"
                onClick={(e) => {
                    e.preventDefault()
                    markers.filter((marker) => marker.selected).forEach((marker) => {
                        marker.setMap(null)
                        dispatch(removeGeolocation({
                            lat: marker.getPosition()!.lat(),
                            lng: marker.getPosition()!.lng()
                        }))
                    })
                }}
        >
            <DeleteIcon />
        </button>
    )
}
