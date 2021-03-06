<?php namespace Zeropingheroes\Lanager\Playlists\Items;

use Zeropingheroes\Lanager\BaseController;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\Playlists\Items\Item,
	Zeropingheroes\Lanager\Playlists\Items\Votes\Vote;
use Response, Auth, Request, Redirect, Event;

class VotesController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('permission', array('only' => array('store', 'destroy')) );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($playlistId, $itemId)
	{
		$item = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId);

		$vote = new Vote;
		$vote->playlist_item_id = $itemId;
		$vote->user_id = Auth::user()->id;
		$vote->vote = -1; // down vote

		return $this->process( $vote, 'playlists.items.index', 'playlists.items.index' );

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistId, $itemId, $voteId)
	{
		$vote = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId)->votes()->findOrFail($voteId);

		return $this->process( $vote, 'playlists.items.index', 'playlists.items.index' );
	}

}