<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    public function players()
    {
        return $this->hasMany(AuctionPlayer::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }

    static public function getAuction()
    {
        return self::orderBy('name', 'ASC')
            ->whereIn('status', ['ongoing', 'upcoming'])
            ->where('is_deleted', '!=', '1')
            ->get();
    }

    static public function getOngoingAuction()
    {
        return self::orderBy('name', 'ASC')
            ->where('status', '=', 'ongoing')
            ->where('is_deleted', '!=', '1')
            ->get();
    }

    static public function getAuctionName($id)
    {
        $auction = self::find($id);

        return $auction
            ? $auction->name . ' - ' . $auction->season
            : null;
    }
}
