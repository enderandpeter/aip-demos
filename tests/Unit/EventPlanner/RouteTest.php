<?php

namespace Tests\Unit\EventPlanner;

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * Test to confirm that all defined routes exist
     * 
     * @return void
     */
    public function testRoutes(){
    	// A definition of the expected routes    	
    	$routelist = [
    			'event-planner' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner'
    			],
    			'event-planner.events.store' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/events'
    			],
    			'event-planner.events.index' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/events'
    			],
    			'event-planner.events.create' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/events/create'
    			],
    			'event-planner.events.destroy' => [
    					'methods' => ['DELETE'],
    					'path' => 'event-planner/events/{event}'
    			],
    			'event-planner.events.show' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/events/{event}'
    			],
    			'event-planner.events.update' => [
    					'methods' => ['PUT', 'PATCH'],
    					'path' => 'event-planner/events/{event}'
    			],
    			'event-planner.events.edit' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/events/{event}/edit'
    			],
    			'event-planner.login.show' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/login'
    			],
    			'event-planner.login.post' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/login'
    			],
    			'event-planner.logout' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/logout'
    			],
    			'event-planner.password-reset.email' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/password/email'
    			],
    			'event-planner.password-reset.post' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/password/reset'
    			],
    			'event-planner.password-reset.show' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/password/reset/{token?}'
    			],
    			'event-planner.register.show' => [
    					'methods' => ['GET', 'HEAD'],
    					'path' => 'event-planner/register'
    			],
    			'event-planner.register.post' => [
    					'methods' => ['POST'],
    					'path' => 'event-planner/register'
    			]
    	];
    	
    	$HTTP_METHODS = ['GET', 'HEAD', 'POST', 'DELETE', 'PUT', 'PATCH', 'UPDATE'];
    	
    	chdir( base_path() );
    	
    	$command = 'php artisan route:list';
    	
    	exec( $command, $output );
    	
    	$appRouteArray = [];
    	foreach( $output as $line ){
    		$lineData = explode( '|', $line );
    		$lineData_nonEmpty = array_filter( $lineData, function( $item ){
    			return !empty( trim( $item ) );
    		});
    	
    			$lineData_nonEmpty = array_values( $lineData_nonEmpty );
    	
    			$http_methods = [];
    			array_walk($lineData_nonEmpty, function( &$item ) use( $HTTP_METHODS, &$http_methods ) {
    				$item = trim( $item );
    				$found_http_method = array_search( $item, $HTTP_METHODS );
    				if( $found_http_method !== false ){
    					$http_methods[] = $HTTP_METHODS[$found_http_method];
    				}
    			});
    	
    				$ROUTE_NAME_OFFSET = 3;
    				$ROUTE_PATH_OFFSET = 4;
    	
    				if(!isset( $lineData_nonEmpty[count( $lineData_nonEmpty ) - $ROUTE_NAME_OFFSET] ) ||
    						!isset( $lineData_nonEmpty[count( $lineData_nonEmpty ) - $ROUTE_PATH_OFFSET] ) ){
    							continue;
    				}
    	
    				$route_name = $lineData_nonEmpty[count( $lineData_nonEmpty ) - $ROUTE_NAME_OFFSET];
    				$route_path = $lineData_nonEmpty[count( $lineData_nonEmpty ) - $ROUTE_PATH_OFFSET];
    	
    				if( strpos( $route_name, 'event-planner' ) !== 0 ){
    					continue;
    				}
    	
    				$appRouteArray[$lineData_nonEmpty[count($lineData_nonEmpty) - $ROUTE_NAME_OFFSET]] = [
    						'methods' => $http_methods,
    						'path' => $lineData_nonEmpty[count($lineData_nonEmpty) - $ROUTE_PATH_OFFSET]
    				];
    	}
    	
    	$this->assertEquals($routelist, $appRouteArray);
    }
}
