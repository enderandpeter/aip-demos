import {createSlice, Draft, PayloadAction} from "@reduxjs/toolkit";
import {RootState} from "@/redux/store";
import LatLngLiteral = google.maps.LatLngLiteral;

export type GeoLocationsState = LatLngLiteral[];

export const geoLocationsSlice = createSlice({
    name: 'geolocations',
    initialState: [] as GeoLocationsState,
    reducers: {
        addGeoLocation(state: Draft<GeoLocationsState>, action: PayloadAction<LatLngLiteral>){
            state.push(action.payload);
        },
        removeGeolocation(state: Draft<GeoLocationsState>, action: PayloadAction<LatLngLiteral>){
            return state.filter((glocation) => glocation.lat !== action.payload.lat && glocation.lng !== action.payload.lng)
        }
    }
});

export const { addGeoLocation, removeGeolocation } = geoLocationsSlice.actions;

export const geolocations = (state: RootState) => state.geolocations

export default geoLocationsSlice.reducer;
