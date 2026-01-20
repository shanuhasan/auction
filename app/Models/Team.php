<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function players()
    {
        return $this->belongsToMany(Player::class, 'team_players')
                    ->withPivot('price');
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }

    static public function getTeamByAuctionId($id)
    {
        return self::orderBy('name', 'ASC')
            ->where('auction_id','=', $id)
            ->where('status', '=', '1')
            ->where('is_deleted', '!=', '1')
            ->get();
    }
}
