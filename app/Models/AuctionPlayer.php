<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionPlayer extends Model
{
    protected $fillable = [
        'in_process', // ✅ add this
    ];

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }
}
