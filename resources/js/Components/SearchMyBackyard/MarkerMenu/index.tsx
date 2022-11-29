import React, {useState} from "react";

import DeleteIcon from '@mui/icons-material/Delete'
import SearchIcon from '@mui/icons-material/Search'
import {useDispatch} from "react-redux";
import {removeGeolocation} from "@/redux/geolocations/slice";
import SearchContainer from "@/Components/SearchMyBackyard/UiControls/SearchContainer";

export interface MarkerMenuProps {
    markers: google.maps.Marker[];
}

export default ({markers}: MarkerMenuProps) => {
    const dispatch = useDispatch();

    const [isSearching, setIsSearching ] = useState(false)

    const removeMarker = (marker: google.maps.Marker) => {
        marker.setMap(null);

        dispatch(removeGeolocation({
            lat: marker.getPosition()!.lat(),
            lng: marker.getPosition()!.lng()
        }))
    }


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
                        markers.map((marker, index) => (
                            <li className="marker_list_item" key={`marker-${index}`}>
                                <div className="row">
                                    <div id="label_container" className="col-12">
                                        <h3 className="marker_list_label_header">{marker.getLabel() as string}</h3>
                                    </div>
                                    <div className="col-6 btn-group" role="group" aria-label="Manage location">
                                        <button
                                            type="submit"
                                            className="btn btn-light btn-sm"
                                            title="Remove"
                                            onClick={(e)  => {
                                                e.preventDefault();
                                                removeMarker(marker)
                                            }}
                                        >
                                            <DeleteIcon />
                                        </button>
                                    </div>
                                </div>
                            </li>
                        ))
                    }
                </ul>
            </form>
        </div>
    )
}
