@extends('admin.layouts.app')
@section('title', 'Auction Player')
@section('auction-player', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Home /</span> Auction Player</h4>
        <!-- <a href="{{ route('admin.player.create') }}" class="btn btn-primary">Add Player</a> -->
    </div>    
    @include('admin.message')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <form action="" method="get">
                    <h5 class="card-header">Filters</h5>
                    <div class="row card-body">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <select class="form-select" id="player_id" name="player_id">
                                <option selected>Select Player</option>
                                @foreach(\App\Models\Player::getAllPlayer() as $item)
                                    <option value="{{ $item->guid }}" {{ $item->guid == request()->get('player_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-success">Search</button>
                            <a href="{{ route('admin.auction-player.index') }}" class="btn btn-outline-dark ml-3">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Name</th>
                        <th>Auction</th>
                        <th>Team</th>
                        <th>Sold Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($players->isNotEmpty())
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($players as $player)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong>{{\App\Models\Player::getName($player->player_id)}}</strong></td>
                        <td><strong>{{\App\Models\Auction::getAuctionName($player->auction_id)}}</strong></td>
                        <td><strong>{{\App\Models\Team::getName($player->team_id)}}</strong></td>
                        <td><strong>{{$player->sold_price}}</strong></td>
                        <td><strong>{{$player->status}}</strong></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.auction-player.start', $player->guid) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">Record Not Found</td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $players->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection