import React, {useEffect, useRef} from "react";
import {Modal} from "bootstrap";
import {useDispatch} from "react-redux";
import {setErrorMessage} from "@/redux/error/slice";

interface ErrorProps {
    message: string;
}

export default ({ message }: ErrorProps): JSX.Element => {
    const dispatch = useDispatch()
    const ref = useRef<HTMLDivElement>(null)

    let isShowing = false;

    const showModal = () => {
        if (isShowing) {
            return
        }

        const modal = new Modal(ref.current!)
        modal.show()
        isShowing = true

        ref.current!.addEventListener('hide.bs.modal', event => {
            isShowing = false
            dispatch(setErrorMessage(''))
        })
    }

    useEffect(() => {
        if(message){
            showModal()
        }
    }, [message])

    return (
        <div ref={ref} className="modal fade" tabIndex={-1}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Error</h5>
                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div className="modal-body">
                        <p>{message}</p>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    )
}
