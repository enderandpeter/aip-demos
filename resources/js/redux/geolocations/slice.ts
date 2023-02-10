import {createSlice, Draft, PayloadAction} from "@reduxjs/toolkit";
import {RootState} from "@/redux/store";
import LatLngLiteral = google.maps.LatLngLiteral;
import {SMBMarkerProps} from "@/Components/SearchMyBackyard/Map";

export interface GeoLocationData extends GeoLocationPayload, GeoLocationControl{
    serviceData: {
        [serviceName: string]: {}
    }
}

export interface GeoLocationPayload extends SMBMarkerProps{
    id: string,
    visible?: boolean,
    description?: string;
    label: string;
    location: LatLngLiteral;
}

export interface GeoLocationEditPayload extends GeoLocationControl{
    id: string;
    hovering?: boolean;
    showInList?: boolean;
    selected?: boolean;
    editing?: boolean;
    visible?: boolean;
    label?: string;
    serviceData?: {
        [serviceName: string]: ServiceData
    }
}

export interface GeoLocationControl {
    callGoToLocation?: boolean;
    goToLocationCalled?: boolean;
    callUpdateInfowindow?: boolean;
    updateInfowindowCalled?: boolean;
    callOpenInfowindow?: boolean;
    openInfowindowCalled?: boolean;
}

export interface ServiceData {
    name: string;
    location: LatLngLiteral;
    data: {}
}

export type GeoLocationsState = GeoLocationData[]

export const geoLocationsSlice = createSlice({
    name: 'geolocations',
    initialState: [] as GeoLocationsState,
    reducers: {
        addGeoLocation(state: Draft<GeoLocationsState>, action: PayloadAction<GeoLocationPayload>){
            const {
                location,
                id,
                showInList,
                selected,
                editing,
                label,
                hovering
            } = action.payload
            state.push({
                location,
                id,
                showInList,
                selected,
                editing,
                hovering,
                label,
                visible : true,
                serviceData: {}
            });
        },
        removeGeolocation(state: Draft<GeoLocationsState>, action: PayloadAction<string>){
            return state.filter((glocation) => glocation.id !== action.payload)
        },
        removeSelectedGeolocations(state: Draft<GeoLocationsState>){
            return state.filter((glocation) => !glocation.selected)
        },
        editGeoLocation(state: Draft<GeoLocationsState>, action: PayloadAction<GeoLocationEditPayload>){
            let gLocation = state.find((g) => g.id === action.payload.id)

            if(gLocation !== undefined){
                gLocation.hovering = action.payload.hovering ?? gLocation.hovering
                gLocation.editing = action.payload.editing ?? gLocation.editing
                gLocation.showInList = action.payload.showInList ?? gLocation.showInList
                gLocation.visible = action.payload.visible ?? gLocation.visible
                gLocation.label = action.payload.label ?? gLocation.label
                gLocation.selected = action.payload.selected ?? gLocation.selected

                if(action.payload.serviceData){
                    Object.keys(action.payload.serviceData).forEach((serviceDataName) => {
                        gLocation!.serviceData[serviceDataName] = action.payload.serviceData!
                    })
                }
            }
        },
        controlGeoLocation(state: Draft<GeoLocationsState>, action: PayloadAction<GeoLocationEditPayload>){
            let selectedGeolocation = state.find((gLocation) => gLocation.id === action.payload.id)

            if(selectedGeolocation !== undefined){
                if(typeof action.payload.callGoToLocation === 'boolean')
                    selectedGeolocation.callGoToLocation = action.payload.callGoToLocation

                if(typeof action.payload.goToLocationCalled === 'boolean')
                    selectedGeolocation.goToLocationCalled = action.payload.goToLocationCalled

                if(typeof action.payload.callUpdateInfowindow === 'boolean')
                    selectedGeolocation.callUpdateInfowindow = action.payload.callUpdateInfowindow

                if(typeof action.payload.updateInfowindowCalled === 'boolean')
                    selectedGeolocation.updateInfowindowCalled = action.payload.updateInfowindowCalled

                if(typeof action.payload.callOpenInfowindow === 'boolean')
                    selectedGeolocation.callOpenInfowindow = action.payload.callOpenInfowindow

                if(typeof action.payload.openInfowindowCalled === 'boolean')
                    selectedGeolocation.openInfowindowCalled = action.payload.openInfowindowCalled
            }
        },
        toggleSelectAll(state: Draft<GeoLocationsState>){
            const atLeastOneSelected = state.some((gLocation) => gLocation.selected)
            if(atLeastOneSelected){
                state.forEach((gLocation) => gLocation.selected = false)
            } else {
                state.forEach((gLocation) => gLocation.selected = true)
            }
        },
        toggleVisible(state: Draft<GeoLocationsState>, action: PayloadAction<boolean | null>){
            let visibility: boolean;
            if(action.payload === null){
                state.filter((g) => g.selected).forEach((gLocation) => {
                    gLocation.visible = !gLocation.visible
                    gLocation.showInList = !gLocation.showInList
                })
            } else {
                visibility = action.payload

                state.forEach((gLocation) => {
                    gLocation.visible = visibility
                    gLocation.showInList = visibility
                })
            }
        },
        search(state: Draft<GeoLocationsState>, action: PayloadAction<string>){
            state.forEach((gLocation) => {
                gLocation.visible = true

                if(!gLocation.label.toLowerCase().includes(action.payload)){
                    gLocation.visible = false
                    gLocation.showInList = false
                }
            })
        }
    }
});

export const {
    addGeoLocation,
    removeGeolocation,
    editGeoLocation,
    controlGeoLocation,
    toggleSelectAll,
    toggleVisible,
    removeSelectedGeolocations,
    search
} = geoLocationsSlice.actions;

export const geolocations = (state: RootState) => state.geolocations
export const atLeastOneVisible = (state: RootState) => state.geolocations.filter((gLocation) => gLocation.selected)
    .some((gLocation) => gLocation.visible)

export default geoLocationsSlice.reducer;
