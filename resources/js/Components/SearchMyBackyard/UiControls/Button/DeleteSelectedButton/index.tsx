import React from 'react'

import DeleteIcon from '@mui/icons-material/Delete';
import {useDispatch} from "react-redux";
import {removeSelectedGeolocations} from "@/redux/geolocations/slice";

export default () => {
    const dispatch = useDispatch()

    return (
        <button title="Remove selected markers"
                className="btn btn-light"
                onClick={(e) => {
                    e.preventDefault()
                    dispatch(removeSelectedGeolocations())
                }}
        >
            <DeleteIcon />
        </button>
    )
}
