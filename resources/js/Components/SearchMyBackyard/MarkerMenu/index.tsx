import React from "react";

import DeleteIcon from '@mui/icons-material/Delete'
import {useDispatch} from "react-redux";
import {removeGeolocation} from "@/redux/geolocations/slice";

export interface MarkerMenuProps {
    markers: google.maps.Marker[];
}

export default ({markers}: MarkerMenuProps) => {
    const dispatch = useDispatch();

    const removeMarker = (marker: google.maps.Marker) => {
        marker.setMap(null);

        dispatch(removeGeolocation({
            lat: marker.getPosition()!.lat(),
            lng: marker.getPosition()!.lng()
        }))
    }

    return (
        <div id="marker_menu">
            <form id="marker_menu_form">
                <h2>Saved Locations</h2>
                <div id="marker_menu_buttons"></div>
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
