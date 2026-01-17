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
}
