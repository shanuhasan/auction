<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_players');
    }

    static public function findById($id)
    {
        return self::where('id', $id)->first();
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }

    static public function getAllPlayer()
    {
        return self::orderBy('name', 'ASC')
            ->whereIn('status', ['available'])
            ->where('is_deleted', '!=', '1')
            ->get();
    }

    static public function playerRole($key = null){
        $list = [
            'Batsman' => 'Batsman',
            'Bowler' => 'Bowler',
            'All-Rounder' => 'All-Rounder',
            'Wicket-Keeper' => 'Wicket-Keeper'
        ];

        return (isset($key)) ? (isset($list[$key]) ? $list[$key] : $key) : $list;
    }

    static public function playerStatus($key = null){
        $list = [
            'not-available' => 'Not Available',
            'available' => 'Available',
            'sold' => 'Sold',
            'unsold' => 'Unsold'            
        ];

        return (isset($key)) ? (isset($list[$key]) ? $list[$key] : $key) : $list;
    }

    static public function getName($id)
    {
        $model = self::findById($id);
        return (!empty($model)) ? $model->name : '';
    }
}
