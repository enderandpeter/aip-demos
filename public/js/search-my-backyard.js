window.addEventListener('load', function(){
    /**
     * A control object for the map that either displays information or provides functionality
     * 
     * Pass either a list of elements or the content, attributes and style
     * used to initialize this control.
     * 
     * @constructor
     * @param object elementList A list of objects with an element property for each element to be added
     * 							 and a property named handlers which is an array of functions that register event listeners.
     * @param google.maps.ControlPosition position The position in the map where this control should be placed
     * @param string text Any text that should appear for this control's html view element
     * @param string className The class name for the view element
     * @param object style An object with properties for the element's style
     * @param array handlers An array of functions that will be called to set DOM listeners for the element
     */
	function UIControl(elementList, position, text, id, className, style, handlers){
    	var CONTROL_TEXT_MAX_CHARS = 1000;
    	var CONTROL_ATTR_MAX_CHARS = 100;
    	
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
	UIControl.prototype.render = function(){
		var controlDiv = document.createElement('div');
    	this.view = controlDiv;
    	
		if(Array.isArray(this.elementList.elements) && this.elementList.elements.length !== 0){
    		for(var elementIndex in this.elementList.elements){
    			var element = this.elementList.elements[elementIndex];
    			/*
    			 * If the object has a type, it should be a Node. If it has
    			 * a className, it should be an HTMLElement.
    			 */
    			if(element.type || element.nodeName){
    				this.view.appendChild(element);
    			}
    		}
    	} else {
    		if(typeof text === 'string' && this.text.length > 0 && this.text.length < CONTROL_TEXT_MAX_CHARS){
    			this.view.text ? this.view.text = text : this.view.textContent = text;
        	}    	
        	
        	if(typeof this.id === 'string' && this.id.length > 0 && this.id.length < CONTROL_ATTR_MAX_CHARS){
        		this.view.id = id;
        	}
    		
        	if(typeof this.className === 'string' && this.className.length > 0 && this.className.length < CONTROL_ATTR_MAX_CHARS){
        		this.view.className = className;
        	}
        	
        	if(this.style && typeof this.style === 'object'){
        		this.view.style = style;
        	}
    	}
		
		this.addHandlers();
	};
	
	/**
	 * Register the handlers that were either passed to the constructor or the elementList.
	 */
	UIControl.prototype.addHandlers = function(){
		var handlerList = Array.isArray(this.elementList.handlers) ? this.elementList.handlers : ( Array.isArray(this.handlers) ? this.handlers : null ); 
		
		if(Array.isArray(handlerList)){
			this.handlers = [];
			for(var handlerIndex in handlerList){
				var handler = handlerList[handlerIndex];
				if(typeof handler === 'function'){
					this.handlers.push(handler());
				}    					
			}
		}
	};
	
	/**
	 * Remove all registered handlers
	 */
	UIControl.prototype.removeHandlers = function(){
		if(Array.isArray(this.handlers) && this.handlers.length > 0){
			for(var handlerIndex in this.handlers){
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
        
        function ErrorViewModel(){
        	var self = this; 
        	
        	this.message = ko.observable('');
        	this.type = ko.observable('');

        	this.setMessage = function(message, type){
        		window.setTimeout(function(){
        			self.message(message);
        		
	        		if(!type){
	        			type = 'warning';
	        		}
	        		self.type(type);	        		
        		}, 200);
        		
        		window.setTimeout(function(){
        			self.message('');
        			self.type('');
        		}, 5000);
        	}
        	
        }
        
        function LocationDataViewModel(){
        	var self = this;
        	
        	this.data = ko.observable({
        		yelp: ko.observableArray([]),
        		streetview: ko.observableArray([])
        	});
        	
        	/**
        	 * Whether or not data is downloading from a web API.
        	 */
        	this.downloading = ko.observable(false);
        };
        
        var errorViewModel = new ErrorViewModel();
        ko.applyBindings(errorViewModel, document.querySelector('#messages'));
        
        var locationDataViewModel = new LocationDataViewModel();
        ko.applyBindings(locationDataViewModel, document.querySelector('#infowindow_content'));
        
        if (!navigator.geolocation) {
        	errorViewModel.setMessage('This browser does not support Geolocation', 'error');
        } else {
        	function success(position){
        		center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        		map.setCenter(center);
        		errorViewModel.setMessage('Geolocation found', 'success');
        	}
        	
        	function error(err){
        		var message = '', status = 'error';
        		switch(err.code){        			
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
          center: center,
          zoom: 10,
          zoomControl: true,
          mapTypeControl: true,
          mapTypeControlOptions: {
        	  style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
          },
          scaleControl: true,
          streetViewControl: true,
          rotateControl: true,
          fullscreenControl: true,
          fullscreenControlOptions : {
        	position: google.maps.ControlPosition.BOTTOM_CENTER
          }
        });
        
        function MarkerListViewModel(){
        	var self = this;
        	
        	var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        	var labelIndex = 0;
        	var panorama;
        	
        	/**
        	 * The displayed array of markers
        	 */
        	this.markers = ko.observableArray([]);
        	
        	/**
        	 * The full list of markers. This is a temporary holding place that is used when searching the locations.
        	 */
        	this.allMarkers = ko.observableArray([]);

        	/**
        	 * Whether or not a search is taking place
        	 */
        	this.searching = ko.observable(false);        	
        	
        	/**
        	 * The InfoWindow used to display info about a location
        	 */
        	this.infoWindow = new google.maps.InfoWindow({
        		map: map
        	});
        	
        	this.startSearch = function(){
        		self.searching(true);        		        		
        	};
        	
        	/**
        	 * Search the location labels for a match
        	 */
        	this.search = function(){
        		var $searchBox = $('#search');        		
        		$searchBox[0].focus();        		
        		
        		if(self.allMarkers().length === 0){
        			self.allMarkers(self.markers.removeAll());
        		}        		        		
        		self.hideMarkers();
        		
        		var searchBoxInput = $searchBox.val();        		
        		self.markers(self.allMarkers().filter(function(element){
        			var match = element.getLabel().match(new RegExp(searchBoxInput, 'i'));
        			if(match){
        				element.toggleVisibility(true);
        			}
        			return match;
        		}));
        		return searchBoxInput;
        	};
        	
        	/**
        	 * End searching and return the full list of locations
        	 */
        	this.endSearch = function(){
        		self.searching(false);
        		self.markers(self.allMarkers.removeAll());
        		self.showMarkers();
        	};
        	
        	/**
             * Add a maker to the map at the provided location
             * 
             * @this MarkerListViewModel
             */
            this.addMarker = function(location) {
            	  /*
            	   * The local StreetViewPanorama
            	   */
            	  var panorama = map.getStreetView();
            	  /*
            	   * A StreetViewService item for when local StreetView data is unavailable
            	   */
            	  var sv = new google.maps.StreetViewService();
            	  
            	  /*
            	   * This is required in order to load the Street View data, but it will still
            	   * not be available the first the Street View Location data is queried.
            	   */
            	  panorama.setVisible(true);
				  panorama.setVisible(false);
            	
    			  var marker = new google.maps.Marker({
    				    position: location,
    				    map: map,
    				    label: labels[labelIndex++ % labels.length],    				    
    			  });
    			  marker.setTitle(marker.getLabel());
    			  marker.hover = ko.observable(false);
    			  marker.selected = ko.observable(false);
    			  marker.isSelected = ko.observable(false);
    			  marker.editing = ko.observable(false);
    			  marker.observableMap = ko.observable(map);
    			  marker.locationDescription = ko.observable('');
    			  
    			  sv.getPanorama({location: marker.getPosition()}, function(data, status){
					  if(status === google.maps.StreetViewStatus.OK){
						  marker.locationDescription(data.location.description);
					  }
				  });
    			  
    			  marker.description = ko.pureComputed(function(){
    				  return marker.locationDescription();
    			  });
    			  
    			  marker.originalClasses = '';
    			  
    			  marker.toggleVisibility = function(visible, event){
    				if(event && event.stopPropagation){
    					event.stopPropagation();
    				}
    				  
					if(visible === true){
						visible = map;
					} else if (visible === false){
						visible = null
					} else {
						visible = this.getMap() ? null : map;
					}
    				    				
					this.setMap(visible);
    				this.observableMap(this.getMap());

    			  };
    			  
    			  marker.getVisibilityTitle = ko.computed(function(){
    				if(marker.observableMap()){
    					return 'Hide';
    				} else {
    					return 'Show';
    				}
    			  });
    			  
    			  marker.edit = function(){
    				if(event && event.stopPropagation){
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
    			  marker.removeMarker = function(){
    				 marker.toggleVisibility(false);
	    	    	 self.markers.remove(this);
	    	     };
    			  
    			  marker.saveLabel = function(item, event){
    				if(event.key === "Enter" || event.keyCode === 13 || event.type === 'blur'){
    					var newLabel = $(event.target).closest('.marker_list_label_input').val();
    					marker.setLabel(newLabel);
    					marker.setTitle(newLabel);
    					marker.editing(false);
    					marker.updateInfoWindow();
    				} else {
    					return true;
    				}
    			  };
    			  
    			  /**
    			   * Update the InfoWindow for this marker
    			   */
    			  marker.updateInfoWindow = function(){
    				  self.infoWindow.setContent(document.querySelector('#infowindow'));
    				  $('#infowindow_title').text(marker.getLabel());
    				  $('#infowindow_lat').text(marker.getPosition().lat());
    				  $('#infowindow_lng').text(marker.getPosition().lng());
    			  };
    			  
    			  marker.openStreetView = function(){
    				  if(event && event.stopPropagation){
    					event.stopPropagation();
    				  }
    				  panorama.setPosition(marker.getPosition());
    				  
    				  if(!panorama.getLocation()){
    					  return;
    				  }
    				  panorama.setVisible(true);
    				  
    				  panorama.addListener('closeclick', function(event){
    					  this.setVisible(false);
    				  });
    			  }
    			  
    			  marker.goToLocation = function(){
    				  if(event && event.stopPropagation){
      					event.stopPropagation();
      				  }
    				  map.panTo(marker.getPosition());
    				  marker.updateInfoWindow();
    				  self.infoWindow.open(map, marker);
    				  
    				  locationDataViewModel.downloading(true);
    				  
    				  var jqxhr = $.ajax('/search-my-backyard',
    						 {
		    				    headers: {
		    				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    				    },	
		    					method: 'POST',
    					  		data: {location: marker.getPosition().lat() + ',' + marker.getPosition().lng()},
    					  		dataType: 'json'
    						 }
    				  ).done(function(response){
    					  if(!response.data){
    						  errorViewModel.setMessage('Location data not found', 'error');
    						  return;
    					  }
    					  
    					  if(response.data.yelp){
    						 var yelpData = response.data.yelp;
    						 locationDataViewModel.data().yelp(yelpData);
    					  }    					 
    				  }).fail(function(jqxhr, status, error){
    					  errorViewModel.setMessage('Could not retrieve location data', 'error');
    					  console.error(error);
    				  }).always(function(){
    					  locationDataViewModel.downloading(false);
    				  });
    			  };
    			  
    			  marker.listItemMouseEnter = function(){
    				  this.setAnimation(google.maps.Animation.BOUNCE);
    			  };
    			  
				  marker.listItemMouseLeave = function(){
    				  this.setAnimation(null);
    			  };
    			  
    			  /**
 	               * Enable a marker's selected property. This will keep the Saved Locations list item highlighted.
	               */
    			  marker.select = function(){
    				  marker.selected(!marker.selected());
    				  marker.isSelected(marker.selected());
    			  };
    			  
    			  marker.addListener('mouseover', self.highlightMarker);
    			  marker.addListener('mouseout', self.unHighlightMarker);
    			  marker.addListener('click', marker.goToLocation);
    			  self.markers.push(marker);
    			  
    			  this.infoWindow.addListener('closeclick', function(){    				  
    				  uicontrols.appendChild(infowindow);
    				  locationDataViewModel.data().yelp.removeAll();
    			  });
        	};
            
            /**
             * Enable a marker's hover property. This will highlight the entry in the Saved Locations list
             * 
             * @this google.maps.Marker
             */
            this.highlightMarker = function(event){
            	this.hover(true);
            	this.selected(false);
            };
            
            /**
             * Disable a marker's hover property. This will unhighlight the entry in the Saved Locations list
             */
            this.unHighlightMarker = function(event){
            	this.hover(false);
            	this.selected(this.isSelected());
            };
            
    	     /**
    	      * Assign the map to all markers
    	      */
    	     this.setMapOnAll = function (map) {
    	    	 for(var markerIndex in self.markers()){
    	    		 var marker = self.markers()[markerIndex];
    	    		 marker.setMap(map);
    	    		 marker.observableMap(map);
    	    	 }    	       
    	     };
            
    	     /**
              * Remove all markers
              */
    	     this.removeMarkerList = function(){
    	    	this.hideMarkers();
             	this.markers([]);
    	     };
    	     
    	     /**
    	      * Deselect all markers in the Saved Locations list
    	      */
    	     this.clearMarkerList = function(){
    	    	 for(var markerIndex in self.markers()){
    	    		 var marker = self.markers()[markerIndex];
    	    		 marker.selected(false);
    	    		 marker.isSelected(marker.selected());
    	    	 }
    	     };
    	     
    	     /**
    	      * Whether or not there is at least one marker being shown
    	      */
    	     this.canHideAll = function(){
    	    	 return self.markers().some(function(element){    	    		 
    	    		 return element.observableMap();
    	    	 });
    	     };
    	     
    	     /**
    	      * Hide all markers from view
    	      */
            this.hideMarkers = function() {
        	  this.setMapOnAll(null);
        	};
            
            /**
             * Show all markers
             */
            this.showMarkers = function() {
              this.setMapOnAll(map);
            };
        	
        }
        
        var markerListViewModel = new MarkerListViewModel();
        
        /*
         * Create the map's event listeners
         */
     
        /*
         * Add a marker to the map at the clicked location
         */
        map.addListener('click', function(event) {
        	markerListViewModel.addMarker(event.latLng);
        });        
        
        /*
         * Apply the view-model contexts
         */
        ko.applyBindings(markerListViewModel, marker_menu);        
        
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
        	elements: [header],
        	handlers: [
	           function(){
	         	  return google.maps.event.addDomListener(siteinfo_button, 'click', function(event){
	         		  var siteinfo = document.querySelector('#siteinfo');
	         		  siteinfo.classList.toggle('hide');
	         		  google.maps.event.trigger(map, 'resize');
	         	  });
	           }
        	]
        };
        
        /*
         * Create the list of elements for the Saved Locations list of markers
         */
        var markerListElements = {
        	elements: [marker_menu]	
        };
        
        /*
         * Initialize the custom UI controls
         */
        var headerControl = new UIControl(headerElements, google.maps.ControlPosition.TOP_CENTER);
        var markerListControl = new UIControl(markerListElements, google.maps.ControlPosition.RIGHT_TOP);
        
        /*
         * Add the custom UI controls to the map
         */
        map.controls[headerControl.position].push(headerControl.view);
        map.controls[markerListControl.position].push(markerListControl.view);
    }
    
    initMap();
});

