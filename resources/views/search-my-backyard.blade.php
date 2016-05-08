@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/search-my-backyard.css" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@include('scripts.jquery')
@include('scripts.bootstrap')
@include('scripts.ko')
@include('scripts.google-maps')
@push ('scripts')
	<script src="js/search-my-backyard.js"></script>
@endpush

@section('body-content')
	<div id="map"></div>
	<div id="uicontrols">	
		<div id="header" class="uicontrol">
			<header>
				<h1>Search My Backyard!</h1>
				<button id="siteinfo_button" class="btn btn-info btn-xs" title="More Info" aria-label="More Info"><span></span>
					<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
				</button>
			</header>		
			<div class="hide" id="siteinfo">
				<p>Welcome to <em>Search My Backyard!</em>, a JavaScript-based web app for searching locations around the world and finding out more about them.
				Please allow the web app to discover your location. Then click anywhere on the map to create a marker. Your location will be saved in the <strong>Locations</strong> list.</p> 
				<h2>Coming Soon!</h2>
				<p>With the help of sites like Google Maps, Yelp, and Wikipedia, you can search any location in the world (by Country, State/Province, City, Street,
				 Address, etc.) and get a wealth of information in once place. Check out interesting facts, news articles, images, and well-reviewed locations 
				 about anywhere on the planet, just like it was your own neighborhood!</p>
				 <p><strong>Note:</strong> Please close this infobox to enable the fullscreen button.</p>
			</div>
			<div id="messages" data-bind="css: { show: type, error: type() === 'error', success: type() === 'success', warning: type() === 'warning' }, text: message">			
			</div>				
		</div>
		<div id="marker_menu">
			<form id="marker_menu_form">
			<h2>Saved Locations</h2>
			<div id="marker_menu_buttons">
				<ul id="marker_menu_buttons_list" class="list-inline">
					<li>					
						<div data-bind="if: markers().length !== 0" class="btn-group" role="group" aria-label="Manage all locations">
							<button type="submit" title="Clear all selections" class="btn btn-default" data-bind="click: clearMarkerList">
								<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
							</button>
							<button type="submit" title="Remove all markers" class="btn btn-default" data-bind="click: removeMarkerList">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>							
							</button>
							<div data-bind="if: canHideAll()" class="button_container">
								<button type="submit" title="Hide all markers" class="btn btn-default" data-bind="click: hideMarkers">																
									<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>								
								</button>
							</div>
							<div data-bind="ifnot: canHideAll()" class="button_container">
								<button type="submit" title="Show all markers" class="btn btn-default" data-bind="click: showMarkers">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>								
								</button>
							</div>
							<button type="submit" title="Search locations" class="btn btn-default" data-bind="click: startSearch">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							</button>
						</div>
					</li>
				</ul>
				<div id="search_container" data-bind="if: searching">
					<input id="search" type="text" data-bind="textInput: search()" />
					<button id="search_close_button" class="btn btn-default btn-xs" title="Close" data-bind="click: endSearch">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</button>
				</div>
			</div>
			<ul id="marker_list" data-bind="foreach: markers">
				<li class="marker_list_item" data-bind="css: { hovering: hover, selected: selected }, click: select, event: { mouseenter: listItemMouseEnter, mouseleave: listItemMouseLeave }, attr: { id: 'marker_list_item_' + $index() }">
					<div class="row">
						<div id="label_container" class="col-md-12">
							<div class="marker_list_label_header_container" data-bind="ifnot: editing">
								<h3 class="marker_list_label_header" data-bind="click: edit, text: getLabel()"></h3>
							</div>
							<div id="label_edit_container" data-bind="if: editing">
								<input class="marker_list_label_input form-control" data-bind="event: { keypress: saveLabel, blur: saveLabel }, value: getLabel()"></input>
								<button class="marker_list_label_save btn btn-default" data-bind="click: saveLabel">Save</button>
							</div>
						</div>
						<div class="col-md-6">
							<div data-bind="ifnot: description">
								<div class="lat">Lat: <span data-bind="text: getPosition().lat().toPrecision(5)"></span></div> 
								<div class="lng">Long: <span data-bind="text: getPosition().lng().toPrecision(5)"></span></div>
							</div>
							<div data-bind="if: description">
								<div data-bind="text: description, attr: { title: 'Close to Lat: ' + getPosition().lat().toPrecision(5) + ', Long: ' + getPosition().lng().toPrecision(5) }"></div>
							</div>
						</div>
						<div class="col-md-6 btn-group" role="group" aria-label="Manage location">
							<button type="submit" title="Go to location" class="btn btn-default btn-sm" data-bind="click: goToLocation">
								<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>							
							</button>
							<button type="submit" class="btn btn-default btn-sm" data-bind="click: toggleVisibility, attr: { title: getVisibilityTitle }">																
								<span class="glyphicon" aria-hidden="true" data-bind="css : { 'glyphicon-eye-open': !observableMap(), 'glyphicon-eye-close': observableMap }"></span>								
							</button>
							<button type="submit" title="Open StreetView" class="btn btn-default btn-sm" data-bind="click: openStreetView, disable: !locationDescription()">
								<span class="glyphicon glyphicon-road" aria-hidden="true"></span>							
							</button>
							<button type="submit" class="btn btn-default btn-sm" title="Remove" data-bind="click: removeMarker">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>							
							</button>
						</div>
					</div>								
				</li>
			</ul>
			</form>
		</div>
		<div id="infowindow">
			<header id="infowindow_header">
				<h2 id="infowindow_title" class="autofilled"></h2>
				<div id="infowindow_position">
					<div>Lat: <span id="infowindow_lat" class="autofilled"></span></div>
					<div>Long: <span id="infowindow_lng" class="autofilled"></span></div>
				</div>
			</header>		
			<div id="infowindow_content" class="autofilled">
				<div class="progress_container" data-bind="if: downloading">
					<div class="progress_indicator glyphicon glyphicon-refresh">					
					</div>
				</div>
				<div id="location_content" data-bind="ifnot: downloading">
					<div id="yelp_content" data-bind="if: data().yelp().length !== 0">
						<h3>Yelp</h3>				
						<ul id="yelp_businesses" class="list-unstyled media-list" data-bind="foreach: data().yelp">
							<li class="business_list_item media">						
								<div class="business_info media-left">
									<div class="business_img">
										<img class="media-object" data-bind="attr: { src: image_url }">
									</div>							
								</div>
								<div class="media-body">
									<header class="media-heading">
										<h3>
											<a data-bind="attr: { href: url }, text: name" target="_blank"></a>
										</h3>
										<address data-bind="text: location.display_address"></address>
										<tel data-bind="text: $data.display_phone"></tel>
									</header>
									<div class="rating">							
										<img class="rating_img" data-bind="attr: { src: rating_img_url_small }" />
										<span data-bind="text: rating"></span>
									</div>
								
								<div class="reviews">
									<ul data-bind="foreach: reviews" class="list-unstyled media-list">
										<li class="review_list_item media">
											<div class="user media-left">
												<img class="media-object" data-bind="attr: { src: user.image_url, title: user.name }" />
											</div>
											<div class="media-body">									
												<div class="review_excerpt" data-bind="text: excerpt"></div>
												<div class="review_rating">
													<img data-bind="attr: { src: rating_image_small_url }" />
													<span data-bind="text: rating"></span>
												</div>
											</div>
										</li>
									</ul>
								</div>
								</div>
							</li>
						</ul>
					</div>
					<div id="streetview_content" data-bind="if: data().streetview().length !== 0">
						<h3>Google Street View</h3>
						<ul id="streetview_image_list" data-bind="foreach: data().streetview">
							<li class="streetview_image_list_item">
								<a data-bind="attr: { href: $data }" target="_blank">
									<img data-bind="attr: { src: $data }">
								</a>	
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endsection