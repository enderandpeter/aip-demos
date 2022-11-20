import React from "react";

import './style.scss'

import ErrorOutlineIcon from '@mui/icons-material/ErrorOutline';

export default () => {

    return (
        <div id="uicontrols">
            <div className="uicontrol-header-div">
                <header>
                    <h1>Search My Backyard!</h1>
                    <button id="siteinfo-button" className="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#siteinfo-modal" title="More Info" aria-label="More Info">
                        <ErrorOutlineIcon />
                    </button>
                </header>
            </div>
        </div>
    )
}
