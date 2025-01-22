import {SMBMarker} from "@/Components/SearchMyBackyard/Map";
import {
    useGetWikiImageDataQuery,
    WikipediaImageArrayData,
    WikipediaImageData
} from "@/redux/services/wikipedia/imageData";
import {useEffect, useState} from "react";
import {BeatLoader} from "react-spinners";

export interface WikipediaProps{
    marker: SMBMarker,
    activeTab: string;
}


export default ({marker, activeTab}: WikipediaProps) => {
    const {data, error, isLoading} = useGetWikiImageDataQuery({lat: marker.getPosition()!.lat(), lng: marker.getPosition()!.lng()})

    const excludedImages = [
        'File:Commons-logo.svg',
        'File:OOjs UI icon edit-ltr-progressive.svg',
        'File:Question book-new.svg',
        'File:Ambox important.svg',
        'File:Ambox current red.svg',
        'File:Crystal Clear app kedit.svg',
        'File:Red pog.svg',
        'File:Edit-clear.svg'
    ]

    useEffect(() => {
        if(!isLoading){ // Center the info window once the data has loaded
            marker.openInfowindow()
        }
    }, [isLoading])

    return (
        <div id="wikipedia_container" className={`service_container ${activeTab === 'wikipedia' ? 'active' : ''}`}
             data-bind="if: activeMarker().locationDataViewModel().getService('wikipedia').showView, css: { active: activeMarker().locationDataViewModel().getService('wikipedia').showView() }">
            <div id="wikipedia_content"
                 data-bind="if: activeMarker().locationDataViewModel().getService('wikipedia').data().length !== 0">
                <h3 className="mt-3">Wikipedia</h3>
                <ul className="wikipedia_article_list list-unstyled"
                    data-bind="foreach: activeMarker().locationDataViewModel().getService('wikipedia').data">
                    {
                        isLoading ? <BeatLoader color={'black'} /> : data && data.length !== 0 ? data.map((image) => {
                            const {articleTitle, imageArray, pageIndex} = image

                            return (<li className="wikipedia_article_list_item" key={pageIndex}>
                                <div className="wikipedia_article_container">
                                    <h4>
                                        <a target="_blank" href={`https://en.wikipedia.org/wiki/${articleTitle.replace(/ /g, '_')}`}>{articleTitle}</a>
                                    </h4>
                                </div>
                                <ul className="wikipedia_image_list list-unstyled list-inline">
                                    {
                                        imageArray.filter((imageData: WikipediaImageArrayData) => !excludedImages.includes(imageData.title))
                                            .map((imageData: WikipediaImageArrayData) => {
                                            const {original, thumbnail, title} = imageData
                                            return (
                                                <li className="wikipedia_image_list_item list-inline-item" key={title}>
                                                    <a href={original} target="_blank" >
                                                        <img className="img-responsive" src={thumbnail} />
                                                    </a>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                            </li>)
                        }) : error ? (<li>{"Error loading content"}</li>) : (<li></li>)
                    }
                </ul>
            </div>
        </div>
    )
}
