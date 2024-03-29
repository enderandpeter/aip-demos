import {createSlice, Draft, PayloadAction} from '@reduxjs/toolkit'
import {RootState} from "@/redux/store";

export interface ErrorState {
    message: string;
}

export const errorSlice = createSlice({
    name: 'error',
    initialState: {
        message: ''
    } as ErrorState,
    reducers: {
        setErrorMessage: (state: Draft<ErrorState>, action: PayloadAction<string>) => {
            state.message = action.payload
        }
    }
})

export const errorMessage = (state: RootState) => state.error.message

export const { setErrorMessage } = errorSlice.actions

export default errorSlice.reducer
