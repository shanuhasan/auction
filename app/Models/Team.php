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

    static public function findById($id)
    {
        return self::where('id', $id)->first();
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

    static public function getName($id)
    {
        $model = self::findById($id);
        return (!empty($model)) ? $model->name : '';
    }

    static public function remainingPurse($id)
    {
        $model = self::findById($id);
        return (!empty($model)) ? $model->remaining_purse : '';
    }
}
