<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Auction</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* LEFT PANEL */
        .player-panel {
            width: 25%;
            background: #1f2937;
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
        }

        .player-photo {
            height: 350px;
            border-radius: 8px;
            overflow: hidden;
        }

        .player-photo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .player-details h2 {
            margin: 15px 0 5px;
        }

        .price {
            margin-top: 15px;
            font-size: 22px;
            color: #22c55e;
            font-weight: bold;
        }

        /* RIGHT PANEL */
        .team-panel {
            width: 65%;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;

            /* ⭐ GRID COLUMN LAYOUT ⭐ */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 15px;
        }

        .team {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .team-header {
            padding: 12px;
            background: #2563eb;
            color: #fff;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .player-list {
            padding: 10px 15px;
        }

        .player-list ul {
            margin: 0;
            padding-left: 18px;
        }

        .player-list li {
            padding: 5px 0;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .player-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- LEFT -->
    <div class="player-panel">
        <div class="player-photo">
             @if (!empty(@$player->image))
                <img width="200" src="{{ asset('uploads/player/' . $player->image) }}" alt="Player Image">
            @else
                <img width="200" src="{{ asset('uploads/player/dummy.jpg') }}" alt="Dummy Image">
            @endif
        </div>
        <div class="player-details">
            <h2>{{@$player->name}}</h2>
            <p>Role: {{@$player->role}}</p>
            <div class="price">Base Price: ₹{{@$player->base_price}}</div>
            @if(@$auctionPlayer->status == 'sold')
                <h2>Sold By {{\App\Models\Team::getName($auctionPlayer->team_id)}}</h2>
                <div class="price">Sold Price: ₹{{$auctionPlayer->sold_price}}</div>
            @endif
        </div>
    </div>

    <!-- RIGHT (COLUMN TEAMS) -->
    <div class="team-panel">

        @foreach($teams as $team)
        <div class="team">
            <div class="team-header">{{$team->name}}<br><span>Remaining Purse: ₹{{\App\Models\Team::remainingPurse($team->id)}}</span></div>
            <div class="player-list">
                <ul>
                    @php
                        $players = \App\Models\TeamPlayer::getPlayersByTeamId($team->id);
                    @endphp
                    @if($players->isEmpty())
                        <li>No Players</li>
                    @else
                        @foreach($players as $player)
                            <li><strong>{{\App\Models\Player::getName($player->player_id)}} {{ $player->is_captain == 1 ? '(C)' : '' }} {{ $player->is_wk == 1 ? '(WK)' : '' }}</strong></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        @endforeach
    </div>

</div>

</body>
</html>

<script>
    setTimeout(function() {
        location.reload();
    }, 5000); // Refresh every 30 seconds
</script>
