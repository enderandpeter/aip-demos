import {WikipediaPages} from "@/redux/services/wikipedia/imageNames";

export interface WikipediaImageData {
    articleTitle: string;
    imageArray: WikipediaImageArrayData[]
}

export interface WikipediaImageArrayData {
    thumbnail: string;
    original: string;
    title: string;
}

import {createApi, fetchBaseQuery, FetchBaseQueryError} from '@reduxjs/toolkit/query/react'
import LatLngLiteral = google.maps.LatLngLiteral;
import {WikipediaImagePages} from "@/redux/services/wikipedia/imageUrls";

const baseUrl = 'https://en.wikipedia.org/w/'
export const wikipediaImageDataAPI = createApi({
    reducerPath: 'wikipediaImageDataAPI',
    baseQuery: fetchBaseQuery({baseUrl}),
    endpoints: (builder) => ({
        getWikiImageData: builder.query<WikipediaImageData[], LatLngLiteral>({
            async queryFn(location, queryApi, options, fetchWithBQ) {

                const response = await fetchWithBQ('api.php?action=query&format=json&origin=*&'
                    + 'generator=geosearch&colimit=50&'
                    + 'prop=coordinates|images&imlimit=max&'
                    + 'ggsradius=10000&ggslimit=50&ggscoord='
                    + `${location.lat}|${location.lng}`)

                if (response.error) {
                    return {error: response.error as FetchBaseQueryError}
                }

                const articles = response as { data: { query: { pages: WikipediaPages } }}

                let error

                const data = Object.keys(articles.data.query.pages).map(async (pageIndex) => {
                    const articlePage = articles.data.query.pages[pageIndex]

                    // Get a max of 50 images per article
                    const imageList = articlePage.images.map((image) => {
                        return image.title
                    }).slice(0, 51).join('|')

                    const imageDataResponse = await fetchWithBQ('api.php?action=query&format=json&origin=*&'
                        + 'prop=pageimages&'
                        + 'piprop=thumbnail|name|original&pithumbsize=200&'
                        + 'titles='
                        + `${imageList}`)

                    if (imageDataResponse.error) {
                        error = imageDataResponse.error
                        return
                    }

                    const imagePages = imageDataResponse.data as { data: { query: { pages: WikipediaImagePages } } }

                    const imageArray = Object.keys(imagePages.data.query.pages).map((imagePageIndex) => {
                        const imagePage = imagePages.data.query.pages[imagePageIndex]

                        return {
                            original: imagePage.original.source,
                            thumbnail: imagePage.thumbnail.source,
                            title: imagePage.title,
                        }
                    })
                    return {
                        articleTitle: articlePage.title,
                        imageArray
                    }
                })

                return error ? {error: error as FetchBaseQueryError} : {data}
            },
        })
    })
})

export const {useGetWikiImageDataQuery} = wikipediaImageDataAPI
