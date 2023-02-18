import React, {useState} from "react";
import {useSelector} from "react-redux";
import SearchIcon from '@mui/icons-material/Search'
import SearchContainer from "@/Components/SearchMyBackyard/UiControls/SearchContainer";
import MarkerListItem from "@/Components/SearchMyBackyard/MarkerListItem";
import ClearAndSelectAllButton from "@/Components/SearchMyBackyard/UiControls/Button/ClearAndSelectAllButton";
import ShowAndHideSelectedButton from "@/Components/SearchMyBackyard/UiControls/Button/ShowAndHideSelectedButton";
import DeleteSelectedButton from "@/Components/SearchMyBackyard/UiControls/Button/DeleteSelectedButton";

import {geolocations} from "@/redux/geolocations/slice";

export default () => {
    const userLocations = useSelector(geolocations);

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
                    { userLocations.length > 0 && (
                        <div id="marker_menu_buttons_list">
                            <div className="btn-group" role="group" aria-label="Manage all locations">
                                <ClearAndSelectAllButton />
                                <DeleteSelectedButton />
                                <ShowAndHideSelectedButton />
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
                            <SearchContainer endSearch={endSearch} />
                        )
                    }
                </div>
                <ul id="marker_list">
                    {
                        userLocations.map((gLocation) => {
                            return <MarkerListItem gLocation={gLocation} key={gLocation.id} />
                        })
                    }
                </ul>
            </form>
        </div>
    )
}
