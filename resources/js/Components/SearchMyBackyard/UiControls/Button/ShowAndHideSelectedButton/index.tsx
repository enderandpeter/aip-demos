import React, {useRef} from 'react'

import {Visibility as VisibilityIcon, VisibilityOff as VisibilityOffIcon} from "@mui/icons-material";
import {useDispatch, useSelector} from "react-redux";
import {toggleVisible, atLeastOneVisible} from "@/redux/geolocations/slice";

export default () => {
    const dispatch = useDispatch()
    const oneVisible = useSelector(atLeastOneVisible)

    return (
        <div className="button_container">
            <button title="Hide selected markers"
                    className="btn btn-light"
                    onClick={(e) => {
                        e.preventDefault()

                        dispatch(toggleVisible(null))
                    }}
            >
                {
                    oneVisible ? <VisibilityOffIcon /> : <VisibilityIcon />
                }
            </button>
        </div>
    )
}
