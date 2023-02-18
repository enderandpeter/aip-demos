import React from 'react'
import CheckBoxOutlineBlankIcon from '@mui/icons-material/CheckBoxOutlineBlank';
import {useDispatch} from "react-redux";
import { toggleSelectAll } from "@/redux/geolocations/slice"

export default () => {
    const dispatch = useDispatch();
    return (
        <button type="submit"
                title="Clear/select markers"
                className="btn btn-light"
                onClick={(e) => {
                    e.preventDefault()

                    dispatch(toggleSelectAll())

                }}
        >
            <CheckBoxOutlineBlankIcon />
        </button>
    )
}
