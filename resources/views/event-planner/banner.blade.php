@section('banner')
<div id="banner">
    <div id="user_controls">
        <div id="user_info">
            <span id="user_greeting">Hello, {{ $user->name }}!</span>
            <span id="auth_controls">
				<form id="logout-form" action="{{ route('event-planner.logout') }}" method="POST">
					<button id="logout-button" class="btn btn-primary ml-1">Logout</button>
				    {{ csrf_field() }}
                   </form>
			</span>
        </div>
    </div>
</div>
@show()