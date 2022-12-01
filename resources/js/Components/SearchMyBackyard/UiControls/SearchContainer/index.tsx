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

                if(!marker.getLabel()!.toString().toLowerCase().includes(searchQuery.toLowerCase())){
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

                       // Show all content right before each search
                       setMarkers((prevMarkers) => {
                           prevMarkers.forEach((marker) => {
                               marker.setVisible(true)
                               marker.showInList = true
                           })

                           return [
                               ...prevMarkers
                           ]
                       })

                       setSearchQuery(e.target.value)
                   }}
            />
            <button id="search_close_button"
                    className="btn btn-light btn-xs"
                    title="Close"
                    onClick={(e) => {
                        e.preventDefault()

                        // Show all content when search box is closed
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
