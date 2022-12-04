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
                    let click = true;

                    setMarkers((prevMarkers) => {
                        if(click){
                            const atLeastOneSelected = prevMarkers.some((marker) => marker.selected)
                            if(atLeastOneSelected){
                                prevMarkers.forEach((marker) => marker.selected = false)
                            } else {
                                prevMarkers.forEach((marker) => marker.selected = true)
                            }

                            click = false
                        }

                        return [
                        ...prevMarkers
                    ]})
                }}
        >
            <CheckBoxOutlineBlankIcon />
        </button>
    )
}
