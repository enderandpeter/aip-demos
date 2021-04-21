[![Build Status](https://travis-ci.org/enderandpeter/aip-demos.svg?branch=master)](https://travis-ci.org/enderandpeter/aip-demos)

# An Internet Presence - Demos

This is the Laravel site for [An Internet Presence](http://aninternetpresence.net/) which demonstrates web applications created by the site author. It is essentially a gallery of
exhibitions. Projects that were inspired by online code schools will be noted.

<a href="https://demos.aninternetpresence.net/search-my-backyard" target="_blank"><h2>Search My Backyard</h2></a>

An interactive map powered by the [Google Maps JavaScript API](https://developers.google.com/maps/documentation/javascript/tutorial). After a user
agrees to share their location, it will center to that location. Click the map to create markers which show up in the list. Click a marker or the
marker icon for the list item to center to the location and show an info window with [Yelp reviews](http://www.yelp.com/developers/documentation), [Street View images](https://developers.google.com/maps/documentation/streetview/intro), and [Wikipedia article images](https://www.mediawiki.org/wiki/API:Main_page) for that area. Click the street icon to open a Street View at the marker. You can also show/hide markers and
remove them individually or in bulk.

Code can be found at `resources/views/search-my-backyard.blade.php`

This is the [final project](https://classroom.udacity.com/courses/ud989/lessons/3580848605/concepts/35254789990923) for [JavaScript Design Patterns](https://www.udacity.com/course/javascript-design-patterns--ud989). 

## Tests

Run the feature tests with `php artisan test`

Run the [Dusk](https://laravel.com/docs/8.x/dusk) tests with `php artisan dusk`. You should run this in a graphical environment that has Google Chrome so that it will
run all the tests in the browser. If running in a command-line environment, quite a bit of preliminary setup is involved. You will see examples in the Dusk docs for [setting up with TravisCI](https://laravel.com/docs/8.x/dusk#running-tests-on-travis-ci).

Also, the [Docker Deployment repo](https://gitlab.com/aninternetpresence/docker-deployment) which defines a Docker Compose setup for AIP's services is setup
to run Dusk tests in the `demos_php` Docker container, so take a look at that setup for an example.

## Bugs
* In Firefox, the Street View panorama may show a gray background when first loaded.
    
<a href="https://demos.aninternetpresence.net/frogger" target="_blank"><h2>Effective JavaScript: Frogger</h2></a>

An HTML5 Canvas / JavaScript game created from a template and assets that were provided by Udacity. Use the left/right arrow keys to select a character from the list and the up/down arrow to choose the character. Once in the level, use the directional keys to move around and collect gems to score points. Hearts replinish health and points. Keep making it across the other side until you run out of life or points.

This is the [final project](https://classroom.udacity.com/courses/ud015/lessons/3072058665/concepts/31018886370923) for [Object-Oriented JavaScript](https://www.udacity.com/course/object-oriented-javascript--ud015).

<hr>

As always, please feel free to share feedback and suggestions. Some of the older projects will most likely not be expanded on, but if there is anything you would really like to see built out even more, just let me know and I will consider taking another look.
