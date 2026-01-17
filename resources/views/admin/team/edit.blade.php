@extends('admin.layouts.app')
@section('title', 'Edit Team')
@section('team', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Team /</span> Edit</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Team Info</h5>
                    <small class="text-muted float-end"></small>
                </div>
                <div class="card-body">
                    <form action="" id="auctionForm" method="post">
                         @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ $team->name }}" />
                                <p class="error"></p>                            
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="short_name">Short Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="short_name" placeholder="Enter Short Name" name="short_name" value="{{ $team->short_name }}" />
                                <p class="error"></p>                            
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="total_purse">Total Purse</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="total_purse" class="form-control" placeholder="Enter Season" name="total_purse" value="{{ $team->total_purse }}" />
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="auction_id" class="col-sm-2 col-form-label">Auction</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="auction_id" name="auction_id">
                                    <option selected>Select Auction</option>
                                    @foreach(\App\Models\Auction::getAuction() as $item)
                                        <option {{$item->id == $team->auction_id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }} {{ $item->season }}</option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="status" aria-label="Default select example" name="status">
                                    <option {{ $team->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $team->status == '0' ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="image">Logo</label>
                            <input type="hidden" name="image_id" id="image_id" value="">
                            <div class="col-sm-10">
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                                <p class="error"></p>
                                @if (!empty($team->logo))
                                    <div>
                                        <img width="200" src="{{ asset('uploads/team/' . $team->logo) }}"
                                            alt="">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.team.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#auctionForm').submit(function(e) {
            e.preventDefault();
            var elements = $(this);
            $('button[type=submit]').prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.team.update', $team->guid) }}",
                type: 'put',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {

                        window.location.href = "{{ route('admin.team.index') }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {

                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('admin.team.index') }}";
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

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('admin.media.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection