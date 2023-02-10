import React, {useEffect, useRef} from 'react'
import DeleteIcon from "@mui/icons-material/Delete";
import DoneIcon from '@mui/icons-material/Done';
import ClearIcon from "@mui/icons-material/Clear";
import PlaceIcon from '@mui/icons-material/Place';
import {Visibility as VisibilityIcon, VisibilityOff as VisibilityOffIcon} from "@mui/icons-material";
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";
import {useDispatch, useSelector} from "react-redux";
import {removeGeolocation, editGeoLocation, geolocations, GeoLocationData} from "@/redux/geolocations/slice";
import './style.scss'

export interface MarkerListItemProps{
    gLocation: GeoLocationData;
}

export default ({gLocation}: MarkerListItemProps) => {
    const dispatch = useDispatch();
    const liRef = useRef<HTMLLIElement>(null)
    const originalLabelRef = useRef("")
    const searchInputRef = useRef<HTMLInputElement>(null)

    const userLocations = useSelector(geolocations)

    const removeMarker = () => {
        dispatch(removeGeolocation(gLocation.id))
    }

    useEffect(() => {
        if (liRef.current) {
            const li = liRef.current

            li.classList.add("marker_list_item")
        }
    }, [liRef.current])

    useEffect(() => {
        if (liRef.current) {
            const li = liRef.current

            if (!gLocation.showInList) {
                li.classList.add("d-none")
            } else {
                li.classList.remove("d-none")
            }
        }
    }, [gLocation.showInList])

    useEffect(() => {
        if(liRef.current){
            const li = liRef.current

            if(gLocation.selected){
                li.classList.add("selected")
            } else {
                li.classList.remove("selected")
            }
        }
    }, [gLocation.selected])

    useEffect(() => {
        if(liRef.current){
            const li = liRef.current

            if(gLocation.hovering){
                li.classList.add("hovering")
            } else {
                li.classList.remove("hovering")
            }
        }
    }, [gLocation.hovering])

    useEffect(() => {
        originalLabelRef.current = gLocation.label
    }, [gLocation.editing])

    const saveInput = () => {
        dispatch(editGeoLocation({
            id: gLocation.id,
            label: searchInputRef.current!.value,
            editing: false
        }))
    }

    const discardInput = () => {
        dispatch(editGeoLocation({
            id: gLocation.id,
            label: originalLabelRef.current,
            editing: false
        }))
    }

    return (
        <li ref={liRef} onClick={(e) => {
            dispatch(editGeoLocation({
                id: gLocation.id,
                selected: !gLocation.selected
            }))
            // let clicked = true;
        }}
            onMouseOver={(e) => {
                let obj = e.relatedTarget as Node | null
                while(obj != null){
                    if(obj === liRef.current){
                        return
                    }
                    obj = obj.parentNode
                }

                dispatch(editGeoLocation({
                    id: gLocation.id,
                    hovering: true
                }))
            }}
            onMouseOut={(e) => {
                let obj = e.relatedTarget as Node | null
                while(obj != null){
                    if(obj === liRef.current){
                        return
                    }
                    obj = obj.parentNode
                }

                dispatch(editGeoLocation({
                    id: gLocation.id,
                    hovering: false
                }))
            }}
        >
            <div className="row">
                <div id="label_container" className="col-12">
                    {
                        gLocation.editing ? (
                            <div id="label_edit_container">
                                <input className="marker_list_label_input form-control" autoFocus
                                       ref={searchInputRef}
                                       defaultValue={gLocation.label}
                                       onBlur={(e) => {
                                           saveInput()
                                       }}
                                       onKeyDown={(e) => {
                                           if(e.key === "Enter"){
                                               saveInput()
                                           }

                                           if(e.key === "Escape"){
                                               discardInput()
                                           }
                                       }}
                                />
                                <button className="marker_list_label_save btn btn-light"
                                        onClick={(e) => {
                                            e.preventDefault()
                                            saveInput()
                                        }}
                                >
                                    <DoneIcon />
                                </button>
                                <button className="marker_list_label_cancel btn btn-light"
                                        onClick={(e) => {
                                            e.preventDefault()
                                            discardInput()
                                        }}
                                >
                                    <ClearIcon />
                                </button>
                            </div>
                        ) : (
                            <div className="marker_list_label_header_container">
                                <h3 className="marker_list_label_header"
                                    onClick={(e) => {
                                        e.stopPropagation()
                                        dispatch(editGeoLocation({
                                            id: gLocation.id,
                                            editing: true
                                        }))
                                    }}
                                >
                                    {gLocation.label}</h3>
                            </div>
                        )
                    }
                </div>
            </div>
            <div className="row">
                <div className="col-5">
                    {
                        gLocation.description ? (
                            <div title={`Close to Lat: ${gLocation.location.lat.toPrecision(5)}, Long: ${gLocation.location.lng.toPrecision(5)}`}>
                                {gLocation.description}
                            </div>
                        ) : (
                            <div>
                                <div className="lat">Lat: <span>{gLocation.location.lat.toPrecision(5)}</span>
                                </div>
                                <div className="lng">Lng: <span>{gLocation.location.lng.toPrecision(5)}</span>
                                </div>
                            </div>
                        )
                    }
                </div>
                <div className="col-6 btn-group" role="group" aria-label="Manage location">
                    <button title="Go to location"
                            className="btn btn-light btn-sm"
                            onClick={(e) => {
                                e.preventDefault()
                                e.stopPropagation()

                                dispatch(editGeoLocation({
                                    id: gLocation.id,
                                    callGoToLocation: true,
                                }))
                            }}>
                        <PlaceIcon />
                    </button>
                    <button className="btn btn-light btn-sm"
                            onClick={(e) => {
                                e.preventDefault()
                                e.stopPropagation()

                                dispatch(editGeoLocation({
                                    id: gLocation.id,
                                    visible: !gLocation.visible
                                }))
                            }}
                            title={gLocation.visible ? 'Hide' : 'Show'}
                    >
                        {
                            gLocation.visible ?
                                <VisibilityOffIcon/>
                                :
                                <VisibilityIcon/>
                        }
                    </button>
                    <button
                        className="btn btn-light btn-sm"
                        title="Remove"
                        onClick={(e) => {
                            e.preventDefault();
                            removeMarker()
                        }}
                    >
                        <DeleteIcon/>
                    </button>
                </div>
            </div>
        </li>
    )
}
