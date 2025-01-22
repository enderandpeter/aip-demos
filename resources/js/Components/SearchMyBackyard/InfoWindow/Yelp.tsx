import {useGetYelpReviewsQuery, YelpBusiness} from "@/redux/services/aip";
import {BeatLoader} from "react-spinners";
import React, {useEffect} from "react";
import {SMBMarker} from "@/Components/SearchMyBackyard/Map";


export interface YelpInfowindowProps {
    marker: SMBMarker;
    activeTab: string;
}

export default ({marker, activeTab}: YelpInfowindowProps) => {
    const locationString = `${marker.getPosition()?.lat()},${marker.getPosition()?.lng()}`
    const {data, error, isLoading} = useGetYelpReviewsQuery(locationString)

    useEffect(() => {
        if(!isLoading){ // Center the info window once the data has loaded
            marker.openInfowindow()
        }
    }, [isLoading])

    return (<div className={'infowindow_yelp'}>
        {
            isLoading ? <BeatLoader color={'blue'} loading={true} />
                : error ? <div>Sorry, there was an error</div>
                    : data ? (
                        <div id="yelp_container" className={`service_container ${activeTab === 'yelp' ? 'active' : ''}`}>
                                <h3>Yelp</h3>
                                <ul id="yelp_businesses" className="list-unstyled media-list">
                                    {
                                        data.map((business: YelpBusiness) => {
                                            const {
                                                url,
                                                name,
                                                image_url,
                                                location,
                                                display_phone,
                                                rating,
                                                reviews
                                            } = business

                                            return (
                                                <li className={"business_list_item d-flex mt-3"} key={business.id}>
                                                    <div className={"business_info"}>
                                                        {
                                                            image_url ? (
                                                                <div className={"business_img flex-shrink-0"}>
                                                                    <a href={url} target="_blank">
                                                                        <img className={"media-object me-3 w-100"}
                                                                             alt={name}
                                                                             src={image_url.replace(/http:/, '')}
                                                                        />
                                                                    </a>
                                                                </div>
                                                            ) : null
                                                        }
                                                    </div>
                                                    <div className={"flex-grow-1 ms-3"}>
                                                        <header className={"media-heading mb-3"}>
                                                            <h3>
                                                                <a href={url}
                                                                   target="_blank">{name}</a>
                                                            </h3>
                                                            <address>
                                                                {
                                                                    location.display_address.map((address) => {
                                                                        return (<div key={address}>{address}</div>)
                                                                    })
                                                                }
                                                            </address>
                                                            <a href={`tel:${display_phone}`}>{display_phone}</a>
                                                        </header>
                                                        <div className={"rating"}>
                                                            <a href={url} target="_blank">
                                                                <img className={"rating_img"}
                                                                     src={`/resources/search-my-backyard/yelp/ratings/business/${rating}.png`}
                                                                     alt={`Rating: ${rating}`}
                                                                     title={rating}
                                                                />
                                                            </a>
                                                        </div>
                                                        <div className={"reviews"}>
                                                            <ul data-bind="foreach: reviews"
                                                                className={"list-unstyled media-list"}>
                                                                {
                                                                    reviews.map((review) => {
                                                                        const {user} = review

                                                                        return (
                                                                            <li className="review_list_item d-flex mb-3" key={review.id}>
                                                                                <div
                                                                                    className="user w-25">
                                                                                    <div className={"flex-shrink-0"}>
                                                                                        <a
                                                                                           href={review.user.profile_url}
                                                                                           target="_blank">
                                                                                            <img
                                                                                                className={"media-object me-3"}
                                                                                                style={{width: '50px', height: '50px'}}
                                                                                                src={
                                                                                                user.image_url ? user.image_url.replace(/http:/, '')
                                                                                                    : null
                                                                                                }
                                                                                                title={user.name}
                                                                                                alt={user.name}
                                                                                            />
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                <div className="flex-grow-1 ms-3">
                                                                                    <div className="review_excerpt me-3 mb-2">{review.text}</div>
                                                                                    <div className="review_rating">
                                                                                        <a
                                                                                           href={review.url}
                                                                                           target="_blank">
                                                                                            <img
                                                                                                src={`/resources/search-my-backyard/yelp/ratings/user/${rating}.png`}
                                                                                                alt={`Rating: ${rating}`}
                                                                                                title={rating}
                                                                                            />
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        )
                                                                    })
                                                                }
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                            </div>
                        )
                    : null
        }
    </div>)
}
