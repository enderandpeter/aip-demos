import {SMBMarker} from "@/Components/SearchMyBackyard/Map";
import {useGetWikiImageDataQuery, WikipediaImageData} from "@/redux/services/wikipedia/imageData";
import {useEffect, useState} from "react";
import {BeatLoader} from "react-spinners";

export interface WikipediaProps{
    marker: SMBMarker
}


export default ({marker}: WikipediaProps) => {
    const {data, error, isLoading} = useGetWikiImageDataQuery({lat: marker.getPosition()!.lat(), lng: marker.getPosition()!.lng()})
    const [wpImages, setWpImages] = useState<WikipediaImageData[]>([])

    useEffect(() => {
        if(data){
            Promise.allSettled(data).then((results) => {
                setWpImages((imageData) => {
                    let newImageData: WikipediaImageData = {
                        articleTitle: '',
                        imageArray: []
                    }
                    return results.map((result) => {
                        if(result.status === 'fulfilled'){
                            newImageData.articleTitle = result.value?.articleTitle ?? ''
                            newImageData.imageArray = result.value?.imageArray ?? []
                        }

                        return newImageData
                    }).filter((imageData: WikipediaImageData) => {
                        return imageData.articleTitle && imageData.imageArray.length !== 0
                    })
                })
            })
        }
    }, [data, setWpImages])

    return (
        <div id="wikipedia_container" className="service_container"
             data-bind="if: activeMarker().locationDataViewModel().getService('wikipedia').showView, css: { active: activeMarker().locationDataViewModel().getService('wikipedia').showView() }">
            <div id="wikipedia_content"
                 data-bind="if: activeMarker().locationDataViewModel().getService('wikipedia').data().length !== 0">
                <h3 className="mt-3">Wikipedia</h3>
                <ul className="wikipedia_article_list list-unstyled"
                    data-bind="foreach: activeMarker().locationDataViewModel().getService('wikipedia').data">
                    {
                        isLoading ? <BeatLoader color={'black'} /> : wpImages.length !== 0 ? wpImages.map((image) => {
                            const {articleTitle, imageArray} = image

                            return (<li className="wikipedia_article_list_item">
                                <div className="wikipedia_article_container">
                                    <h4>
                                        <a target="_blank" href={`https://en.wikipedia.org/wiki/${articleTitle.replace(/ /g, '_')}`}>{articleTitle}</a>
                                    </h4>
                                </div>
                                <ul className="wikipedia_image_list list-unstyled list-inline">
                                    {
                                        imageArray.map((imageData) => {
                                            const {original, thumbnail} = imageData
                                            return (
                                                <li className="wikipedia_image_list_item list-inline-item">
                                                    <a href={original}>
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
