import { host } from '@/config'

export interface YelpReview {
    id: string;
    url: string;
    text: string;
    rating: number;
    time_created: string;
    user: {
        id: string;
        profile_url: string;
        image_url: string;
        name: string;
    }
}

export interface YelpBusiness {
    id: string;
    alias: string;
    name: string;
    image_url: string;
    is_closed: boolean;
    url: string;
    review_count: string,
    categories: {alias: string, title: string}[],
    rating: string;
    coordinates: {latitude: string, longitude: string},
    transactions: string[],
    price?: string;
    location: {
        address1?: string;
        address2?: string;
        address3?: string;
        city?: string;
        zip_code?: string;
        country?: string;
        state?: string;
        cross_streets: string;
        display_address: string[]
    };
    phone: string;
    display_phone: string;
    distance?: string;
    reviews: YelpReview[]
}

import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react'
import {EndpointBuilder} from "@reduxjs/toolkit/dist/query/endpointDefinitions";

export const aipAPI = createApi({
    reducerPath: 'aipAPI',
    baseQuery: fetchBaseQuery({baseUrl: `api`}),
    endpoints: (builder) => ({
        getYelpReviews: builder.query<YelpBusiness[], string>({
            query: (location) => `search-my-backyard?location=${location}`,
            transformResponse: (response: { data: {yelp: YelpBusiness[]}}) => response.data.yelp,
        })
    })
})

export const { useGetYelpReviewsQuery } = aipAPI
