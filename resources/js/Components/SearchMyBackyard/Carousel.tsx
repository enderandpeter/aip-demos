import {useState} from "react";

export interface ImageSettings {
    src: string;
    alt: string;
    name: string;
}

export interface CarouselProps {
    images : ImageSettings[]
    type: string;
}
export default ({images, type}: CarouselProps) => {
    let carouselId = `carousel_${type}`

    return (
        <div id={carouselId} className="carousel slide">
            <div className="carousel-indicators">
                {
                    images.map((imageData, index) => {
                        return (
                            <button type="button"
                                    data-bs-target={`#${carouselId}`} data-bs-slide-to={index}
                                    aria-label={imageData.name} key={imageData.name}
                                    className={`${index === 0 ? 'active' : 0}`}
                            ></button>
                        )
                    })
                }
            </div>
            <div className="carousel-inner">
                {
                    images.map((imageData, index) => {
                        return (
                            <div className={`carousel-item ${index === 0 ? 'active' : ''}`} key={imageData.name}>
                                <img src={imageData.src} className="d-block w-100" alt={imageData.alt} />
                            </div>
                        )
                    })
                }
            </div>
            <button className="carousel-control-prev" type="button" data-bs-target={`#${carouselId}`}
                    data-bs-slide="prev">
                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                <span className="visually-hidden">Previous</span>
            </button>
            <button className="carousel-control-next" type="button" data-bs-target={`#${carouselId}`}
                    data-bs-slide="next">
                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                <span className="visually-hidden">Next</span>
            </button>
        </div>
    )
}
