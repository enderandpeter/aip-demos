import React from "react";

import './style.scss'

export default () => {

    return (
        <div id="uicontrols">
            <div className="uicontrol-header-div">
                <header>
                    <h1>Search My Backyard!</h1>
                    <button id="siteinfo-button" className="btn btn-info btn-sm" data-toggle="modal"
                            data-target="#siteinfo-modal" title="More Info" aria-label="More Info">
                        <i className="material-icons">error_outline</i>
                    </button>
                </header>
            </div>
        </div>
    )
}
