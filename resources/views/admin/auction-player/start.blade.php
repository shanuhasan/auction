@extends('admin.layouts.app')
@section('title', 'Start Player Auction')
@section('auction-player', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Player /</span> Auction Started</h4>

    <!-- Basic Layout & Basic with Icons -->
     @include('admin.message')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Player Info</h5>
                    <small class="text-muted float-end"></small>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="name">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ $player->name }}" readonly />
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="base_price">Base Price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control only-number" id="base_price" placeholder="Enter Base Price" name="base_price" value="{{ $player->base_price }}" readonly />
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="role">Role</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control only-number" id="role" placeholder="Enter Role" name="role" value="{{ $player->role }}" readonly />
                            <p class="error"></p>
                        </div>
                    </div>
                       
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="image">Photo</label>
                        <div class="col-sm-10">
                            @if (!empty($player->image))
                                <div>
                                    <img width="200" src="{{ asset('uploads/player/' . $player->image) }}" alt="{{$player->name}}">
                                </div>
                            @else
                                <div>
                                    <img width="200" src="{{ asset('uploads/player/dummy.jpg') }}" alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Biding Info</h5>
                </div>
                <div class="card-body">
                   <form action="" id="startBidingForm" method="post">
                        @csrf
                        <div class="row mb-3">
                            <label for="team_id" class="col-sm-2 col-form-label">Team</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="team_id" name="team_id">
                                    <option value="">Select Team</option>
                                    @foreach(\App\Models\Team::getTeamByAuctionId($auctionPlayer->auction_id) as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="sold_price">Sold Price</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control only-number" id="sold_price" placeholder="Enter Sold Price" name="sold_price" />
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Biding / Sold</button>
                                <a href="{{ route('admin.player.index') }}" class="btn btn-danger ml-3">Unsold</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    var auctionStartUrl = "{{ route('admin.auction-player.start', ':guid') }}";

    $('#startBidingForm').submit(function(e) {
        e.preventDefault();
        var elements = $(this);
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: "{{ route('admin.auction-player.update', $auctionPlayer->guid) }}",
            type: 'put',
            data: elements.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $('button[type=submit]').prop('disabled', false);
                if (response['status'] == true) {

                    window.location.href = "{{ route('admin.player.index') }}";
                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                } else {

                    if (response['notFound'] == true) {
                        window.location.href = auctionStartUrl.replace(':guid', response['guid']);
                    }

                    var errors = response['errors'];

                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    $.each(errors, function(key, val) {
                        $('#' + key).addClass('is-invalid').siblings('p').addClass(
                            'invalid-feedback').html(val);
                    });
                }
            },
            error: function(jqXHR) {
                console.log('Something went wrong.');
            }
        });
    });
</script>
@endsection