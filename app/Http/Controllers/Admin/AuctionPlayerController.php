<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\AuctionPlayer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuctionPlayerController extends Controller
{
    public function index(Request $request)
    {
        $players = AuctionPlayer::orderBy('id', 'DESC');

        if (!empty($request->get('name'))) {
            $players = $players->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $players = $players->paginate(20);

        return view('admin.auction-player.index', [
            'players' => $players
        ]);
    }

    public function edit($guid, Request $request)
    {
        $player = Player::findByGuid($guid);
        if (empty($player)) {
            return redirect()->route('admin.auction-player.index');
        }

        return view('admin.auction-player.edit', compact('player'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'auction_id' => 'required'
        ]);

        if ($validator->passes()) {
            
            $model = new AuctionPlayer();
            $model->guid = GUIDv4();
            $model->player_id = $request->player_id;
            $model->auction_id = $request->auction_id;
            $model->status = 'live';
            $model->save();

            session()->flash('success', 'Auction Start...');
            return response()->json([
                'status' => true,
                'guid'=> $model->guid
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function start($guid, Request $request)
    {
        $auctionPlayer = AuctionPlayer::findByGuid($guid);

        $player = Player::findById($auctionPlayer->player_id);
        if (empty($player)) {
            return redirect()->route('admin.auction-player.index');
        }

        return view('admin.auction-player.start', [
            'player'=>$player,
            'auctionPlayer'=>$auctionPlayer
        ]);
    }

    // sold player
    public function update($guid, Request $request)
    {
        $model = AuctionPlayer::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Auction Player not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Auction Player not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required',
            'sold_price' => 'required'
        ]);

        if ($validator->passes()) {

            $team = Team::find($request->team_id);

            if($team->remaining_purse < $request->sold_price){
                $request->session()->flash('error', 'Insufficant Purse.');
                return response()->json([
                    'status' => false,
                    'notFound' => true,
                    'guid' => $guid,
                    'message' => 'Insufficant Purse.'
                ]);
            }

            $model->team_id = $request->team_id;
            $model->sold_price = $request->sold_price;
            $model->save();

            $request->session()->flash('success', 'Player Sold successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Player Sold successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
