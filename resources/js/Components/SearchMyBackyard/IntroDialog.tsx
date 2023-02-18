import React from "react";

export default (): JSX.Element => {
    return (
        <div id="siteinfo-modal" className="modal fade" tabIndex={-1}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Welcome!</h5>
                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div className="modal-body">
                        <p>Welcome to <em>Search My Backyard!</em>, a JavaScript-based web app for searching locations around the world and finding out more about them.
                            Please allow the web app to discover your location. Then click anywhere on the map to create a marker. Your location will be saved in the <strong>Saved Locations</strong> list.</p>
                        <p>Click the marker or marker icon in the list item to center on the location and show information from Google Maps, Yelp, and Wikipedia about the location. Click the Street icon
                            to go to a Google Street view panorama, if available. You can show/hide or remove individual markers in the list as well as click the list entries to select them and
                            use the buttons at the top of the <strong>Saved Locations</strong> section for bulk actions. Click <em>Clear/select markers</em> to select all markers or clear the selection.</p>
                        <p><strong>Note:</strong> Please close this infobox to enable the fullscreen button.</p>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
                    </div>
                </div>
            </div>
        </div>
    )
}
