<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Auction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index($guid = null, Request $request)
    {
        $auction = Auction::findByGuid($guid);

        if(!empty($auction)){
            $auctionPlayers = $auction->players()->where('in_process', 1)->first();
            if(!empty($auctionPlayers)){
                $player = Player::findById($auctionPlayers->player_id);
            }
            
            $teams = $auction->teams()->where('auction_id', $auction->id)->get();

            return view('auction',[
                'player' => $player ?? null,
                'teams' => $teams,
                'auctionPlayer' => $auctionPlayers
            ]);
        }  

        return view('noauction');

        
        
        
    }
}
