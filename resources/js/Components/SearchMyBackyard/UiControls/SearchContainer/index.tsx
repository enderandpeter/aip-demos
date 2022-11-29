import ClearIcon from "@mui/icons-material/Clear";
import React, {useEffect, useRef} from "react";

export interface SearchContainerProps {
    endSearch: () => void;
}

export default ({endSearch}: SearchContainerProps) => {

    const searchInput = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if(searchInput.current){
            searchInput.current.focus()
        }
    }, [searchInput.current])

    return (
        <div id="search_container">
            <input id="search" type="text" ref={searchInput} />
            <button id="search_close_button"
                    className="btn btn-light btn-xs"
                    title="Close"
                    onClick={(e) => {
                        e.preventDefault()
                        endSearch()
                    }}
            >
                <ClearIcon/>
            </button>
        </div>
    )
}
