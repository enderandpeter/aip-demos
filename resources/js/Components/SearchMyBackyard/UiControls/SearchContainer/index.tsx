import ClearIcon from "@mui/icons-material/Clear";
import React, {useEffect, useRef, useState} from "react";
import {CanSetMarkers} from "@/Components/SearchMyBackyard/Map";

export interface SearchContainerProps extends CanSetMarkers{
    endSearch: () => void;
}

export default ({endSearch, setMarkers}: SearchContainerProps) => {

    const searchInput = useRef<HTMLInputElement>(null);
    const [searchQuery, setSearchQuery] = useState("")

    useEffect(() => {
        if(searchInput.current){
            searchInput.current.focus()
        }
    }, [searchInput.current])

    useEffect(() => {
        setMarkers((prevMarkers) => {
            prevMarkers.forEach((marker) => {
                marker.setVisible(true)
                // @ts-ignore
                if(!marker.getLabel().toString().includes(searchQuery)){
                    marker.setVisible(false)
                    marker.showInList = false
                }
            })

            return [
                ...prevMarkers
            ]
        })
    }, [searchQuery])

    return (
        <div id="search_container">
            <input id="search"
                   type="text"
                   ref={searchInput}
                   onChange={(e) => {
                       e.preventDefault()

                       setSearchQuery(e.target.value)
                   }}
            />
            <button id="search_close_button"
                    className="btn btn-light btn-xs"
                    title="Close"
                    onClick={(e) => {
                        e.preventDefault()

                        setMarkers((prevMarkers) => {
                            prevMarkers.forEach((marker) => {
                                marker.setVisible(true)
                                marker.showInList = true
                            })

                            return [
                                ...prevMarkers
                            ]
                        })

                        endSearch()
                    }}
            >
                <ClearIcon/>
            </button>
        </div>
    )
}
