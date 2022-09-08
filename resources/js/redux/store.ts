import { configureStore } from '@reduxjs/toolkit'
import errorReducer from "@/redux/error/slice"
import locationReducer from '@/redux/location/slice'

const store = configureStore({
    reducer: {
        error: errorReducer,
        location: locationReducer,
    }
})

export default store;
export type RootState = ReturnType<typeof store.getState>
