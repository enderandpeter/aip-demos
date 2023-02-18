import React from "react";

import './style.scss'

import ErrorOutlineIcon from '@mui/icons-material/ErrorOutline';
import MarkerMenu from "@/Components/SearchMyBackyard/MarkerMenu";

export default () => {
    return (
        <div id="uicontrols" className={"d-flex flex-column align-items-end"}>
            <div className="uicontrol-header-div">
                <header>
                    <h1>Search My Backyard!</h1>
                    <button id="siteinfo-button" className="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#siteinfo-modal" title="More Info" aria-label="More Info">
                        <ErrorOutlineIcon />
                    </button>
                </header>
            </div>
            <MarkerMenu />
        </div>
    )
}
