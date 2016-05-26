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

@push('nav-list-items')	
	@if ( Route::current()->getPath() === 'search-my-backyard' )
		<li class="active">Search my Backyard</li>
	@else
		<li><a href="/search-my-backyard">Search my Backyard</a></li>
	@endif
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
				<p>Click the marker or marker icon in the list item to center on the location and show information from Google Maps, Yelp, and Wikipedia about the location. Click the Street icon
				to go to a Google Street view panorama, if available. You can show/hide or remove individual markers in the list as well as click the list entries to select them and 
				use the buttons at the top of the <em>Saved Locations</em> section for bulk actions. Click <em>Clear/select markers</em> to select all markers or clear the selection.</p>
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
							<button type="submit" title="Clear/select markers" class="btn btn-default" data-bind="click: toggleMarkerSelection">
								<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
							</button>
							<button type="submit" title="Remove selected markers" class="btn btn-default" data-bind="click: removeSelectedMarkers">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>							
							</button>
							<div data-bind="if: canHideAllSelection()" class="button_container">
								<button type="submit" title="Hide selected markers" class="btn btn-default" data-bind="click: toggleVisibleSelectedMarkers">																
									<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>								
								</button>
							</div>
							<div data-bind="ifnot: canHideAllSelection()" class="button_container">
								<button type="submit" title="Show all markers" class="btn btn-default" data-bind="click: toggleVisibleSelectedMarkers">
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
		<div id="infowindow" data-bind="if: marker">
			<header id="infowindow_header">
				<h2 id="infowindow_title" data-bind="text: marker().getLabel()" class="autofilled"></h2>
				<div id="infowindow_position">
					<div data-bind="ifnot: marker().locationDescription">
						<div class="lat">Lat: <span id="infowindow_lat" class="autofilled" data-bind="text: marker().getPosition().lat().toPrecision(5)"></span></div> 
						<div class="lng">Long: <span id="infowindow_lng" class="autofilled" data-bind="text: marker().getPosition().lng().toPrecision(5)"></span></div>
					</div>
					<div data-bind="if: marker().locationDescription">
						<div data-bind="text: marker().locationDescription, attr: { title: 'Close to Lat: ' + marker().getPosition().lat().toPrecision(5) + ', Long: ' + marker().getPosition().lng().toPrecision(5) }"></div>
					</div>
				</div>
			</header>		
			<div id="infowindow_content" class="autofilled">
				<div data-bind="ifnot: downloading">
				<ul id="infowindow_main_list" class="nav nav-tabs" data-bind="foreach: services()">
					<li role="presentation" data-bind="css: { active: showView }"><a href="#" data-bind="text: serviceName, click: showTab"></a></li>
				</ul>
				</div>
				<div class="progress_container" data-bind="if: downloading">
					<div class="progress_indicator glyphicon glyphicon-refresh">					
					</div>
				</div>
				<div id="location_content" data-bind="ifnot: downloading">
					<div id="location_data">
						<div id="yelp_container" data-bind="if: getService('yelp').showView">
							<div id="yelp_content" data-bind="if: getService('yelp').data().length !== 0">
								<h3 data-bind="text: getService('yelp').serviceName"></h3>
								<ul id="yelp_businesses" class="list-unstyled media-list" data-bind="foreach: getService('yelp').data">
									<li class="business_list_item media">						
									<div class="business_info media-left">
										<div class="business_img">
											<img class="media-object" data-bind="attr: { src: $data.image_url.replace(/http:/, '') }">
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
											<img class="rating_img" data-bind="attr: { src: rating_img_url_small.replace(/http:/, '') }" />
											<span data-bind="text: rating"></span>
										</div>
									
										<div class="reviews">
											<ul data-bind="foreach: reviews" class="list-unstyled media-list">
												<li class="review_list_item media">
													<div class="user media-left">
														<img class="media-object" data-bind="attr: { src: user.image_url.replace(/http:/, ''), title: user.name }" />
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
						</div>
						<div id="streetview_container" data-bind="if: getService('streetview').showView">
							<h3 data-bind="text: getService('streetview').serviceName"></h3>
							<div id="streetview_content" data-bind="if: getService('streetview').data().length === 0">								
								<div data-bind="if: getService('streetview').data().length === 0">								
									<h4>No Street View images found.</h4>
								</div>																							
							</div>
							<div data-bind="ifnot: getService('streetview').data().length === 0">								
								<ul id="streetview_image_list" class="list-unstyled list-inline" data-bind="foreach: getService('streetview').data">
									<li class="streetview_image_list_item">
										<a data-bind="attr: { href: image }" target="_blank">
											<img class="img-responsive" data-bind="attr: { src: thumbnail }">
										</a>	
									</li>
								</ul>
							</div>	
						</div>
						<div id="wikipedia_container" data-bind="if: getService('wikipedia').showView">
							<div id="wikipedia_content" data-bind="if: getService('wikipedia').data().length !== 0">
								<h3 data-bind="text: getService('wikipedia').serviceName"></h3>																				
								<ul class="wikipedia_article_list list-unstyled" data-bind="foreach: getService('wikipedia').data">
									<li class="wikipedia_article_list_item">
										<div class="wikipedia_article_container" data-bind="if: $data.imageArray">
											<h4>
												<a data-bind="text: title, attr: { href: 'https://en.wikipedia.org/wiki/' + title.replace(/ /g, '_') }" target="_blank"></a>
											</h4>
											<ul class="wikipedia_image_list list-unstyled list-inline" data-bind="foreach: imageArray">
												<li class="wikipedia_image_list_item">
													<a data-bind="attr: { href: $data }" target="_blank">
														<img class="img-responsive" data-bind="attr: { src: $data }">
													</a>											
												</li>
											</ul>
										</div>	
									</li>
								</ul>				
							</div>
						</div>
					</div>					
					
					
				</div>
				
			</div>
		</div>	
	</div>
@endsection