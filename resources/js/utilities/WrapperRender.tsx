import {Status} from "@googlemaps/react-wrapper";
import {setErrorMessage} from "@/redux/error/slice";
import {BeatLoader} from "react-spinners";
import React from "react";
import {useDispatch} from "react-redux";

export const render = (status: Status) => {
    const dispatch = useDispatch()

    let displayStatus
    switch(status){
        case Status.LOADING:
            displayStatus = <BeatLoader color={'blue'} loading={true} />
            break;
        case Status.FAILURE:
            dispatch(setErrorMessage("Could not load Google Maps"))
            break;
    }
    return (
        <div className={'d-flex h-100 justify-content-center align-items-center'}>
          {displayStatus}
        </div>
    )
}
