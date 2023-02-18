import {useEffect, useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {editGeoLocation, geolocations} from "@/redux/geolocations/slice";
import {SMBMarker} from "@/Components/SearchMyBackyard/Map";
import Carousel, {ImageSettings} from "@/Components/SearchMyBackyard/Carousel";

export interface GSVInfowindowProps {
    marker: SMBMarker;
}

const imageDirections = ['0', '90', '180', '270'];

const googleApiKey = import.meta.env.VITE_GOOGLE_API_KEY

export default ({marker}: GSVInfowindowProps) => {
    const {pano} = marker

    const [images, setImages] = useState<ImageSettings[]>([])

    useEffect(() => {
        if(pano){
            setImages(imageDirections.map((heading) => {
                return {
                    src: `https://maps.googleapis.com/maps/api/streetview?key=${googleApiKey}&pano=${pano}&heading=${heading}&size=624x312`,
                    name: `Heading ${heading}`,
                    alt: `Heading ${heading}`,
                }
            }))

            marker.openInfowindow()
        }
    }, [marker, setImages])

    return (
        <div id="streetview_container" className="service_container">
            <h3 className="mt-3">Google Street View</h3>
            <div id="streetview_content">
                {
                    images.length === 0
                        ? (<h4>No Street View images found.</h4>)
                        : (<Carousel images={images} type={'gsv'} />)
                }
            </div>
        </div>
    )
}
