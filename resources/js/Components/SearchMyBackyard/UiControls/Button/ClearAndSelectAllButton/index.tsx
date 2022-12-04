import React from 'react'
import CheckBoxOutlineBlankIcon from '@mui/icons-material/CheckBoxOutlineBlank';
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";

export interface ClearAndSelectAllButtonProps extends CanSetMarkers {
    markers: SMBMarker[]
}

export default ({markers, setMarkers}: ClearAndSelectAllButtonProps) => {

    return (
        <button type="submit"
                title="Clear/select markers"
                className="btn btn-light"
                onClick={(e) => {
                    e.preventDefault()

                    const atLeastOneSelected = markers.some((marker) => marker.selected)
                    if(atLeastOneSelected){
                        markers.forEach((marker) => marker.selected = false)
                    } else {
                        markers.forEach((marker) => marker.selected = true)
                    }
                    setMarkers(() => [
                        ...markers
                    ])
                }}
        >
            <CheckBoxOutlineBlankIcon />
        </button>
    )
}
