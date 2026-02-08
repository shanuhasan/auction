<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    static public function getPlayersByTeamId($teamId)
    {
        return self::where('team_id', $teamId)
                    ->get();
    }
}
