@extends('layouts.default')
@section('content')
	@if(count($achievements))
		{{ Table::open(array('class' => 'achievements')) }}
		@if( Authority::can('manage', 'achievements') )
			{{ Table::headers('Name', 'Description', 'Achieved By', 'Controls') }}
		@else
			{{ Table::headers('Name', 'Description', 'Achieved By') }}
		@endif
		<?php
		foreach( $achievements as $achievement )
		{
			if( Authority::can('manage', 'achievements') )
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'users'			=> $achievement->users->count(),
					'controls'		=> 	Button::sm_link(URL::route('awards.create', array('achievement_id' => $achievement->id)), 'Award' )->with_icon('user')  . ' ' .
										HTML::button('achievements.edit',$achievement->id, ['size' => 'sm']) .  
										HTML::button('achievements.destroy',$achievement->id, ['size' => 'sm']),
				);
			}
			else
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'users'			=> $achievement->users->count(),
				);	
			}
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
		{{ $achievements->links() }}
		<br>
		@if( Authority::can('manage', 'achievements') )
			@if( ! Input::get('hidden') )
				{{ link_to_route('achievements.index', 'Show hidden achievements', array('hidden' => 1)) }}
			@else
				{{ link_to_route('achievements.index', 'Show all achievements', array('hidden' => 0)) }}
				@endif
		@endif
	@else
		No achievements found!
	@endif
@endsection
