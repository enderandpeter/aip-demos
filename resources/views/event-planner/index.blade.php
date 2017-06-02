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
	<div id="banner">
		<div id="user_controls">
			<div id="user_info">
				<span id="user_greeting">Hello, {{ $user->name }}!</span>
				<span id="auth_controls">
					<form id="logout-form" action="{{ route('event-planner.logout') }}" method="POST">
						<button id="logout-button">Logout</button>
					    {{ csrf_field() }}
                    </form>
				</span>
			</div>
		</div>
	</div>
	
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