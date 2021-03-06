@extends('layouts.default')
@section('content')
	<?php $state = $user->states()->latest()->first(); ?>
	@if($user->steam_visibility == 1 AND Auth::check() AND $user->id == Auth::user()->id)
		{{ Alert::error('<strong>Oh no!</strong> Your Steam Profile is set to private. This means the LANager can\'t add you to the "popular games" or "popular servers" pages - please consider ' .
		link_to(SteamBrowserProtocol::openSteamPage('SteamIDEditPage'), 'changing your profile\'s privacy settings to public') . ', even if it\'s just for the event. Thanks!' ) }}
	@endif
	<div class="user-profile-header">
		@include('users.partials.avatar', ['user' => $user, 'size' => 'large'] )
		<ul class="user-profile-actions pull-right">
			@if( Auth::check() AND $user->id == Auth::user()->id )
				<li>{{ Button::link( SteamBrowserProtocol::openSteamPage('SteamIDEditPage'), 'Edit Profile' ) }}</li>
				<li>{{ HTML::button( 'users.destroy', $user, ['value' => 'Delete Account', 'confirmation' => 'Are you sure you want to permanently delete your account?'] ) }}</li>
			@else
				<li>{{ Button::link( SteamBrowserProtocol::addFriend($user->steam_id_64), 'Add' ) }}</li>
				<li>{{ Button::link( SteamBrowserProtocol::messageFriend($user->steam_id_64), 'Message' ) }}</li>
				<li>{{ Button::link( 'http://www.steamcommunity.com/profiles/'.$user->steam_id_64, 'View Steam Profile', array('target' => '_blank') ) }}</li>
			@endif
		</ul>
	</div>
	<div class="user-profile-content">
		<div class="user-status pull-right">
			@if( count($state) )
				{{ $state->getStatus() }}
				@if( isset( $state->application->steam_app_id) )
					:
					<a href="{{ SteamBrowserProtocol::viewAppInStore($state->application->steam_app_id) }}">
						{{{ $state->application->name }}}<br>
						<img src="{{ $state->application->getLogo() }}" alt="Game Logo">
					</a>
					<br>
					@if( isset( $state->server->address ) )
						{{ link_to( SteamBrowserProtocol::connectToServer( $state->server->getFullAddress() ), $state->server->getFullAddress() ) }}
					@endif
				@endif
			@endif
		</div>
		<?php $awards = $user->awards()->orderBy('lan_id','desc')->get(); ?>
		<h2>Achievements</h2>
		@include('awards.list')
		@if( Authority::can( 'manage', 'achievements' ) )
			<ul>
				<li>{{ Button::link(URL::route('awards.create', array('user_id' => $user->id)), 'Award Achievements' ) }}</li>
			</ul>
		@endif
		@if( count($user->shouts) )
			<?php $shouts = $user->shouts()->orderBy('created_at','desc')->take(3)->get(); ?>
			<h2>Shouts</h2>
			@include('shouts.list')
		@endif
		@if( count($user->roles) )
			<h2>Roles</h2>
			<ul>
				@foreach($user->roles as $role)
					<li>{{{ $role->name }}}</li>
				@endforeach
			</ul>
		@endif
		@if( Authority::can( 'manage', 'users', $user ) )
			<h2>Administration</h2>
			<ul>
				<li>{{ Button::link(URL::route('role-assignments.index'), 'Manage Roles' ) }}</li>
				<li>{{ HTML::button('users.destroy',$user) }}</li>
			</ul>
		@endif
	</div>
@endsection
