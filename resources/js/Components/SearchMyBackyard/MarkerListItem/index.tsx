import React, {useEffect, useRef} from 'react'
import DeleteIcon from "@mui/icons-material/Delete";
import DoneIcon from '@mui/icons-material/Done';
import ClearIcon from "@mui/icons-material/Clear";
import {Visibility as VisibilityIcon, VisibilityOff as VisibilityOffIcon} from "@mui/icons-material";
import {CanSetMarkers, SMBMarker} from "@/Components/SearchMyBackyard/Map";
import {useDispatch} from "react-redux";
import {removeGeolocation} from "@/redux/geolocations/slice";
import './style.scss'

export interface MarkerListItemProps extends CanSetMarkers {
    marker: SMBMarker;
}

export default ({marker, setMarkers}: MarkerListItemProps) => {
    const dispatch = useDispatch();
    const liRef = useRef<HTMLLIElement>(null)
    const originalLabelRef = useRef("")
    const searchInputRef = useRef<HTMLInputElement>(null)

    const removeMarker = (marker: SMBMarker) => {
        marker.setMap(null);

        dispatch(removeGeolocation({
            lat: marker.getPosition()!.lat(),
            lng: marker.getPosition()!.lng()
        }))
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

            if (!marker.showInList) {
                li.classList.add("d-none")
            } else {
                li.classList.remove("d-none")
            }
        }
    }, [marker.showInList])

    useEffect(() => {
        if(liRef.current){
            const li = liRef.current

            if(marker.selected){
                li.classList.add("selected")
            } else {
                li.classList.remove("selected")
            }
        }
    }, [marker.selected])

    useEffect(() => {
        if(liRef.current){
            const li = liRef.current

            if(marker.hovering){
                li.classList.add("hovering")
            } else {
                li.classList.remove("hovering")
            }
        }
    }, [marker.hovering])

    useEffect(() => {
        originalLabelRef.current = marker.getLabel()!.toString()
    }, [marker.editing])

    const saveInput = () => {
        marker.setLabel(searchInputRef.current!.value)
        marker.editing = false

        setMarkers((prevMarkers) => [ ...prevMarkers])
    }

    const discardInput = () => {
        marker.setLabel(originalLabelRef.current)
        marker.editing = false
        setMarkers((prevMarkers) => [ ...prevMarkers])
    }

    return (
        <li ref={liRef} onClick={(e) => {
            let clicked = true;
            setMarkers((prevMarkers) => {
                if(clicked){
                    marker.selected = !marker.selected
                    clicked = false
                }

                return [
                    ...prevMarkers
                ]
            })
        }}
            onMouseOver={(e) => {
                setMarkers((prevMarkers) => {
                    marker.hovering = true

                    return [
                        ...prevMarkers
                    ]
                })
            }}
            onMouseOut={(e) => {
                setMarkers((prevMarkers) => {
                    marker.hovering = false

                    return [
                        ...prevMarkers
                    ]
                })
            }}
        >
            <div className="row">
                <div id="label_container" className="col-12">
                    {
                        marker.editing ? (
                            <div id="label_edit_container">
                                <input className="marker_list_label_input form-control" autoFocus
                                       ref={searchInputRef}
                                       defaultValue={marker.getLabel()!.toString()}
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
                                        marker.editing = true
                                    }}
                                >
                                    {marker.getLabel() as string}</h3>
                            </div>
                        )
                    }
                </div>
            </div>
            <div className="row">
                <div className="col-5">
                    {
                        marker.description ? (
                            <div title={`Close to Lat: ${marker.getPosition()!.lat().toPrecision(5)}, Long: ${marker.getPosition()!.lng().toPrecision(5)}`}>
                                {marker.description}
                            </div>
                        ) : (
                            <div>
                                <div className="lat">Lat: <span>{marker.getPosition()!.lat().toPrecision(5)}</span>
                                </div>
                                <div className="lng">Lng: <span>{marker.getPosition()!.lng().toPrecision(5)}</span>
                                </div>
                            </div>
                        )
                    }
                </div>
                <div className="col-6 btn-group" role="group" aria-label="Manage location">
                    <button
                        className="btn btn-light btn-sm"
                        title="Remove"
                        onClick={(e) => {
                            e.preventDefault();
                            removeMarker(marker)
                        }}
                    >
                        <DeleteIcon/>
                    </button>
                    <button className="btn btn-light btn-sm"
                            onClick={(e) => {
                                e.preventDefault()

                                marker.setVisible(!marker.getVisible())

                                // Force re-render of updated marker state
                                setMarkers((prevMarkers) => [...prevMarkers])
                            }}
                            title={marker.getVisible() ? 'Hide' : 'Show'}
                    >
                        {
                            marker.getVisible() ?
                                <VisibilityOffIcon/>
                                :
                                <VisibilityIcon/>
                        }
                    </button>
                </div>
            </div>
        </li>
    )
}
