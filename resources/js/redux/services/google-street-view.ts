import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react'

const baseUrl = 'https://maps.googleapis.com/maps/api/'
export const googleStreetViewAPI = createApi({
    reducerPath: 'googleStreetViewAPI',
    baseQuery: fetchBaseQuery({baseUrl}),
    endpoints: (builder) => ({
        getYelpReviews: builder.query<YelpBusiness[], string>({
            query: (location) => `search-my-backyard?location=${location}`,
            transformResponse: (response: { data: {yelp: YelpBusiness[]}}) => response.data.yelp,
        })
    })
})
