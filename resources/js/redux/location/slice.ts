import {createSlice, Dispatch, Draft, PayloadAction} from '@reduxjs/toolkit'
import {RootState} from "@/redux/store";
import LatLngLiteral = google.maps.LatLngLiteral;
import { setGeolocation } from '@/utilities/GeolocationUtilities'

export interface LocationState {
    center: LatLngLiteral;
}

export const defaultCenter: LatLngLiteral = { lat: 44.540, lng: -78.546 }

export const locationSlice = createSlice({
    name: 'location',
    initialState: {
        center: defaultCenter
    } as LocationState,
    reducers: {
        setLocationCenter: (state: Draft<LocationState>, action: PayloadAction<LatLngLiteral>) => {
            state.center = action.payload
        }
    }
})

export const findGeolocation = () => (dispatch: Dispatch) => {
    setGeolocation(dispatch)
}

export const locationCenter = (state: RootState) => state.location.center

export const { setLocationCenter } = locationSlice.actions

export default locationSlice.reducer
