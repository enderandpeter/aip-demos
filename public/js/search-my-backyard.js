$(function () {
    /**
     * Data pertaining to an image modal
     *
     * @param ImageData image A URL to an image
     */
    function ImageModal(image) {
        this.image = ko.observable(image);
        this.nextImage = ko.observable({});
        this.previousImage = ko.observable({});
        this.downloading = ko.observable(false);
        /** @prop The service that is currently active. */
        this.activeService = ko.observable('');
        this.showNextImage = function () {
            this.image().localPage.showImage(this.nextImage(), true);
        }
        this.showPreviousImage = function () {
            this.image().localPage.showImage(this.previousImage(), true);
        }
    }

    /**
     * A page of data displayed to the user in a tab. A page is more like a
     * section of data such as a Wikipedia article and all the images from that
     * article. A LocalPage is typically in a collection of pages that are shown
     * in a tab and whose contents can be navigated through.
     *
     * @param localPages The array of LocalPage objects that this localPage
     *            is a member of
     * @param article Data pertaining to the article that the page belongs to
     */
    function LocalPage(localPages, article) {
        /**
         * @prop string title The title for this page, which comes from the
         *       article it is based on
         */
        this.title = article.title;
        /** @prop array imageArray An ordered list of the images for this page */
        this.imageArray = [];
        /**
         * @prop object images A key-indexed list of the images where the keys
         *       are the image titles
         */
        this.images = {};

        /**
         * @param ImageData
         *            image The image data for the image that will be shown
         * @param bool
         *            openedDirectly Whether or not the image was opened
         *            directly or navigated to. If the image was opened
         *            directly, the downloading graphic is shown after the
         *            previous image is hidden. The next image is loaded after
         *            the previous one is finished being hidden. If the image
         *            was navigated to with the Previous or Next button, the
         *            image is hidden and then explicity reshown because the
         *            code in the image's onload handler may not fire.
         */
        this.showImage = function (image, openedDirectly) {
            var $imageMedia;
            if (openedDirectly === true) {
                $imageMedia = $('.image_modal_media');
                $imageElement = $('#image_modal_image');
                $imageVideo = $('#image_modal_video');

                if ($imageElement.is(':visible')) {
                    $imageElement.hide(500, function () {
                        imageModal.downloading(true);
                        imageModal.image(image);
                    }).removeClass('d-block');
                }

                if ($imageVideo.is(':visible')) {
                    $imageVideo.hide(500, function () {
                        imageModal.downloading(true);
                        imageModal.image(image);
                    }).removeClass('d-block');
                }

                if (image.isOgv()) {
                    $imageVideo.attr('src', image.original);
                    $imageVideo.show(500, function () {
                        imageModal.downloading(false);
                    }).addClass('d-block');
                }

            } else {
                imageModal.image(image);
            }

            var previousImage, nextImage;
            /*
             * The loaded image has data that can locate it in the localPages
             * array in order to find the previous and next images
             */
            if (localPages[image.localPageIndex] !== undefined) {
                var thisLocalPage = localPages[image.localPageIndex];
                var thisLocalPageIndex = +image.localPageIndex;
                var imageArrayIndex = +image.imageArrayIndex;
                if (imageArrayIndex === 0) { // User is viewing the first image of the local page
                    if (thisLocalPageIndex === 0) { // User is viewing the first local page
                        // The previous image is the last one in the last local
                        // page
                        previousImage = localPages[localPages.length - 1].imageArray[localPages[localPages.length - 1].imageArray.length - 1];
                    } else {
                        // The previous image is the last one in the previous local page
                        previousImage = localPages[thisLocalPageIndex - 1].imageArray[localPages[thisLocalPageIndex - 1].imageArray.length - 1];
                    }
                } else {
                    // The previous image is the previous one in the the current
                    // local page
                    previousImage = thisLocalPage.imageArray[imageArrayIndex - 1];
                }

                if (imageArrayIndex === thisLocalPage.imageArray.length - 1) { // User is viewing last image in local page
                    // User is viewing last image in last local page
                    if (thisLocalPageIndex === localPages.length - 1) {
                        // The next image is the first one in the first local page
                        nextImage = localPages[0].imageArray[0];
                    } else {
                        // The next image is the first on in the next local page
                        nextImage = localPages[thisLocalPageIndex + 1].imageArray[0];
                    }
                } else {
                    // The next image is the next one in the current local page
                    nextImage = thisLocalPage.imageArray[imageArrayIndex + 1];
                }

                imageModal.nextImage(nextImage);
                imageModal.previousImage(previousImage);
            }

            $imageMedia = $('.image_modal_media');
            $imageElement = $('#image_modal_image');
            $imageVideo = $('#image_modal_video');

            if (openedDirectly !== true) {
                $imageMedia.hide(500, function () {
                    imageModal.downloading(true);
                }).removeClass('d-block');

                if (image.isOgv()) {
                    $imageVideo.attr('src', image.original);
                    $imageVideo.show(500, function () {
                        imageModal.downloading(false);
                    }).addClass('d-block');
                }
            }

            $imageElement.on('load', function (event) {
                $(this).show(500, function () {
                    imageModal.downloading(false);
                }).addClass('d-block');
            });
        }
    } // End LocalPage

    /**
     * Data pertaining to an image to be shown
     *
     * @param image object Data from which the image thumbnail, original URL, and title can be obtained
     * @param Localpage localPage The LocalPage that this image is from
     * @param string|int localPageIndex The numerical index in a localPages array at which this local page is found
     */
    function ImageData(image, imageArrayIndex, localPage, localPageIndex){
        this.thumbnail = image.thumbnail;
        this.original = image.original;
        this.title = image.title;
        this.imageArrayIndex = imageArrayIndex;
        this.localPage = localPage;
        this.article = localPage.title;
        this.localPageIndex = localPageIndex;

        // Whether or not the data is for an image as opposed to other media
        this.isImage = function(){
            return !this.isOgv();
        };
        this.isSvg = function(){
            return this.original.match(/\.svg$/);
        };
        this.isOgv = function(){
            return this.original.match(/\.ogv$/);
        };
    }

    /*
     * There is a single image modal used to show images on the site.
     */
    var imageModal = new ImageModal();

    ko.applyBindings(imageModal, $('#image_modal')[0]);

    /**
     * A control object for the map that either displays information or provides
     * functionality
     *
     * Pass either a list of elements or the content, attributes and style used
     * to initialize this control.
     *
     * @constructor
     * @param object
     *            elementList A list of objects with an element property for
     *            each element to be added and a property named handlers which
     *            is an array of functions that register event listeners.
     * @param google.maps.ControlPosition
     *            position The position in the map where this control should be
     *            placed
     * @param string
     *            text Any text that should appear for this control's html view
     *            element
     * @param string
     *            className The class name for the view element
     * @param object
     *            style An object with properties for the element's style
     * @param array
     *            handlers An array of functions that will be called to set DOM
     *            listeners for the element
     */
    function UIControl(elementList, position, text, id, className, style,
            handlers) {
        this.CONTROL_TEXT_MAX_CHARS = 1000;
        this.CONTROL_ATTR_MAX_CHARS = 100;

        this.elementList = elementList;
        this.position = position;
        this.text = text;
        this.id = id;
        this.className = className;
        this.style = style;
        this.handlers = handlers;

        this.view = null;

        this.render();
    }

    /**
     * Create the view for this control
     */
    UIControl.prototype.render = function () {
        var controlDiv = document.createElement('div');
        this.view = controlDiv;

        if (Array.isArray(this.elementList.elements)
                && this.elementList.elements.length !== 0) {
            for ( var elementIndex in this.elementList.elements) {
                var element = this.elementList.elements[elementIndex];
                /*
                 * If the object has a type, it should be a Node. If it has a
                 * className, it should be an HTMLElement.
                 */
                if (element.type || element.nodeName) {
                    this.view.appendChild(element);
                }
            }
        } else {
            if (typeof this.text === 'string' && this.text.length > 0
                    && this.text.length < this.CONTROL_TEXT_MAX_CHARS) {
                this.view.text ? this.view.text = this.text
                        : this.view.textContent = this.text;
            }

            if (typeof this.id === 'string' && this.id.length > 0
                    && this.id.length < this.CONTROL_ATTR_MAX_CHARS) {
                this.view.id = this.id;
            }

            if (typeof this.className === 'string' && this.className.length > 0
                    && this.className.length < this.CONTROL_ATTR_MAX_CHARS) {
                this.view.className = this.className;
            }

            if (this.style && typeof this.style === 'object') {
                this.view.style = this.style;
            }
        }

        this.addHandlers();
    };

    /**
     * Register the handlers that were either passed to the constructor or the
     * elementList.
     */
    UIControl.prototype.addHandlers = function () {
        var handlerList = Array.isArray(this.elementList.handlers) ? this.elementList.handlers
                : (Array.isArray(this.handlers) ? this.handlers : null);

        if (Array.isArray(handlerList)) {
            this.handlers = [];
            for ( var handlerIndex in handlerList) {
                var handler = handlerList[handlerIndex];
                if (typeof handler === 'function') {
                    this.handlers.push(handler());
                }
            }
        }
    };

    /**
     * Remove all registered handlers
     */
    UIControl.prototype.removeHandlers = function () {
        if (Array.isArray(this.handlers) && this.handlers.length > 0) {
            for ( var handlerIndex in this.handlers) {
                var handler = this.handlers[handlerIndex];
                google.maps.event.removeListener(handler);
            }

            this.handlers = [];
        }
    };

    /**
     * Create the map and setup the controls
     */
    function initMap() {
        var mapDiv = document.querySelector('#map');
        var header = document.querySelector('#header');
        var siteinfo_button = document.querySelector('#siteinfo_button');
        var marker_menu = document.querySelector('#marker_menu');
        var infowindow = document.querySelector('#infowindow');
        var uicontrols = document.querySelector('#uicontrols');

        var center = new google.maps.LatLng(44.540, -78.546);

        function ErrorViewModel() {
            var self = this;

            this.message = ko.observable('');
            this.type = ko.observable('');

            this.setMessage = function (message, type) {
                window.setTimeout(function () {
                    self.message(message);

                    if (!type) {
                        type = 'warning';
                    }
                    self.type(type);
                }, 200);

                window.setTimeout(function () {
                    self.message('');
                    self.type('');
                }, 5000);
            }

        }

        function LocationDataViewModel() {
            var self = this;

            /*
             * this.services = ko.observable([
             *     ko.observable({
             *         service: 'yelp',
             *         serviceName: 'Yelp',
             *         data: ko.observableArray(),
             *         showView: true,
             *         showTab: function(){...} }
             *     ),
             *     ko.observable({
             *         service: 'streetview',
             *         serviceName: 'Google Street View',
             *         data: ko.observableArray(),
             *         showView: false,
             *         showTab: function(){...} }
             *     ),
             *     ko.observable({
             *         service: 'wikipedia',
             *         serviceName: 'Wikipedia',
             *         data: ko.observableArray(),
             *         showView: false,
             *         showTab: function(){...} }
             *     )
             *  ]);
             */
            var serviceSetup = [ {
                service : 'yelp',
                serviceName : 'Yelp',
            }, {
                service : 'streetview',
                serviceName : 'Google Street View'
            }, {
                service : 'wikipedia',
                serviceName : 'Wikipedia'
            } ];

            this.services = ko.observableArray();
            var services = this.services;

            $.each(serviceSetup, function (index, element) {
                function showTab() {
                    for ( var serviceIndex in services()) {
                        var service = services()[serviceIndex];
                        service().showView(false);
                    }

                    this.showView(true);
                    imageModal.activeService(this.service);
                }

                element.data = ko.observableArray();
                element.showTab = showTab;

                if (!index) {
                    element.showView = ko.observable(true);
                } else {
                    element.showView = ko.observable(false);
                }

                services.push(ko.observable(element));
            });

            /**
             * Whether or not data is downloading from a web API.
             */
            this.downloading = ko.observable(false);

            this.getService = function (name) {
                for ( var serviceIndex in services()) {
                    var service = services()[serviceIndex];
                    if (name === service().service) {
                        return service();
                    }
                }
            }
        }
        ;

        var errorViewModel = new ErrorViewModel();
        //ko.applyBindings(errorViewModel, $('#messages')[0]);

        if (!navigator.geolocation) {
            errorViewModel.setMessage(
                    'This browser does not support Geolocation', 'error');
        } else {
            function success(position) {
                center = new google.maps.LatLng(position.coords.latitude,
                        position.coords.longitude);
                map.setCenter(center);
                errorViewModel.setMessage('Geolocation found', 'success');
            }

            function error(err) {
                var message = '', status = 'error';
                switch (err.code) {
                case err.PERMISSION_DENIED:
                    message = 'This page does not have permission to use Geolocation';
                    break;
                case err.TIMEOUT:
                    message = 'A timeout error occured while finding the Geolocation';
                    break;
                case err.POSITION_UNAVAILABLE:
                    message = 'The Geolocation is not available at this time';
                    break;
                default:
                    message = 'An error occured while trying to find the Geolocation';
                }

                errorViewModel.setMessage(message, status);
            }

            navigator.geolocation.getCurrentPosition(success, error)
        }

        var map = new google.maps.Map(mapDiv, {
            center : center,
            zoom : 10,
            zoomControl : true,
            mapTypeControl : true,
            mapTypeControlOptions : {
                style : google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            scaleControl : true,
            streetViewControl : true,
            rotateControl : true,
            fullscreenControl : true,
            fullscreenControlOptions : {
                position : google.maps.ControlPosition.BOTTOM_CENTER
            }
        });

        function MarkerListViewModel() {
            var self = this;

            var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var labelIndex = 0;
            var panorama;

            /**
             * The displayed array of markers
             */
            this.markers = ko.observableArray();

            /**
             * The marker currently being shown in the InfoWindow
             */
            this.activeMarker = ko.observable(null);

            /**
             * The full list of markers. This is a temporary holding place that
             * is used when searching the locations.
             */
            this.allMarkers = ko.observableArray();

            /**
             * Whether or not a search is taking place
             */
            this.searching = ko.observable(false);

            /**
             * The InfoWindow used to display info about a location
             */
            this.infoWindow = new google.maps.InfoWindow();

            this.infoWindow.addListener('closeclick', function(){
                map.setOptions({gestureHandling : 'auto'});
            });

            this.hideMarkers = function(){
                $.each(this.allMarkers(), function(index, marker){
                    marker.toggleVisibility(false);
                });
            }

            this.showMarkers = function(){
                $.each(this.allMarkers(), function(index, marker){
                    marker.toggleVisibility(true);
                });
            }

            this.startSearch = function () {
                self.searching(true);
            };

            /**
             * Search the location labels for a match
             */
            this.search = function () {
                var $searchBox = $('#search');
                $searchBox[0].focus();

                if (self.allMarkers().length === 0) {
                    self.allMarkers(self.markers.removeAll());
                }

                self.hideMarkers();

                var searchBoxInput = $searchBox.val();
                self.markers(self.allMarkers().filter(
                        function (marker) {
                            var match = marker.getLabel().match(
                                    new RegExp(searchBoxInput, 'i'));
                            if (match) {
                                marker.toggleVisibility(true);
                            }
                            return match;
                        }));
                return searchBoxInput;
            };

            /**
             * End searching and return the full list of locations
             */
            this.endSearch = function (item, event) {
                self.searching(false);
                self.showMarkers();
                self.markers(self.allMarkers.removeAll());
            };

            /**
             * Add a maker to the map at the provided location
             *
             * @this MarkerListViewModel
             */
            this.addMarker = function (location) {
                /*
                 * The local StreetViewPanorama
                 */
                var panorama = map.getStreetView();
                var panoramaData = null;
                /*
                 * A StreetViewService item for when local StreetView data is
                 * unavailable
                 */
                var sv = new google.maps.StreetViewService();

                /*
                 * This is required in order to load the Street View data, but
                 * it will still not be available the first the Street View
                 * Location data is queried.
                 */
                panorama.setVisible(true);
                panorama.setVisible(false);

                var marker = new google.maps.Marker({
                    position : location,
                    map : map,
                    label : labels[labelIndex++ % labels.length],
                });
                marker.setTitle(marker.getLabel());
                marker.hover = ko.observable(false);
                marker.selected = ko.observable(false);
                marker.isSelected = ko.observable(false);
                marker.editing = ko.observable(false);
                marker.observableMap = ko.observable(map);
                marker.locationDescription = ko.observable('');

                sv.getPanorama({
                    location : marker.getPosition()
                }, function (data, status) {
                    if (status === google.maps.StreetViewStatus.OK) {
                        panoramaData = data;
                        marker.locationDescription(data.location.description);
                    }
                });

                marker.description = ko.pureComputed(function () {
                    return marker.locationDescription();
                });

                marker.originalClasses = '';

                marker.toggleVisibility = function (visible, event) {
                    if (event && event.stopPropagation) {
                        event.stopPropagation();
                    }

                    if (visible === true) {
                        visible = map;
                    } else if (visible === false) {
                        visible = null
                    } else {
                        visible = this.getMap() ? null : map;
                    }

                    this.setMap(visible);
                    this.observableMap(this.getMap());

                };

                marker.getVisibilityTitle = ko.computed(function () {
                    if (marker.observableMap()) {
                        return 'Hide';
                    } else {
                        return 'Show';
                    }
                });

                marker.edit = function (item, event) {
                    if (event && event.stopPropagation) {
                        event.stopPropagation();
                    }
                    this.editing(true);
                    var markerIndex = self.markers.indexOf(marker);
                    var $markerListItem = $('#marker_list_item_' + markerIndex);
                    var input = $markerListItem.find('.marker_list_label_input');
                    input[0].focus();
                };

                /**
                 * Remove a single marker
                 */
                marker.removeMarker = function () {
                    marker.toggleVisibility(false);
                    self.markers.remove(this);
                };

                marker.saveLabel = function (item, event) {
                    if (event.key === "Enter" || event.keyCode === 13
                            || event.type === 'blur') {
                        var newLabel = $(event.target).closest(
                                '.marker_list_label_input').val();
                        marker.setLabel(newLabel);
                        marker.setTitle(newLabel);
                        marker.editing(false);
                    } else {
                        return true;
                    }
                };

                /**
                 * Update the InfoWindow for this marker
                 */
                marker.updateInfoWindow = function () {
                    self.infoWindow.setContent($('#infowindow')[0]);
                }

                marker.openStreetView = function (element, event) {
                    if (event && event.stopPropagation) {
                        event.stopPropagation();
                    }
                    panorama.setPosition(marker.getPosition());

                    panorama.setVisible(true);

                    panorama.addListener('closeclick', function (event) {
                        this.setVisible(false);
                    });
                }

                marker.openInfoWindow = function () {
                    self.infoWindow.open(map, marker);
                    map.setOptions({gestureHandling : 'cooperative'});
                }

                marker.locationDataViewModel = ko.observable(new LocationDataViewModel());

                /*
                 * Go to the marker and show the InfoWindow
                 */
                marker.goToLocation = function (element, event) {
                    if (event && event.stopPropagation) {
                        event.stopPropagation();
                    }
                    markerListViewModel.activeMarker(marker);
                    map.panTo(marker.getPosition());
                    marker.updateInfoWindow();
                    marker.openInfoWindow();

                    /*
                     * Enable the download state
                     */
                    marker.locationDataViewModel().downloading(true);

                    var streetViewPages = [];

                    /*
                     * Only get Street View images if they have not already been
                     * loaded and panorama data is detected
                     */
                    if (marker.locationDataViewModel().getService('streetview')
                            .data().length === 0
                            && panoramaData && panoramaData.location.pano) {
                        var requestURL = 'https://maps.googleapis.com/maps/api/streetview?key=AIzaSyAlGK97uekQTDMR4h7Wr5lLtENUgpOD7eo&pano='
                                + panoramaData.location.pano;

                        var streetViewArticle = {title: 'Google Street View'};

                        var streetViewPage = new LocalPage(streetViewPages, streetViewArticle);
                        var imageTitles = {
                                0:   'North',
                                90:  'East',
                                180: 'South',
                                270: 'West'
                        };

                        for (var i = 0; i < 360; i += 90) {
                            var imageTitle = imageTitles[i];
                            var imageParam = {
                                    title: imageTitle,
                                    thumbnail: requestURL + '&heading=' + i + '&size=100x100',
                                    original: requestURL + '&heading=' + i + '&size=600x300'
                            }
                            var imageData = new ImageData(imageParam, i / 90, streetViewPage, 0);
                            streetViewPage.imageArray.push(imageData);
                            streetViewPage.images[imageTitle] = imageData;
                        }

                        streetViewPages.push(streetViewPage);
                        marker.locationDataViewModel().getService('streetview').data(streetViewPages);
                    }

                    /*
                     * Create and send the request for Yelp review data
                     */
                    if (marker.locationDataViewModel().getService('yelp')
                            .data().length === 0) {
                        var jqxhr = $.ajax(
                                '/search-my-backyard',
                                {
                                    headers : {
                                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                                    },
                                    method : 'POST',
                                    data : {
                                        location : marker.getPosition().lat() + ',' + marker.getPosition().lng()
                                    },
                                    dataType : 'json'
                                }).done(
                                function (response) {
                                    if (!response.data) {
                                        errorViewModel.setMessage(
                                                'Location data not found', 'error');
                                        return;
                                    }

                                    if (response.data.yelp) {
                                        var yelpData = response.data.yelp;
                                        marker.locationDataViewModel().getService('yelp').data(yelpData);
                                    }
                                }).fail(
                                function (jqxhr, status, error) {
                                    errorViewModel.setMessage('Could not retrieve location data', 'error');
                                    console.error(error);
                                }).always(function () {
                            marker.locationDataViewModel().downloading(false);
                            marker.openInfoWindow();
                        });
                    } else {
                        marker.locationDataViewModel().downloading(false);
                        marker.openInfoWindow();
                    }
                    /*
                     * Create and send the request for images from Wikipedia
                     * articles near the Geolocation
                     */

                    /*
                     * Create the URL for retrieving a list of articles in the
                     * area and their image names
                     *
                     * Example:
                     * https://en.wikipedia.org/w/api.php?action=query&format=jsonfm&generator=geosearch&colimit=50&
                     * prop=coordinates|images&imlimit=max&ggsradius=10000&ggslimit=50&ggscoord=39.68711024716294|-104.80545043945312
                     */
                    if (marker.locationDataViewModel().getService('wikipedia')
                            .data().length === 0) {
                        var wpApiUrl = 'https://en.wikipedia.org/w/api.php?'
                                + 'action=query&format=json&origin=*&'
                                + 'generator=geosearch&colimit=50&'
                                + 'prop=coordinates|images&imlimit=max&'
                                + 'ggsradius=10000&ggslimit=50&ggscoord='
                                + marker.getPosition().lat() + '|'
                                + marker.getPosition().lng();

                        wpApiUrl = window.encodeURI(wpApiUrl);

                        var wpJqxhr = $
                                .ajax(wpApiUrl, {
                                    /*
                                     * Removed X-CSRF-TOKEN header because it
                                     * interfered with Wikipedia setting the
                                     * access-control-allow-origin header in
                                     * order to allow cross-origin access to
                                     * their API.
                                     */
                                    method : 'GET',
                                    dataType : 'json'
                                })
                                .done(
                                        function (response) {
                                            var pages = {};
                                            var pageIds = [];
                                            if (response.query.pages) {
                                                pages = response.query.pages;

                                                /*
                                                 * The list of titles to join
                                                 * with a vertical bar to send
                                                 * as parameters on the next
                                                 * Wikipedia API call
                                                 */
                                                var titles = [];
                                                /*
                                                 * Contains objects listing the
                                                 * articles and their images
                                                 */
                                                var localPages = [];
                                                /*
                                                 * The maximum number of
                                                 * articles to query.
                                                 */
                                                var maxArticles = 10;
                                                /*
                                                 * The current article count
                                                 */
                                                var articleCount = 0;

                                                /*
                                                 * For every result, store the
                                                 * title and an object of image
                                                 * name keys and image URLs. The
                                                 * URLs will be populated on the
                                                 * next API call. There will
                                                 * also be an imageArray that
                                                 * will contain just the file
                                                 * URLs so that Knockout's
                                                 * foreach can loop through
                                                 * them.
                                                 */
                                                for ( var pageIndex in pages) {
                                                    /*
                                                     * The API only allows a max
                                                     * of 50 article titles to
                                                     * be passed as parameters,
                                                     * but we may use less.
                                                     */
                                                    if (articleCount > maxArticles) {
                                                        break;
                                                    }
                                                    var article = pages[pageIndex];

                                                    /*
                                                     * Each article and images
                                                     * for the article will be
                                                     * stored in a localPage
                                                     * object and placed in the
                                                     * localPages array.
                                                     */
                                                    var localPage = new LocalPage(localPages, article);

                                                    var localImages = {};

                                                    /*
                                                     * Grab the first five images from each article
                                                     */
                                                    if (Array.isArray(article.images)
                                                            && article.images.length > 0) {
                                                        for (var i = 0; i < 5; i++) {
                                                            if (!article.images[i]) {
                                                                break;
                                                            }
                                                            localImages[article.images[i].title] = {};
                                                            titles.push(article.images[i].title);
                                                        }

                                                        localPage.images = localImages;
                                                        localPages.push(localPage);
                                                    }

                                                    articleCount++;
                                                }

                                                /*
                                                 * Create the URL for retrieving the image URLs for the images found in the articles in the geographic area
                                                 */
                                                var wpImageUrl = window.encodeURI('https://en.wikipedia.org/w/api.php'
                                                                + '?action=query&format=json&origin=*&'
                                                                + 'prop=pageimages&'
                                                                + 'piprop=thumbnail|name|original&pithumbsize=200&'
                                                                + 'titles='
                                                                + titles.join('|'));

                                                var wpimageinfoJqxhr = $.ajax(wpImageUrl, {
                                                            /*
                                                             * Required for Laravel's VerifyCsrfToken middleware
                                                             * Previously, the X-CSRF-TOKEN header was set, but this interfered
                                                             * with wikipedia.org setting the header that allows cross-domain access.
                                                             *  The dataType was set to jsonp to circumvent this.
                                                             */
                                                            method : 'GET',
                                                            dataType : 'json'
                                                        })
                                                        .done(
                                                                function (imageinfo_resp) {
                                                                    if (imageinfo_resp.query.pages) {
                                                                        /*
                                                                         * For every page of pageimages data, search through localPages to find the element in the images
                                                                         * collection with that image name for a key and store the URLs there.
                                                                         *
                                                                         * Each "page" consists of data for a single image
                                                                         */
                                                                        for ( var imageIndex in imageinfo_resp.query.pages) {
                                                                            var image = imageinfo_resp.query.pages[imageIndex];

                                                                            for ( var localPageIndex in localPages) {
                                                                                var aLocalPage = localPages[localPageIndex];

                                                                                if (aLocalPage.images[image.title] !== undefined) {
                                                                                    var imageParam = {
                                                                                      thumbnail: image.thumbnail.source,
                                                                                      original: image.original.source,
                                                                                      title : image.title
                                                                                    };

                                                                                    var imageArrayIndex = aLocalPage.imageArray.length;
                                                                                    var imageData = new ImageData(imageParam, imageArrayIndex, aLocalPage, localPageIndex);
                                                                                    aLocalPage.images[image.title] = imageData;
                                                                                    aLocalPage.imageArray.push(imageData);
                                                                                }
                                                                            }
                                                                        }
                                                                        marker.locationDataViewModel().getService('wikipedia').data(localPages);
                                                                    }
                                                                })
                                                        .fail(
                                                                function (jqxhr, status, error) {
                                                                    errorViewModel.setMessage(
                                                                                    'Could not retrieve Wikipedia image data',
                                                                                    'error');
                                                                    console.error(error);
                                                                });
                                            }
                                        })
                                .fail(
                                        function (jqxhr, status, error) {
                                            errorViewModel.setMessage(
                                                            'Could not retrieve Wikipedia article data',
                                                            'error');
                                            console.error(error);
                                        }).always(function () {
                                    marker.openInfoWindow();
                                });
                    }
                };

                marker.listItemMouseEnter = function () {
                    this.setAnimation(google.maps.Animation.BOUNCE);
                };

                marker.listItemMouseLeave = function () {
                    this.setAnimation(null);
                };

                /**
                 * Enable a marker's selected property. This will keep the Saved Locations list item highlighted.
                 */
                marker.select = function (toggle) {
                    if (typeof toggle !== 'boolean') {
                        toggle = !marker.selected();
                    }
                    marker.selected(toggle);
                    marker.isSelected(marker.selected());
                };

                marker.addListener('mouseover', self.highlightMarker);
                marker.addListener('mouseout', self.unHighlightMarker);
                marker.addListener('click', marker.goToLocation);
                self.markers.push(marker);

                this.infoWindow.addListener('closeclick', function () {
                    uicontrols.appendChild(infowindow);
                });
            };

            /**
             * Enable a marker's hover property. This will highlight the entry in the Saved Locations list
             *
             * @this google.maps.Marker
             */
            this.highlightMarker = function (event) {
                this.hover(true);
                this.selected(false);
            };

            /**
             * Disable a marker's hover property. This will unhighlight the entry in the Saved Locations list
             */
            this.unHighlightMarker = function (event) {
                this.hover(false);
                this.selected(this.isSelected());
            };

            /**
             * Assign the map to all markers
             */
            this.setMapOnAll = function (map) {
                for ( var markerIndex in self.markers()) {
                    var marker = self.markers()[markerIndex];
                    marker.setMap(map);
                    marker.observableMap(map);
                }
            };

            /**
             * Remove selected markers
             */
            this.removeSelectedMarkers = function () {
                self.getSelectedMarkers().map(function (element) {
                    element.toggleVisibility(false);
                    element.removeMarker();
                });
            };

            /**
             * Hide selected markers
             */
            this.toggleVisibleSelectedMarkers = function () {
                self.getSelectedMarkers().map(function (element) {
                    element.toggleVisibility(!element.observableMap());
                });
            };

            /**
             * Get the selected markers
             */
            this.getSelectedMarkers = function () {
                return self.markers().filter(function (element) {
                    return element.isSelected();
                });
            };

            /**
             * Deselect all markers in the Saved Locations list
             */
            this.toggleMarkerSelection = function () {

                var allSelected = self.markers().every(function (element) {
                    return element.isSelected();
                });

                self.markers().map(function (element) {
                    element.select(!allSelected);
                });

            };

            /**
             * Whether or not there is at least one marker being shown
             */
            this.canHideAllSelection = function () {
                return self.getSelectedMarkers().some(function (element) {
                    return element.observableMap();
                });
            };
        }

        var markerListViewModel = new MarkerListViewModel();

        /*
         * Create the map's event listeners
         */

        /*
         * Add a marker to the map at the clicked location
         */
        map.addListener('click', function (event) {
            markerListViewModel.addMarker(event.latLng);
        });

        /*
         * Apply the view-model contexts
         */
        ko.applyBindings(markerListViewModel, marker_menu);
        ko.applyBindings(markerListViewModel, infowindow);

        /*
         * Create the list of elements and handlers from which to make a UI control
         *
         * elements - An array of elements that will be appended to the UI control container
         * handlers - A function that returns the addDomListener reference for the control
         */

        /*
         * Create the Header UI Control
         */
        var headerElements = {
            elements : [ header ]
        };

        /*
         * Create the list of elements for the Saved Locations list of markers
         */
        var markerListElements = {
            elements : [ marker_menu ]
        };

        /*
         * Initialize the custom UI controls
         */
        var headerControl = new UIControl(headerElements,
                google.maps.ControlPosition.RIGHT_TOP);
        var markerListControl = new UIControl(markerListElements,
                google.maps.ControlPosition.RIGHT_TOP);

        /*
         * Add the custom UI controls to the map
         */
        map.controls[headerControl.position].push(headerControl.view);
        map.controls[markerListControl.position].push(markerListControl.view);
    }

    initMap();
});
