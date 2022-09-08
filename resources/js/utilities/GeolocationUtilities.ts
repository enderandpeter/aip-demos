import {useDispatch} from "react-redux";
import {Dispatch} from "@reduxjs/toolkit";
import {setErrorMessage} from "@/redux/error/slice";
import {setLocationCenter} from "@/redux/location/slice";

export const setGeolocation = (dispatch: Dispatch = useDispatch()) => {
    if(!window.navigator.geolocation){
        dispatch(setErrorMessage('This browser does not support Geolocation'))
        return
    }

    const success = (position: GeolocationPosition) => {
        dispatch(setLocationCenter({lat: position.coords.latitude, lng: position.coords.longitude}))
    }

    const error = (err: GeolocationPositionError) => {
        switch (err.code) {
            case err.PERMISSION_DENIED:
                dispatch(setErrorMessage('This page does not have permission to use Geolocation'))
                break
            case err.TIMEOUT:
                dispatch(setErrorMessage('A timeout error occurred while finding the Geolocation'))
                break
            case err.POSITION_UNAVAILABLE:
                dispatch(setErrorMessage('The Geolocation is not available at this time'))
                break
            default:
                dispatch(setErrorMessage('An error occurred while trying to find the Geolocation'))
        }
    }

    window.navigator.geolocation.getCurrentPosition(success, error)
}
