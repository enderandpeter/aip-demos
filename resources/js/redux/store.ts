import { configureStore } from '@reduxjs/toolkit'
import errorReducer from "@/redux/error/slice";

const store = configureStore({
    reducer: {
        error: errorReducer
    }
})

export default store;
export type RootState = ReturnType<typeof store.getState>
