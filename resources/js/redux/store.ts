import { configureStore } from '@reduxjs/toolkit'
import errorReducer from "@/redux/error/slice"
import locationReducer from '@/redux/location/slice'
import geoLocationsReducer from '@/redux/geolocations/slice'
import { aipAPI } from "@/redux/services/aip";
import { setupListeners } from "@reduxjs/toolkit/query/react";

const store = configureStore({
    reducer: {
        error: errorReducer,
        location: locationReducer,
        geolocations: geoLocationsReducer,
        [aipAPI.reducerPath]: aipAPI.reducer,
    },
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware().concat(aipAPI.middleware)
})

setupListeners(store.dispatch)

export default store;
export type RootState = ReturnType<typeof store.getState>
