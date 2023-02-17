import { configureStore } from '@reduxjs/toolkit'
import errorReducer from "@/redux/error/slice"
import locationReducer from '@/redux/location/slice'
import geoLocationsReducer from '@/redux/geolocations/slice'
import { aipAPI } from "@/redux/services/aip";
import { setupListeners } from "@reduxjs/toolkit/query/react";
import {wikipediaImageDataAPI} from "@/redux/services/wikipedia/imageData";

const store = configureStore({
    reducer: {
        error: errorReducer,
        location: locationReducer,
        geolocations: geoLocationsReducer,
        [aipAPI.reducerPath]: aipAPI.reducer,
        [wikipediaImageDataAPI.reducerPath]: wikipediaImageDataAPI.reducer,
    },
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware().concat(aipAPI.middleware)
})

setupListeners(store.dispatch)

export default store;
export type RootState = typeof store
