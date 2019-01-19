@extends( 'layouts.event-planner' )

@section( 'title' )
Event Planner
@endsection

@section( 'body-content' )

@push( 'css' )
	<link rel="stylesheet" type="text/css" href="/css/event-planner/index.css" />
@endpush

@if ( count( $errors ) > 0 )
    <div class="alert alert-danger">
        <ul>
            @foreach ( $errors->all() as $error )
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ( $user )	
	@include('event-planner.banner')
	
	<div class="list-group mt-5 mb-5" id="calendarevent-list">
		@foreach( $calendarevents as $calendarevent )
			 <a href="{{ route( 'event-planner.events.show', $calendarevent ) }}" class="calendarevent-item list-group-item list-group-item-action flex-column align-items-start">
			 	 <div class="d-flex w-100 justify-content-between">
				      <h5 class="mb-1 calendarevent-name">{{ $calendarevent->name }}</h5>
				      <small class="calendarevent-date">
				      	{{ $calendarevent->showStartDate() }}
				      	@if( $calendarevent->start_date->format( 'Y m d' ) !== $calendarevent->end_date->format( 'Y m d' ) )
				      		- {{ $calendarevent->showEndDate() }}
				      	@endif
				      </small>
			    </div>
			    <p class="mb-1 calendarevent-location">
			    	{{ $calendarevent->location }}
			    </p>
			    <small class="calendarevent-type">
			    	{{ $calendarevent->type }}
			    </small>
			 </a>
		@endforeach
	</div>
	
@endif


@endsection