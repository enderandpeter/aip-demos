import React, {useState} from "react";

import SearchIcon from '@mui/icons-material/Search'
import SearchContainer from "@/Components/SearchMyBackyard/UiControls/SearchContainer";
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";
import MarkerListItem from "@/Components/SearchMyBackyard/MarkerListItem";
import ClearAndSelectAllButton from "@/Components/SearchMyBackyard/UiControls/Button/ClearAndSelectAllButton";
import ShowAndHideSelectedButton from "@/Components/SearchMyBackyard/UiControls/Button/ShowAndHideSelectedButton";
import DeleteSelectedButton from "@/Components/SearchMyBackyard/UiControls/Button/DeleteSelectedButton";

import './style.scss'

export interface MarkerMenuProps extends CanSetMarkers {
    markers: SMBMarker[];
}

export default ({markers, setMarkers}: MarkerMenuProps) => {
    const [isSearching, setIsSearching ] = useState(false)

    const startSearch = () => {
        setIsSearching(true)
    }
    const endSearch = () => {
        setIsSearching(false)
    }

    return (
        <div id="marker_menu">
            <form id="marker_menu_form">
                <h2>Saved Locations</h2>
                <div id="marker_menu_buttons">
                    { markers.length > 0 && (
                        <div id="marker_menu_buttons_list">
                            <div className="btn-group" role="group" aria-label="Manage all locations">
                                <ClearAndSelectAllButton markers={markers} setMarkers={setMarkers} />
                                <DeleteSelectedButton markers={markers} setMarkers={setMarkers} />
                                <ShowAndHideSelectedButton markers={markers} setMarkers={setMarkers} />
                                <button type="submit"
                                        title="Search locations"
                                        className="btn btn-light"
                                        onClick={(e) => {
                                            e.preventDefault()
                                            startSearch()
                                        }}
                                >
                                    <SearchIcon />
                                </button>
                            </div>
                        </div>
                    )}
                    {
                        isSearching && (
                            <SearchContainer endSearch={endSearch} setMarkers={setMarkers} />
                        )
                    }
                </div>
                <ul id="marker_list">
                    {
                        markers.map((marker, index) => {
                            return <MarkerListItem marker={marker} setMarkers={setMarkers} key={`marker-${index}`} />
                        })
                    }
                </ul>
            </form>
        </div>
    )
}
