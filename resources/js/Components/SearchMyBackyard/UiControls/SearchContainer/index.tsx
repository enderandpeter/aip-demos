import ClearIcon from "@mui/icons-material/Clear";
import React, {useEffect, useRef, useState} from "react";
import {useDispatch} from "react-redux";
import {search, toggleVisible} from "@/redux/geolocations/slice";

export interface SearchContainerProps{
    endSearch: () => void;
}

export default ({endSearch}: SearchContainerProps) => {
    const dispatch = useDispatch()

    const searchInput = useRef<HTMLInputElement>(null);
    const [searchQuery, setSearchQuery] = useState("")

    useEffect(() => {
        if(searchInput.current){
            searchInput.current.focus()
        }
    }, [searchInput.current])

    useEffect(() => {
        dispatch(search(searchQuery.toLowerCase()))
    }, [searchQuery])

    return (
        <div id="search_container">
            <input id="search"
                   type="text"
                   ref={searchInput}
                   onChange={(e) => {
                       e.preventDefault()

                       // Show all content right before each search
                       dispatch(toggleVisible(true))

                       setSearchQuery(e.target.value)
                   }}
            />
            <button id="search_close_button"
                    className="btn btn-light btn-xs"
                    title="Close"
                    onClick={(e) => {
                        e.preventDefault()

                        // Show all content when search box is closed
                        dispatch(toggleVisible(true))

                        endSearch()
                    }}
            >
                <ClearIcon/>
            </button>
        </div>
    )
}
